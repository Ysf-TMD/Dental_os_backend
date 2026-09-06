<?php

namespace App\Modules\Facturation\Models;

use App\Modules\Paiements\Models\Payment;
use App\Modules\Patients\Models\Patient;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    protected $fillable = [
        'number',
        'patient_id',
        'treatment_plan_id',
        'date',
        'due_date',
        'total',
        'paid',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date:Y-m-d',
            'due_date' => 'date:Y-m-d',
            'total' => 'decimal:2',
            'paid' => 'decimal:2',
        ];
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function treatmentPlan(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Finance\Models\TreatmentPlan::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(\App\Modules\Finance\Models\InvoiceItem::class);
    }

    public function paymentAllocations(): HasMany
    {
        return $this->hasMany(\App\Modules\Finance\Models\PaymentAllocation::class);
    }

    public function getRemainingBalanceAttribute(): float
    {
        return $this->total - $this->paid;
    }

    protected static function booted(): void
    {
        static::creating(function (Invoice $invoice) {
            if (empty($invoice->number)) {
                $last = static::query()->max('id') ?? 0;
                $invoice->number = 'FA-'.now()->year.'-'.str_pad((string) ($last + 1), 4, '0', STR_PAD_LEFT);
            }
        });
    }
}
