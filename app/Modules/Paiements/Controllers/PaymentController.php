<?php

namespace App\Modules\Paiements\Controllers;

use App\Modules\Paiements\Models\Payment;
use App\Modules\Paiements\Resources\PaymentResource;
use App\Modules\Shared\Controllers\BaseCrudController;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class PaymentController extends BaseCrudController
{
    protected string $model = Payment::class;

    protected string $resource = PaymentResource::class;

    protected array $searchable = ['reference'];

    protected array $filterable = ['method', 'invoice_id'];

    protected function rules(bool $updating = false): array
    {
        return [
            'invoice_id' => ['required', 'integer', 'exists:invoices,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'method' => ['required', 'in:espèces,carte,virement,chèque'],
            'paid_at' => ['required', 'date'],
            'reference' => ['nullable', 'string', 'max:100'],
        ];
    }

    protected function applyFilters(Builder $query, Request $request): void
    {
        $query->with('invoice:id,number,patient_id', 'invoice.patient:id,first_name,last_name');
    }
}
