<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

/**
 * Demande de prestation de service, soumise depuis l'app par n'importe quel
 * profil (historiquement « demande de digitalisation » de l'espace entreprise).
 */
class DigitalizationRequest extends Model
{
    use HasFactory, SoftDeletes;

    /** Catégories proposées dans l'app (menu « Que voulez-vous faire ? »). */
    public const CATEGORIES = [
        'purchase' => 'Achat d\'article',
        'company_service' => 'Prestation entreprise',
        'professional_service' => 'Prestation d\'un professionnel',
        'find_company' => 'Rechercher une entreprise',
    ];

    /** Canaux par lesquels le demandeur accepte d'être contacté. */
    public const CHANNELS = [
        'estuaire' => 'Estuaire Emploi',
        'whatsapp' => 'WhatsApp',
        'sms' => 'SMS',
        'call' => 'Appels',
    ];

    /** Canaux qui exigent un numéro de téléphone. */
    public const PHONE_CHANNELS = ['whatsapp', 'sms', 'call'];

    protected $fillable = [
        'user_id',
        'category',
        'project_name',
        'company_name',
        'city',
        'district',
        'contact',
        'channels',
        'description',
        'attachment_path',
        'attachment_name',
        'status',
        'admin_notes',
        'processed_at',
        'notified_at',
        'responses_count',
    ];

    protected $hidden = ['attachment_path'];

    protected $appends = ['attachment_url', 'category_label'];

    protected function casts(): array
    {
        return [
            'channels' => 'array',
            'processed_at' => 'datetime',
            'notified_at' => 'datetime',
            'responses_count' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getAttachmentUrlAttribute(): ?string
    {
        return $this->attachment_path
            ? Storage::disk('public')->url($this->attachment_path)
            : null;
    }

    /** Snapshot épinglé sur les messages de réponse (metadata.service_request). */
    public function toChatSnapshot(): array
    {
        $isImage = $this->attachment_path
            && preg_match('/\.(jpe?g|png|webp)$/i', $this->attachment_path);

        return [
            'id' => $this->id,
            'title' => $this->project_name,
            'category' => $this->category,
            'category_label' => $this->category_label,
            'city' => $this->city,
            'district' => $this->district,
            'owner_id' => $this->user_id,
            'image' => $isImage ? $this->attachment_url : null,
        ];
    }

    public function getCategoryLabelAttribute(): string
    {
        return self::CATEGORIES[$this->category] ?? 'Prestation';
    }
}
