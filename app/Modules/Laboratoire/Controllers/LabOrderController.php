<?php

namespace App\Modules\Laboratoire\Controllers;

use App\Modules\Laboratoire\Models\LabOrder;
use App\Modules\Laboratoire\Resources\LabOrderResource;
use App\Modules\Shared\Controllers\BaseCrudController;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class LabOrderController extends BaseCrudController
{
    protected string $model = LabOrder::class;

    protected string $resource = LabOrderResource::class;

    protected array $searchable = ['type', 'lab_name'];

    protected array $filterable = ['status', 'patient_id'];

    protected function rules(bool $updating = false): array
    {
        return [
            'patient_id' => ['required', 'integer', 'exists:patients,id'],
            'type' => ['required', 'string', 'max:255'],
            'lab_name' => ['required', 'string', 'max:255'],
            'sent_at' => ['required', 'date'],
            'due_at' => ['required', 'date', 'after_or_equal:sent_at'],
            'status' => ['sometimes', 'in:en cours,livré,retard'],
        ];
    }

    protected function applyFilters(Builder $query, Request $request): void
    {
        $query->with('patient:id,first_name,last_name');
    }
}
