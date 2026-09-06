<?php

namespace App\Modules\Patients\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'title',
        'first_name',
        'last_name',
        'first_name_ar',
        'last_name_ar',
        'phone',
        'phone_secondary',
        'email',
        'gender',
        'birth_date',
        'birth_place',
        'nationality',
        'cin',
        'passport',
        'photo',
        'address',
        'city',
        'region',
        'postal_code',
        'blood_group',
        'referring_dentist',
        'first_visit_date',
        'consultation_reason',
        'oral_health_status',
        'last_visit',
        'status',
        'cached_balance',
        'balance_updated_at',
        'credit_limit',
        'discount_rate',
        'preferred_payment_method',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date:Y-m-d',
            'last_visit' => 'date:Y-m-d',
            'first_visit_date' => 'date:Y-m-d',
            'cached_balance' => 'decimal:2',
            'balance_updated_at' => 'datetime',
            'credit_limit' => 'decimal:2',
            'discount_rate' => 'decimal:2',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Patient $patient) {
            if (empty($patient->code)) {
                $last = static::query()->max('id') ?? 0;
                $patient->code = 'DOS-'.str_pad((string) ($last + 1 + 2400), 5, '0', STR_PAD_LEFT);
            }
        });
    }

    public function emergencyContacts(): HasMany
    {
        return $this->hasMany(PatientEmergencyContact::class);
    }

    public function medicalHistory(): HasMany
    {
        return $this->hasMany(PatientMedicalHistory::class);
    }

    public function allergies(): HasMany
    {
        return $this->hasMany(PatientAllergy::class);
    }

    public function medications(): HasMany
    {
        return $this->hasMany(PatientMedication::class);
    }

    public function habits(): HasMany
    {
        return $this->hasMany(PatientHabit::class);
    }

    public function insurances(): HasMany
    {
        return $this->hasMany(PatientInsurance::class);
    }

    public function consents(): HasMany
    {
        return $this->hasMany(PatientConsent::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(PatientDocument::class);
    }

    public function notes(): HasMany
    {
        return $this->hasMany(PatientNote::class);
    }

    public function financialTransactions(): HasMany
    {
        return $this->hasMany(\App\Modules\Finance\Models\FinancialTransaction::class);
    }

    public function paymentPlans(): HasMany
    {
        return $this->hasMany(\App\Modules\Finance\Models\PaymentPlan::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(\App\Modules\Facturation\Models\Invoice::class, 'patient_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(\App\Modules\Paiements\Models\Payment::class, 'patient_id');
    }

    public function treatments(): HasMany
    {
        return $this->hasMany(\App\Modules\Traitements\Models\Treatment::class);
    }

    public function treatmentPlans(): HasMany
    {
        return $this->hasMany(\App\Modules\Finance\Models\TreatmentPlan::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'updated_by');
    }

    public function getBalanceAttribute(): float
    {
        // Calculate real-time balance from financial transactions
        return $this->financialTransactions()
            ->selectRaw('SUM(CASE WHEN type = \'invoice\' THEN amount WHEN type IN (\'payment\', \'refund\') THEN -amount ELSE 0 END) as balance')
            ->value('balance') ?? 0;
    }

    public function updateCachedBalance(): void
    {
        $this->cached_balance = $this->balance;
        $this->balance_updated_at = now();
        $this->saveQuietly();
    }
}
