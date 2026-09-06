<?php

namespace App\Modules\Patients\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatientAllergy extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'penicillin',
        'latex',
        'anesthetics',
        'iodine',
        'other_allergies',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'penicillin' => 'boolean',
        'latex' => 'boolean',
        'anesthetics' => 'boolean',
        'iodine' => 'boolean',
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
