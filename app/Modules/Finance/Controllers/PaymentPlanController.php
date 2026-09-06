<?php

namespace App\Modules\Finance\Controllers;

use App\Modules\Finance\Models\PaymentPlan;
use App\Modules\Finance\Resources\PaymentPlanResource;
use App\Modules\Shared\Controllers\BaseCrudController;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class PaymentPlanController extends BaseCrudController
{
    protected string $model = PaymentPlan::class;

    protected string $resource = PaymentPlanResource::class;

    protected array $searchable = ['notes'];

    protected array $filterable = ['status', 'frequency'];

    protected function rules(bool $updating = false): array
    {
        return [
            'patient_id' => ['required', 'integer', 'exists:patients,id'],
            'total_amount' => ['required', 'numeric', 'min:0.01'],
            'down_payment' => ['nullable', 'numeric', 'min:0'],
            'installment_amount' => ['required', 'numeric', 'min:0.01'],
            'number_of_installments' => ['required', 'integer', 'min:1'],
            'frequency' => ['required', 'in:weekly,bi-weekly,monthly,quarterly'],
            'start_date' => ['required', 'date'],
            'status' => ['sometimes', 'in:active,completed,cancelled'],
            'notes' => ['nullable', 'string'],
        ];
    }

    protected function applyFilters(Builder $query, Request $request): void
    {
        $query->with('patient:id,first_name,last_name', 'createdBy:id,name');
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());
        $userId = auth()->id();

        $paymentPlan = PaymentPlan::create([
            ...$validated,
            'created_by' => $userId,
            'updated_by' => $userId,
        ]);

        return new PaymentPlanResource($paymentPlan->load('patient', 'createdBy'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate($this->rules(true));
        $userId = auth()->id();
        $paymentPlan = PaymentPlan::findOrFail($id);

        $paymentPlan->update([
            ...$validated,
            'updated_by' => $userId,
        ]);

        return new PaymentPlanResource($paymentPlan->load('patient', 'createdBy'));
    }
}
