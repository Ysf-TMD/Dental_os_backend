<?php

namespace App\Modules\Traitements\Controllers;

use App\Modules\Shared\Controllers\BaseCrudController;
use App\Modules\Traitements\Models\Treatment;
use App\Modules\Traitements\Resources\TreatmentResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class TreatmentController extends BaseCrudController
{
    protected string $model = Treatment::class;

    protected string $resource = TreatmentResource::class;

    protected array $searchable = ['name', 'tooth'];

    protected array $filterable = ['status', 'patient_id'];

    protected function rules(bool $updating = false): array
    {
        return [
            'patient_id' => ['required', 'integer', 'exists:patients,id'],
            'name' => ['required', 'string', 'max:255'],
            'tooth' => ['nullable', 'string', 'max:20'],
            'price' => ['required', 'numeric', 'min:0'],
            'status' => ['sometimes', 'in:planifié,en cours,terminé'],
            'notes' => ['nullable', 'string'],
        ];
    }

    protected function applyFilters(Builder $query, Request $request): void
    {
        $query->with('patient:id,first_name,last_name');
    }
}
