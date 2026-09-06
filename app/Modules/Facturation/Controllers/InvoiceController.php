<?php

namespace App\Modules\Facturation\Controllers;

use App\Modules\Facturation\Models\Invoice;
use App\Modules\Facturation\Resources\InvoiceResource;
use App\Modules\Shared\Controllers\BaseCrudController;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class InvoiceController extends BaseCrudController
{
    protected string $model = Invoice::class;

    protected string $resource = InvoiceResource::class;

    protected array $searchable = ['number'];

    protected array $filterable = ['status', 'patient_id'];

    protected function rules(bool $updating = false): array
    {
        return [
            'patient_id' => ['required', 'integer', 'exists:patients,id'],
            'date' => ['required', 'date'],
            'due_date' => ['required', 'date', 'after_or_equal:date'],
            'total' => ['required', 'numeric', 'min:0'],
            'paid' => ['sometimes', 'numeric', 'min:0'],
            'status' => ['sometimes', 'in:payée,partielle,en attente,en retard'],
        ];
    }

    protected function applyFilters(Builder $query, Request $request): void
    {
        $query->with('patient:id,first_name,last_name,code');
    }
}
