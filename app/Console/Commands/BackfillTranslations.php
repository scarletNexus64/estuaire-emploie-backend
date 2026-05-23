<?php

namespace App\Console\Commands;

use App\Models\Job;
use App\Models\QuickService;
use App\Models\Resume;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Backfill translations on translatable user-generated content (jobs, resumes,
 * quick-services) using the Anthropic Claude API.
 *
 * Reads the canonical (source language) columns and writes localized values
 * into the polymorphic `translations` table for the target locales.
 *
 * Idempotent: skips records that already have a non-empty translation for the
 * requested (locale, field) pair. Safe to re-run.
 *
 * Requires ANTHROPIC_API_KEY in .env. Uses claude-haiku-4-5 by default
 * (cheap + fast).
 *
 * Usage:
 *   php artisan translations:backfill jobs
 *   php artisan translations:backfill jobs --limit=10 --locales=en,es
 *   php artisan translations:backfill quick-services --dry-run
 *   php artisan translations:backfill resumes --id=42
 */
class BackfillTranslations extends Command
{
    protected $signature = 'translations:backfill
                            {model : jobs | resumes | quick-services}
                            {--locales=en,es,ar : comma-separated target locales}
                            {--limit=0 : max records to process (0 = no limit)}
                            {--id= : process a single record by id}
                            {--dry-run : log what would be translated without calling the API}
                            {--model-name=claude-haiku-4-5-20251001 : Anthropic model to use}';

    protected $description = 'Backfill EN/ES/AR translations on jobs, resumes or quick-services via Claude API';

    /**
     * Map of {model arg} → [class, translatable fields, optional kind label]
     */
    private const MODELS = [
        'jobs' => [
            'class' => Job::class,
            'fields' => ['title', 'description', 'requirements', 'benefits'],
            'kind' => 'job posting',
        ],
        'quick-services' => [
            'class' => QuickService::class,
            'fields' => ['title', 'description'],
            'kind' => 'quick service ad',
        ],
        'resumes' => [
            'class' => Resume::class,
            'fields' => ['title', 'professional_summary'],
            'kind' => 'resume / CV',
        ],
    ];

    public function handle(): int
    {
        $modelKey = $this->argument('model');

        if (! isset(self::MODELS[$modelKey])) {
            $this->error("Unknown model '$modelKey'. Use one of: " . implode(', ', array_keys(self::MODELS)));
            return self::INVALID;
        }

        $apiKey = env('ANTHROPIC_API_KEY');
        $dryRun = (bool) $this->option('dry-run');

        if (! $apiKey && ! $dryRun) {
            $this->error('ANTHROPIC_API_KEY is missing in .env. Either add it, or use --dry-run.');
            return self::FAILURE;
        }

        $config = self::MODELS[$modelKey];
        $modelClass = $config['class'];
        $fields = $config['fields'];
        $kind = $config['kind'];

        $targetLocales = array_filter(array_map('trim', explode(',', $this->option('locales'))));
        $supported = config('translations.locales', ['fr', 'en', 'es', 'ar']);
        $invalid = array_diff($targetLocales, $supported);
        if (! empty($invalid)) {
            $this->error('Unsupported locales: ' . implode(', ', $invalid));
            return self::INVALID;
        }

        $query = $modelClass::query()->with('translations');

        if ($id = $this->option('id')) {
            $query->where('id', $id);
        }

        $limit = (int) $this->option('limit');

        // Use a clone for the count so the original query is unaffected if we
        // later apply ->limit() / ->get().
        $total = $limit > 0 ? min($limit, (clone $query)->count()) : $query->count();
        if ($total === 0) {
            $this->info("Nothing to process for $modelKey.");
            return self::SUCCESS;
        }

        $this->info("Backfilling $total $modelKey → " . implode(',', $targetLocales)
            . ($dryRun ? ' [DRY RUN]' : ''));

        $bar = $this->output->createProgressBar($total);
        $bar->start();

        $stats = [
            'processed' => 0,
            'translated' => 0,
            'skipped' => 0,
            'errors' => 0,
            'api_calls' => 0,
        ];

        $iterate = function (callable $callback) use ($query, $limit) {
            if ($limit > 0) {
                $records = $query->orderBy('id')->limit($limit)->get();
                $callback($records);
                return;
            }
            $query->chunkById(50, $callback);
        };

        $iterate(function ($records) use (&$stats, $fields, $targetLocales, $kind, $dryRun, $bar, $modelKey) {
            foreach ($records as $record) {
                $stats['processed']++;

                $sourceLocale = $record->language ?? 'fr';

                $sourcePayload = [];
                foreach ($fields as $field) {
                    $value = $record->getAttribute($field);
                    if (is_string($value) && trim($value) !== '') {
                        $sourcePayload[$field] = $value;
                    }
                }

                if (empty($sourcePayload)) {
                    $bar->advance();
                    continue;
                }

                // Always ensure the source-language translation exists, so
                // localizeForApi picks it up when ?lang= matches the source.
                foreach ($sourcePayload as $field => $value) {
                    $existing = $record->translations
                        ->where('field', $field)
                        ->firstWhere('locale', $sourceLocale);
                    if (! $existing || $existing->value === null || $existing->value === '') {
                        if (! $dryRun) {
                            $record->setTranslation($field, $sourceLocale, $value);
                        }
                    }
                }

                foreach ($targetLocales as $locale) {
                    if ($locale === $sourceLocale) {
                        continue;
                    }

                    // Check which fields still need translation for this locale.
                    $missingFields = [];
                    foreach ($sourcePayload as $field => $value) {
                        $existing = $record->translations
                            ->where('field', $field)
                            ->firstWhere('locale', $locale);
                        if (! $existing || $existing->value === null || $existing->value === '') {
                            $missingFields[] = $field;
                        }
                    }

                    if (empty($missingFields)) {
                        $stats['skipped']++;
                        continue;
                    }

                    $missingPayload = array_intersect_key($sourcePayload, array_flip($missingFields));

                    if ($dryRun) {
                        Log::info("[backfill-translations DRY] $modelKey #{$record->id} → $locale: " . implode(', ', $missingFields));
                        $stats['translated'] += count($missingFields);
                        continue;
                    }

                    try {
                        $translated = $this->translateBatch($missingPayload, $sourceLocale, $locale, $kind);
                        $stats['api_calls']++;

                        foreach ($translated as $field => $value) {
                            if (! is_string($value) || $value === '') {
                                continue;
                            }
                            $record->setTranslation($field, $locale, $value);
                            $stats['translated']++;
                        }
                    } catch (Throwable $e) {
                        $stats['errors']++;
                        Log::error("[backfill-translations] $modelKey #{$record->id} → $locale failed: " . $e->getMessage());
                        $this->newLine();
                        $this->warn("✗ $modelKey #{$record->id} → $locale: {$e->getMessage()}");
                    }
                }

                $bar->advance();
            }
        });

        $bar->finish();
        $this->newLine(2);
        $this->info(sprintf(
            'Done: %d processed, %d field-translations written, %d skipped, %d errors, %d API calls.',
            $stats['processed'],
            $stats['translated'],
            $stats['skipped'],
            $stats['errors'],
            $stats['api_calls']
        ));

        return self::SUCCESS;
    }

