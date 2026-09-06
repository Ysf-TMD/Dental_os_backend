<?php

namespace App\Modules\Laboratoire\Models;

use App\Modules\Patients\Models\Patient;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LabOrder extends Model
{
    protected $fillable = [
        'patient_id',
        'type',
        'lab_name',
        'sent_at',
        'due_at',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'sent_at' => 'date:Y-m-d',
            'due_at' => 'date:Y-m-d',
        ];
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
}
