<?php

namespace App\Modules\Employes\Controllers;

use App\Modules\Employes\Models\Employee;
use App\Modules\Employes\Resources\EmployeeResource;
use App\Modules\Shared\Controllers\BaseCrudController;

class EmployeeController extends BaseCrudController
{
    protected string $model = Employee::class;

    protected string $resource = EmployeeResource::class;

    protected array $searchable = ['name', 'email', 'role'];

    protected array $filterable = ['is_active'];

    protected function rules(bool $updating = false): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'role' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'color' => ['nullable', 'string', 'max:50'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
