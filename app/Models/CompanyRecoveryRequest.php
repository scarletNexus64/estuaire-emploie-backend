<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyRecoveryRequest extends Model
{
    protected $guarded = ['id'];
    protected $casts = ['reviewed_at' => 'datetime'];

    public const LABELS = ['pending' => 'En attente', 'approved' => 'Approuvée', 'rejected' => 'Refusée'];

    public function company() { return $this->belongsTo(Company::class); }
    public function requester() { return $this->belongsTo(User::class, 'requester_user_id'); }
    public function resultUser() { return $this->belongsTo(User::class, 'result_user_id'); }
    public function reviewer() { return $this->belongsTo(User::class, 'reviewed_by'); }
    public function deliveries() { return $this->hasMany(CompanyRecoveryDelivery::class, 'request_id'); }

    public function customerData(): array
    {
        return [
            'id' => $this->id, 'mode' => $this->mode, 'status' => $this->status,
            'company_id' => $this->company_id, 'company_name' => $this->company?->name,
            'city' => $this->company?->city, 'name' => $this->name,
            'phone' => $this->phone, 'position' => $this->position,
            'explanation' => $this->explanation, 'admin_reply' => $this->admin_reply,
            'created_at' => $this->created_at?->toIso8601String(),
            'reviewed_at' => $this->reviewed_at?->toIso8601String(),
        ];
    }
}
