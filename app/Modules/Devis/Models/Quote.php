<?php

namespace App\Modules\Devis\Models;

use App\Modules\Patients\Models\Patient;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Quote extends Model
{
    protected $fillable = [
        'number',
        'patient_id',
        'title',
        'total',
        'status',
        'items',
    ];

    protected function casts(): array
    {
        return [
            'total' => 'decimal:2',
            'items' => 'array',
        ];
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    protected static function booted(): void
    {
        static::creating(function (Quote $quote) {
            if (empty($quote->number)) {
                $last = static::query()->max('id') ?? 0;
                $quote->number = 'DV-'.now()->year.'-'.str_pad((string) ($last + 1 + 420), 4, '0', STR_PAD_LEFT);
            }
        });
    }
}
