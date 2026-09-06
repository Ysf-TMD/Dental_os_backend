<?php

namespace App\Modules\Patients\Controllers;

use App\Modules\Patients\Models\Patient;
use App\Modules\Patients\Models\PatientEmergencyContact;
use App\Modules\Patients\Models\PatientMedicalHistory;
use App\Modules\Patients\Models\PatientAllergy;
use App\Modules\Patients\Models\PatientMedication;
use App\Modules\Patients\Models\PatientHabit;
use App\Modules\Patients\Models\PatientInsurance;
use App\Modules\Patients\Models\PatientConsent;
use App\Modules\Patients\Models\PatientDocument;
use App\Modules\Patients\Models\PatientNote;
use App\Modules\Facturation\Models\Invoice;
use App\Modules\Paiements\Models\Payment;
use App\Modules\Finance\Models\PaymentPlan;
use App\Modules\Patients\Resources\PatientResource;
use App\Modules\Patients\Resources\PatientDetailResource;
use App\Modules\Shared\Controllers\BaseCrudController;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PatientController extends BaseCrudController
{
    protected string $model = Patient::class;

    protected string $resource = PatientResource::class;

    protected string $detailResource = PatientDetailResource::class;

    protected array $searchable = ['first_name', 'last_name', 'code', 'phone', 'email'];

    protected array $filterable = ['status', 'city', 'gender'];

    protected array $sortable = ['id', 'created_at', 'updated_at', 'first_name', 'last_name', 'code', 'phone', 'email', 'city', 'last_visit', 'balance', 'status'];

    protected array $defaultSort = ['column' => 'created_at', 'direction' => 'desc'];

    protected function applyFilters(Builder $query, Request $request): void
    {
        // Filter by balance (with unpaid)
        if ($request->query('with_balance') === 'true') {
            $query->where('balance', '>', 0);
        }

        // Filter by active status
        if ($request->query('active_only') === 'true') {
            $query->where('status', 'actif');
        }

        // Filter by new status
        if ($request->query('new_only') === 'true') {
            $query->where('status', 'nouveau');
        }

        // Filter by inactive status
        if ($request->query('inactive_only') === 'true') {
            $query->where('status', 'inactif');
        }
    }

    protected function rules(bool $updating = false): array
    {
        return [
            'title' => ['nullable', 'in:M.,Mme,Mlle,Enfant'],
            'first_name' => ['required', 'string', 'min:2', 'max:100'],
            'last_name' => ['required', 'string', 'min:2', 'max:100'],
            'first_name_ar' => ['nullable', 'string', 'max:100'],
            'last_name_ar' => ['nullable', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:30'],
            'phone_secondary' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'gender' => ['required', 'in:M,F'],
            'birth_date' => ['required', 'date'],
            'birth_place' => ['nullable', 'string', 'max:100'],
            'nationality' => ['nullable', 'string', 'max:50'],
            'cin' => ['nullable', 'string', 'max:20'],
            'passport' => ['nullable', 'string', 'max:50'],
            'photo' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'city' => ['nullable', 'string', 'max:100'],
            'region' => ['nullable', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'blood_group' => ['nullable', 'in:A+,A-,B+,B-,O+,O-,AB+,AB-'],
            'referring_dentist' => ['nullable', 'string', 'max:100'],
            'first_visit_date' => ['nullable', 'date'],
            'consultation_reason' => ['nullable', 'string'],
            'oral_health_status' => ['nullable', 'string'],
            'status' => ['sometimes', 'in:actif,nouveau,inactif'],
            'balance' => ['sometimes', 'numeric', 'min:0'],
            'credit_limit' => ['sometimes', 'numeric', 'min:0'],
            'discount_rate' => ['sometimes', 'numeric', 'min:0', 'max:100'],
            'preferred_payment_method' => ['nullable', 'in:Cash,Card,Transfer,Check'],
            // Nested data
            'emergency_contacts' => ['array'],
            'emergency_contacts.*.name' => ['required_with:emergency_contacts', 'string', 'max:100'],
            'emergency_contacts.*.relationship' => ['required_with:emergency_contacts', 'string', 'max:50'],
            'emergency_contacts.*.phone' => ['required_with:emergency_contacts', 'string', 'max:30'],
            'emergency_contacts.*.address' => ['nullable', 'string'],
            'medical_history' => ['array'],
            'medical_history.diabetes' => ['boolean'],
            'medical_history.hypertension' => ['boolean'],
            'medical_history.asthma' => ['boolean'],
            'medical_history.epilepsy' => ['boolean'],
            'medical_history.heart_disease' => ['boolean'],
            'medical_history.hepatitis' => ['boolean'],
            'medical_history.hiv' => ['boolean'],
            'medical_history.pregnancy' => ['boolean'],
            'medical_history.cancer' => ['boolean'],
            'medical_history.bleeding_disorder' => ['boolean'],
            'medical_history.other_conditions' => ['nullable', 'string'],
            'allergies' => ['array'],
            'allergies.penicillin' => ['boolean'],
            'allergies.latex' => ['boolean'],
            'allergies.anesthetics' => ['boolean'],
            'allergies.iodine' => ['boolean'],
            'allergies.other_allergies' => ['nullable', 'string'],
            'medications' => ['array'],
            'medications.*.name' => ['required_with:medications', 'string', 'max:200'],
            'medications.*.dose' => ['required_with:medications', 'string', 'max:100'],
            'medications.*.frequency' => ['required_with:medications', 'string', 'max:100'],
            'habits' => ['array'],
            'habits.smoker' => ['boolean'],
            'habits.hookah' => ['boolean'],
            'habits.alcohol' => ['boolean'],
            'habits.drugs' => ['boolean'],
            'habits.notes' => ['nullable', 'string'],
            'insurances' => ['array'],
            'insurances.*.type' => ['required_with:insurances', 'string', 'max:50'],
            'insurances.*.company' => ['required_with:insurances', 'string', 'max:100'],
            'insurances.*.policy_number' => ['required_with:insurances', 'string', 'max:100'],
            'insurances.*.expiration_date' => ['nullable', 'date'],
            'consents' => ['array'],
            'consents.terms_of_use' => ['boolean'],
            'consents.data_processing' => ['boolean'],
            'consents.sms_notifications' => ['boolean'],
            'consents.email_notifications' => ['boolean'],
            'consents.whatsapp_notifications' => ['boolean'],
            'consents.cndp_consent' => ['boolean'],
            'notes' => ['array'],
            'notes.*.content' => ['required_with:notes', 'string'],
            'notes.*.is_private' => ['boolean'],
        ];
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());

        return DB::transaction(function () use ($validated, $request) {
            $userId = auth()->id();

            // Create patient
            $patient = Patient::create([
                ...$validated,
                'created_by' => $userId,
                'updated_by' => $userId,
            ]);

            // Create emergency contacts
            if (!empty($validated['emergency_contacts'])) {
                foreach ($validated['emergency_contacts'] as $contact) {
                    $patient->emergencyContacts()->create([
                        ...$contact,
                        'created_by' => $userId,
                        'updated_by' => $userId,
                    ]);
                }
            }

            // Create medical history
            if (!empty($validated['medical_history'])) {
                $patient->medicalHistory()->create([
                    ...$validated['medical_history'],
                    'created_by' => $userId,
                    'updated_by' => $userId,
                ]);
            }

            // Create allergies
            if (!empty($validated['allergies'])) {
                $patient->allergies()->create([
                    ...$validated['allergies'],
                    'created_by' => $userId,
                    'updated_by' => $userId,
                ]);
            }

            // Create medications
            if (!empty($validated['medications'])) {
                foreach ($validated['medications'] as $medication) {
                    $patient->medications()->create([
                        ...$medication,
                        'created_by' => $userId,
                        'updated_by' => $userId,
                    ]);
                }
            }

            // Create habits
            if (!empty($validated['habits'])) {
                $patient->habits()->create([
                    ...$validated['habits'],
                    'created_by' => $userId,
                    'updated_by' => $userId,
                ]);
            }

            // Create insurances
            if (!empty($validated['insurances'])) {
                foreach ($validated['insurances'] as $insurance) {
                    $patient->insurances()->create([
                        ...$insurance,
                        'created_by' => $userId,
                        'updated_by' => $userId,
                    ]);
                }
            }

            // Create consents
            if (!empty($validated['consents'])) {
                $patient->consents()->create([
                    ...$validated['consents'],
                    'created_by' => $userId,
                    'updated_by' => $userId,
                ]);
            }

            // Create notes
            if (!empty($validated['notes'])) {
                foreach ($validated['notes'] as $note) {
                    $patient->notes()->create([
                        ...$note,
                        'created_by' => $userId,
                        'updated_by' => $userId,
                    ]);
                }
            }

            return new PatientDetailResource($patient->load([
                'emergencyContacts',
                'medicalHistory',
                'allergies',
                'medications',
                'habits',
                'insurances',
                'consents',
                'documents',
                'notes',
            ]));
        });
    }

    public function show($id)
    {
        $patient = Patient::with([
            'emergencyContacts',
            'medicalHistory',
            'allergies',
            'medications',
            'habits',
            'insurances',
            'consents',
            'documents',
            'notes',
            'createdBy',
            'updatedBy',
        ])->findOrFail($id);

        return new PatientDetailResource($patient);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate($this->rules(true));

        return DB::transaction(function () use ($validated, $request, $id) {
            $userId = auth()->id();
            $patient = Patient::findOrFail($id);

            // Update patient
            $patient->update([
                ...$validated,
                'updated_by' => $userId,
            ]);

            // Update emergency contacts
            if (isset($validated['emergency_contacts'])) {
                $patient->emergencyContacts()->delete();
                foreach ($validated['emergency_contacts'] as $contact) {
                    $patient->emergencyContacts()->create([
                        ...$contact,
                        'created_by' => $userId,
                        'updated_by' => $userId,
                    ]);
                }
            }

            // Update medical history
            if (isset($validated['medical_history'])) {
                $patient->medicalHistory()->delete();
                $patient->medicalHistory()->create([
                    ...$validated['medical_history'],
                    'created_by' => $userId,
                    'updated_by' => $userId,
                ]);
            }

            // Update allergies
            if (isset($validated['allergies'])) {
                $patient->allergies()->delete();
                $patient->allergies()->create([
                    ...$validated['allergies'],
                    'created_by' => $userId,
                    'updated_by' => $userId,
                ]);
            }

            // Update medications
            if (isset($validated['medications'])) {
                $patient->medications()->delete();
                foreach ($validated['medications'] as $medication) {
                    $patient->medications()->create([
                        ...$medication,
                        'created_by' => $userId,
                        'updated_by' => $userId,
                    ]);
                }
            }

            // Update habits
            if (isset($validated['habits'])) {
                $patient->habits()->delete();
                $patient->habits()->create([
                    ...$validated['habits'],
                    'created_by' => $userId,
                    'updated_by' => $userId,
                ]);
            }

            // Update insurances
            if (isset($validated['insurances'])) {
                $patient->insurances()->delete();
                foreach ($validated['insurances'] as $insurance) {
                    $patient->insurances()->create([
                        ...$insurance,
                        'created_by' => $userId,
                        'updated_by' => $userId,
                    ]);
                }
            }

            // Update consents
            if (isset($validated['consents'])) {
                $patient->consents()->delete();
                $patient->consents()->create([
                    ...$validated['consents'],
                    'created_by' => $userId,
                    'updated_by' => $userId,
                ]);
            }

            // Update notes
            if (isset($validated['notes'])) {
                $patient->notes()->delete();
                foreach ($validated['notes'] as $note) {
                    $patient->notes()->create([
                        ...$note,
                        'created_by' => $userId,
                        'updated_by' => $userId,
                    ]);
                }
            }

            return new PatientDetailResource($patient->load([
                'emergencyContacts',
                'medicalHistory',
                'allergies',
                'medications',
                'habits',
                'insurances',
                'consents',
                'documents',
                'notes',
            ]));
        });
    }

    public function financialSummary($id)
    {
        $patient = Patient::findOrFail($id);
        
        \Log::info('Calculating financial summary for patient', ['patient_id' => $id]);
        
        // Check if patient has treatments
        $treatmentsCount = $patient->treatments()->count();
        \Log::info('Treatments count', ['patient_id' => $id, 'count' => $treatmentsCount]);
        
        // Check if patient has payments
        $paymentsCount = $patient->payments()->count();
        \Log::info('Payments count', ['patient_id' => $id, 'count' => $paymentsCount]);
        
        // Check if patient has invoices
        $invoicesCount = $patient->invoices()->count();
        \Log::info('Invoices count', ['patient_id' => $id, 'count' => $invoicesCount]);
        
        // Calculate based on invoices instead of treatments
        $totalInvoices = $patient->invoices()->sum('total');
        $totalPaid = $patient->invoices()->sum('paid') + $patient->payments()->sum('amount');
        $balance = $totalInvoices - $totalPaid;
        $lastPayment = $patient->payments()->latest()->first();
        
        \Log::info('Financial summary calculated', [
            'patient_id' => $id,
            'total_invoices' => $totalInvoices,
            'total_paid' => $totalPaid,
            'balance' => $balance,
            'last_payment' => $lastPayment?->paid_at,
            'last_payment_amount' => $lastPayment?->amount,
        ]);
        
        return response()->json([
            'total_treatments' => $totalInvoices, // Use invoices total instead of treatments
            'total_paid' => $totalPaid,
            'balance' => $balance,
            'last_payment' => $lastPayment?->paid_at,
            'last_payment_amount' => $lastPayment?->amount,
        ]);
    }

    public function financialTransactions($id, Request $request)
    {
        $patient = Patient::findOrFail($id);
        
        \Log::info('Fetching financial transactions for patient', ['patient_id' => $id, 'request_params' => $request->all()]);
        
        $invoicesQuery = $patient->invoices();
        $paymentsQuery = $patient->payments();
        
        \Log::info('Invoices query', ['sql' => $invoicesQuery->toSql(), 'bindings' => $invoicesQuery->getBindings()]);
        \Log::info('Payments query', ['sql' => $paymentsQuery->toSql(), 'bindings' => $paymentsQuery->getBindings()]);
        
        // Apply date filters
        if ($request->has('start_date')) {
            $startDate = $request->input('start_date');
            $invoicesQuery->whereDate('created_at', '>=', $startDate);
            $paymentsQuery->whereDate('created_at', '>=', $startDate);
            \Log::info('Applied start_date filter', ['start_date' => $startDate]);
        }
        
        if ($request->has('end_date')) {
            $endDate = $request->input('end_date');
            $invoicesQuery->whereDate('created_at', '<=', $endDate);
            $paymentsQuery->whereDate('created_at', '<=', $endDate);
            \Log::info('Applied end_date filter', ['end_date' => $endDate]);
        }
        
        // Apply type filter
        if ($request->has('type') && $request->input('type') !== '') {
            $type = $request->input('type');
            if ($type === 'invoice') {
                $paymentsQuery->whereRaw('1 = 0'); // Exclude payments
                \Log::info('Applied type filter: invoice only');
            } elseif ($type === 'payment') {
                $invoicesQuery->whereRaw('1 = 0'); // Exclude invoices
                \Log::info('Applied type filter: payment only');
            }
        }
        
        $invoices = $invoicesQuery->get();
        \Log::info('Fetched invoices', ['count' => $invoices->count(), 'invoices' => $invoices->toArray()]);
        
        $payments = $paymentsQuery->get();
        \Log::info('Fetched payments', ['count' => $payments->count(), 'payments' => $payments->toArray()]);
        
        $invoicesMapped = $invoices->map(function ($invoice) {
            return [
                'id' => $invoice->id,
                'type' => 'invoice',
                'amount' => (float) $invoice->total,
                'reference' => $invoice->number,
                'created_at' => $invoice->created_at ? $invoice->created_at->toISOString() : now()->toISOString(),
                'status' => $invoice->status ?? 'en attente',
            ];
        });

        $paymentsMapped = $payments->map(function ($payment) {
            return [
                'id' => $payment->id,
                'type' => 'payment',
                'amount' => (float) $payment->amount,
                'reference' => $payment->reference,
                'created_at' => $payment->created_at ? $payment->created_at->toISOString() : now()->toISOString(),
                'status' => $payment->status ?? 'validé',
            ];
        });

        $transactions = $invoicesMapped->concat($paymentsMapped)->sortByDesc('created_at')->values();
        
        \Log::info('Final transactions result', ['total_count' => $transactions->count(), 'transactions' => $transactions->toArray()]);

        return response()->json($transactions);
    }

    public function paymentPlans($id)
    {
        $patient = Patient::findOrFail($id);
        
        $plans = $patient->paymentPlans()->get()->map(function ($plan) {
            return [
                'id' => $plan->id,
                'total_amount' => $plan->total_amount,
                'down_payment' => $plan->down_payment,
                'installment_amount' => $plan->installment_amount,
                'number_of_installments' => $plan->number_of_installments,
                'frequency' => $plan->frequency,
                'start_date' => $plan->start_date,
                'status' => $plan->status,
                'notes' => $plan->notes,
            ];
        });

        return response()->json($plans);
    }
}
