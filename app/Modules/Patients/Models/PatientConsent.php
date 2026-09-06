<?php

namespace App\Modules\Patients\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatientConsent extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'terms_of_use',
        'data_processing',
        'sms_notifications',
        'email_notifications',
        'whatsapp_notifications',
        'cndp_consent',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'terms_of_use' => 'boolean',
        'data_processing' => 'boolean',
        'sms_notifications' => 'boolean',
        'email_notifications' => 'boolean',
        'whatsapp_notifications' => 'boolean',
        'cndp_consent' => 'boolean',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'updated_by');
    }
}
