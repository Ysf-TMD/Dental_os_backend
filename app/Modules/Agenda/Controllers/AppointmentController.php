<?php

namespace App\Modules\Agenda\Controllers;

use App\Modules\Agenda\Models\Appointment;
use App\Modules\Agenda\Resources\AppointmentResource;
use App\Modules\Shared\Controllers\BaseCrudController;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class AppointmentController extends BaseCrudController
{
    protected string $model = Appointment::class;

    protected string $resource = AppointmentResource::class;

    protected array $searchable = ['reason', 'practitioner'];

    protected array $filterable = ['status', 'practitioner', 'patient_id'];

    protected function rules(bool $updating = false): array
    {
        return [
            'patient_id' => ['required', 'integer', 'exists:patients,id'],
            'practitioner' => ['required', 'string', 'max:100'],
            'date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'reason' => ['required', 'string', 'max:255'],
            'status' => ['sometimes', 'in:confirmé,en attente,terminé,annulé'],
            'notes' => ['nullable', 'string'],
        ];
    }

    protected function applyFilters(Builder $query, Request $request): void
    {
        // Eager load to prevent N+1 queries
        $query->with('patient:id,first_name,last_name,code');

        if ($request->filled('from')) {
            $query->whereDate('date', '>=', $request->query('from'));
        }
        if ($request->filled('to')) {
            $query->whereDate('date', '<=', $request->query('to'));
        }
    }
}
