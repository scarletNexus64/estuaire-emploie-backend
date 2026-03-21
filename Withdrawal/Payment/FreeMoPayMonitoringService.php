<?php

namespace App\Services\Payment;

use App\Models\ApiHealthLog;
use App\Models\User;
use App\Notifications\ApiHealthAlertNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class FreeMoPayMonitoringService
{
    // Latency thresholds in milliseconds
    private const LATENCY_OK = 2000;        // Under 2s = OK
    private const LATENCY_DEGRADED = 5000;  // Under 5s = Degraded
    // Above 5s or timeout = Error

    public function __construct(
        private FreeMoPayService $freeMoPayService
    ) {}

    /**
     * Check the health of the FreeMoPay API.
     */
    public function checkHealth(): ApiHealthLog
    {
        $startTime = microtime(true);
        $status = ApiHealthLog::STATUS_OK;
        $errorMessage = null;
        $errorCode = null;
        $metadata = [];

        try {
            $result = $this->freeMoPayService->testConnection();

            $latencyMs = (int) round((microtime(true) - $startTime) * 1000);

            // Determine status based on latency
            if ($latencyMs > self::LATENCY_DEGRADED) {
                $status = ApiHealthLog::STATUS_DEGRADED;
                $errorMessage = "Latence élevée: {$latencyMs}ms";
            } elseif ($latencyMs > self::LATENCY_OK) {
                $status = ApiHealthLog::STATUS_DEGRADED;
                $errorMessage = "Latence modérée: {$latencyMs}ms";
            }

            // Check if connection test was successful
            if (isset($result['success']) && !$result['success']) {
                $status = ApiHealthLog::STATUS_ERROR;
                $errorMessage = $result['message'] ?? 'Erreur de connexion';
            }

            $metadata = [
                'response' => $result,
                'latency_ms' => $latencyMs,
            ];

        } catch (\Exception $e) {
            $latencyMs = (int) round((microtime(true) - $startTime) * 1000);
            $status = ApiHealthLog::STATUS_ERROR;
            $errorMessage = $e->getMessage();
            $errorCode = (string) $e->getCode();

            $metadata = [
                'exception' => get_class($e),
                'trace' => $e->getTraceAsString(),
            ];

            Log::error('FreeMoPay health check failed', [
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
            ]);
        }

        // Log the health check
        $healthLog = ApiHealthLog::create([
            'service_name' => ApiHealthLog::SERVICE_FREEMOPAY,
            'status' => $status,
            'latency_ms' => $latencyMs ?? null,
            'error_message' => $errorMessage,
            'error_code' => $errorCode,
            'metadata' => $metadata,
            'checked_at' => now(),
        ]);

        // Send alert if status is not OK
        if ($status !== ApiHealthLog::STATUS_OK) {
            $this->sendAlertIfNeeded($healthLog);
        }

        return $healthLog;
    }

    /**
     * Send alert notification to admins if this is a new incident.
     */
    protected function sendAlertIfNeeded(ApiHealthLog $healthLog): void
    {
        // Check if we already sent an alert in the last hour for this service
        $recentAlert = ApiHealthLog::forService(ApiHealthLog::SERVICE_FREEMOPAY)
            ->where('notification_sent', true)
            ->where('checked_at', '>=', now()->subHour())
            ->exists();

        if ($recentAlert) {
            return; // Don't spam admins
        }

        // Send notification to all admins
        $admins = User::role('admin')->get();

        if ($admins->isEmpty()) {
            Log::warning('No admins found to send health alert');
            return;
        }

        Notification::send($admins, new ApiHealthAlertNotification($healthLog));

        // Mark the log as notified
        $healthLog->update(['notification_sent' => true]);

        Log::info('FreeMoPay health alert sent to admins', [
            'status' => $healthLog->status,
            'admin_count' => $admins->count(),
        ]);
    }

    /**
     * Get the current health status.
     */
    public function getCurrentStatus(): array
    {
        $latest = ApiHealthLog::getLatestForService(ApiHealthLog::SERVICE_FREEMOPAY);

        return [
            'status' => $latest?->status ?? ApiHealthLog::STATUS_UNKNOWN,
            'status_label' => $latest?->status_label ?? 'Inconnu',
            'status_color' => $latest?->status_color ?? 'gray',
            'latency_ms' => $latest?->latency_ms,
            'last_check' => $latest?->checked_at,
            'error_message' => $latest?->error_message,
            'uptime_24h' => ApiHealthLog::getUptimePercentage(ApiHealthLog::SERVICE_FREEMOPAY, 24),
            'avg_latency_24h' => round(ApiHealthLog::getAverageLatency(ApiHealthLog::SERVICE_FREEMOPAY, 24) ?? 0),
        ];
    }

    /**
     * Get health history for the dashboard.
     */
    public function getHealthHistory(int $hours = 24, int $limit = 50): array
    {
        $logs = ApiHealthLog::forService(ApiHealthLog::SERVICE_FREEMOPAY)
            ->recent($hours)
            ->orderBy('checked_at', 'desc')
            ->limit($limit)
            ->get();

        return $logs->map(fn ($log) => [
            'id' => $log->id,
            'status' => $log->status,
            'status_label' => $log->status_label,
            'status_color' => $log->status_color,
            'latency_ms' => $log->latency_ms,
            'error_message' => $log->error_message,
            'checked_at' => $log->checked_at->toISOString(),
        ])->toArray();
    }

    /**
     * Get incidents (errors and degraded states) for the last N days.
     */
    public function getIncidents(int $days = 7): array
    {
        $logs = ApiHealthLog::forService(ApiHealthLog::SERVICE_FREEMOPAY)
            ->withErrors()
            ->where('checked_at', '>=', now()->subDays($days))
            ->orderBy('checked_at', 'desc')
            ->get();

        return $logs->map(fn ($log) => [
            'id' => $log->id,
            'status' => $log->status,
            'status_label' => $log->status_label,
            'error_message' => $log->error_message,
            'error_code' => $log->error_code,
            'latency_ms' => $log->latency_ms,
            'checked_at' => $log->checked_at->toISOString(),
            'notification_sent' => $log->notification_sent,
        ])->toArray();
    }
}
