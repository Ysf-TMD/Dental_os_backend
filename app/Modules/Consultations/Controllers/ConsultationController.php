<?php

namespace App\Modules\Consultations\Controllers;

use App\Modules\Consultations\Models\Consultation;
use App\Modules\Consultations\Resources\ConsultationResource;
use App\Modules\Shared\Controllers\BaseCrudController;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ConsultationController extends BaseCrudController
{
    protected string $model = Consultation::class;

    protected string $resource = ConsultationResource::class;

    protected array $searchable = ['motif', 'practitioner'];

    protected array $filterable = ['status', 'patient_id', 'practitioner'];

    protected function rules(bool $updating = false): array
    {
        return [
            'patient_id' => ['required', 'integer', 'exists:patients,id'],
            'practitioner' => ['required', 'string', 'max:100'],
            'motif' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'date' => ['required', 'date'],
            'status' => ['sometimes', 'in:clôturée,à suivre,en cours'],
        ];
    }

    protected function applyFilters(Builder $query, Request $request): void
    {
        $query->with('patient:id,first_name,last_name,code');
    }
}