    /**
     * Send one batch of fields to Claude and parse the JSON response.
     *
     * @param  array<string, string>  $payload  fields keyed by name
     * @return array<string, string>            same keys, translated values
     */
    private function translateBatch(array $payload, string $sourceLocale, string $targetLocale, string $kind): array
    {
        $localeNames = [
            'fr' => 'French',
            'en' => 'English',
            'es' => 'Spanish',
            'ar' => 'Arabic',
        ];
        $sourceName = $localeNames[$sourceLocale] ?? $sourceLocale;
        $targetName = $localeNames[$targetLocale] ?? $targetLocale;

        $jsonIn = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        $system = "You translate $kind content from $sourceName to $targetName. "
            . "You receive a JSON object whose values are strings. "
            . "Translate EACH value, preserving the same JSON keys, formatting (line breaks, bullet points), and HTML tags if present. "
            . "Do NOT translate proper nouns, company names, brand names, technologies, or job-specific acronyms. "
            . "Reply with ONLY the translated JSON object, no preamble, no markdown fences, no commentary.";

        $response = Http::timeout(120)
            ->retry(2, 1500, throw: false)
            ->withHeaders([
                'x-api-key' => env('ANTHROPIC_API_KEY'),
                'anthropic-version' => '2023-06-01',
                'content-type' => 'application/json',
            ])
            ->post('https://api.anthropic.com/v1/messages', [
                'model' => $this->option('model-name'),
                'max_tokens' => 4096,
                'system' => $system,
                'messages' => [
                    ['role' => 'user', 'content' => $jsonIn],
                ],
            ]);

        if (! $response->successful()) {
            throw new \RuntimeException('Anthropic API HTTP ' . $response->status() . ': ' . $response->body());
        }

        $body = $response->json();
        $text = $body['content'][0]['text'] ?? null;

        if (! is_string($text) || $text === '') {
            throw new \RuntimeException('Empty response from Anthropic API');
        }

        // Strip possible markdown fences just in case the model adds them.
        $text = trim($text);
        $text = preg_replace('/^```(?:json)?\s*/i', '', $text);
        $text = preg_replace('/\s*```$/', '', $text);
        $text = trim($text);

        $decoded = json_decode($text, true);
        if (! is_array($decoded)) {
            throw new \RuntimeException('Invalid JSON returned by model: ' . substr($text, 0, 200));
        }

        return $decoded;
    }
}
