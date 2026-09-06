<?php

namespace App\Modules\Traitements\Models;

use App\Modules\Patients\Models\Patient;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Treatment extends Model
{
    protected $fillable = [
        'patient_id',
        'catalog_id',
        'treatment_plan_id',
        'name',
        'tooth',
        'price',
        'price_at_moment',
        'discount',
        'tax',
        'total',
        'status',
        'notes',
        'dentist_id',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'price_at_moment' => 'decimal:2',
            'discount' => 'decimal:2',
            'tax' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function catalog(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Finance\Models\TreatmentCatalog::class);
    }

    public function treatmentPlan(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Finance\Models\TreatmentPlan::class);
    }

    public function dentist(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'dentist_id');
    }
}
