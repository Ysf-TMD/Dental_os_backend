<?php

namespace App\Modules\Permission\Controllers;

use App\Modules\Permission\Models\Permission;
use App\Modules\Permission\Resources\PermissionResource;
use App\Modules\Shared\Controllers\BaseCrudController;

class PermissionController extends BaseCrudController
{
    protected string $model = Permission::class;

    protected string $resource = PermissionResource::class;

    protected array $searchable = ['name', 'display_name', 'description'];

    protected array $filterable = ['action', 'group_name'];

    protected function rules(bool $updating = false): array
    {
        $rules = [
            'display_name' => ['required', 'string', 'min:2', 'max:100'],
            'action' => ['sometimes', 'in:view,create,update,delete'],
            'page_id' => ['nullable', 'exists:pages,id'],
            'group_name' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:500'],
        ];

        if ($updating) {
            $rules['name'] = ['sometimes', 'string', 'max:100', 'unique:permissions,name,' . request()->route('permission')];
        } else {
            $rules['name'] = ['required', 'string', 'max:100', 'unique:permissions,name'];
        }

        return $rules;
    }

    protected function applyFilters($query, $request): void
    {
        if ($request->filled('action')) {
            $query->byAction($request->query('action'));
        }

        if ($request->filled('group_name')) {
            $query->byGroup($request->query('group_name'));
        }
    }
}
