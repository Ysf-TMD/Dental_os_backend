<?php

namespace App\Modules\Page\Controllers;

use App\Modules\Page\Models\Page;
use App\Modules\Page\Resources\PageResource;
use App\Modules\Shared\Controllers\BaseCrudController;
use Illuminate\Http\Request;

class PageController extends BaseCrudController
{
    protected string $model = Page::class;

    protected string $resource = PageResource::class;

    protected array $searchable = ['name', 'display_name', 'path'];

    protected array $filterable = ['is_active', 'group_name'];

    protected function rules(bool $updating = false): array
    {
        $rules = [
            'display_name' => ['required', 'string', 'min:2', 'max:100'],
            'path' => ['required', 'string', 'max:255'],
            'component' => ['nullable', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:100'],
            'group_name' => ['nullable', 'string', 'max:100'],
            'is_active' => ['sometimes', 'boolean'],
        ];

        if ($updating) {
            $rules['name'] = ['sometimes', 'string', 'max:100', 'unique:pages,name,' . request()->route('page')];
            $rules['path'] = ['sometimes', 'string', 'max:255', 'unique:pages,path,' . request()->route('page')];
        } else {
            $rules['name'] = ['required', 'string', 'max:100', 'unique:pages,name'];
            $rules['path'] = ['required', 'string', 'max:255', 'unique:pages,path'];
        }

        return $rules;
    }

    protected function applyFilters($query, $request): void
    {
        if ($request->filled('group_name')) {
            $query->where('group_name', $request->query('group_name'));
        }

        if ($request->filled('ids')) {
            $ids = explode(',', $request->query('ids'));
            $query->whereIn('id', $ids);
        }
    }

    public function syncPermissions(Request $request, $pageId)
    {
        $request->validate([
            'permission_ids' => 'required|array',
            'permission_ids.*' => 'exists:permissions,id',
        ]);

        $page = Page::findOrFail($pageId);
        $page->permissions()->sync($request->permission_ids);

        return response()->json([
            'message' => 'Permissions synchronisées avec succès',
            'page' => new PageResource($page->load('permissions'))
        ]);
    }

    public function autoDetect(Request $request)
    {
        // Cette méthode peut être étendue pour scanner automatiquement les pages frontend
        // Pour l'instant, elle retourne les pages existantes
        $pages = Page::all();
        
        return response()->json([
            'message' => 'Pages détectées avec succès',
            'pages' => PageResource::collection($pages)
        ]);
    }
}
