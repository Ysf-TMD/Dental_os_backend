<?php

namespace App\Modules\Devis\Controllers;

use App\Modules\Devis\Models\Quote;
use App\Modules\Devis\Resources\QuoteResource;
use App\Modules\Shared\Controllers\BaseCrudController;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class QuoteController extends BaseCrudController
{
    protected string $model = Quote::class;

    protected string $resource = QuoteResource::class;

    protected array $searchable = ['number', 'title'];

    protected array $filterable = ['status', 'patient_id'];

    protected function rules(bool $updating = false): array
    {
        return [
            'patient_id' => ['required', 'integer', 'exists:patients,id'],
            'title' => ['required', 'string', 'max:255'],
            'total' => ['required', 'numeric', 'min:0'],
            'status' => ['sometimes', 'in:brouillon,envoyé,accepté,refusé'],
            'items' => ['nullable', 'array'],
        ];
    }

    protected function applyFilters(Builder $query, Request $request): void
    {
        $query->with('patient:id,first_name,last_name,code');
    }
}
