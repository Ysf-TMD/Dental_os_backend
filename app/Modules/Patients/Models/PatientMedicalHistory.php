<?php

namespace App\Modules\Patients\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatientMedicalHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'diabetes',
        'hypertension',
        'asthma',
        'epilepsy',
        'heart_disease',
        'hepatitis',
        'hiv',
        'pregnancy',
        'cancer',
        'bleeding_disorder',
        'other_conditions',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'diabetes' => 'boolean',
        'hypertension' => 'boolean',
        'asthma' => 'boolean',
        'epilepsy' => 'boolean',
        'heart_disease' => 'boolean',
        'hepatitis' => 'boolean',
        'hiv' => 'boolean',
        'pregnancy' => 'boolean',
        'cancer' => 'boolean',
        'bleeding_disorder' => 'boolean',
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
