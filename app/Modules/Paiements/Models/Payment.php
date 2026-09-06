<?php

namespace App\Modules\Paiements\Models;

use App\Modules\Facturation\Models\Invoice;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payment extends Model
{
    protected $fillable = [
        'invoice_id',
        'patient_id',
        'amount',
        'method',
        'paid_at',
        'reference',
        'status',
        'notes',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'paid_at' => 'date:Y-m-d',
        ];
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Patients\Models\Patient::class);
    }

    public function allocations(): HasMany
    {
        return $this->hasMany(\App\Modules\Finance\Models\PaymentAllocation::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function getUnallocatedAmountAttribute(): float
    {
        $allocated = $this->allocations()->sum('amount_allocated');
        return $this->amount - $allocated;
    }
}
