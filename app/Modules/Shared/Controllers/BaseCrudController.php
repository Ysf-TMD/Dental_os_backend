<?php

namespace App\Modules\Shared\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

/**
 * Generic CRUD controller shared by all modules.
 * Modules only define the model, resource, searchable columns and validation rules.
 * Queries are optimized: indexed search columns, pagination, selective filtering.
 */
abstract class BaseCrudController extends Controller
{
    /** @var class-string<\Illuminate\Database\Eloquent\Model> */
    protected string $model;

    /** @var class-string<\Illuminate\Http\Resources\Json\JsonResource> */
    protected string $resource;

    /** Columns usable with the ?search= query param (must be indexed). */
    protected array $searchable = [];

    /** Columns usable as direct equality filters, e.g. ?status=actif */
    protected array $filterable = [];

    /** Columns usable for sorting, e.g. ?sort_by=name&sort_order=asc */
    protected array $sortable = ['id', 'created_at', 'updated_at'];

    /** Default sort column and order */
    protected array $defaultSort = ['column' => 'id', 'direction' => 'desc'];

    public function index(Request $request)
    {
        $query = $this->model::query();

        // Search
        if ($search = $request->query('search')) {
            $query->where(function (Builder $q) use ($search) {
                foreach ($this->searchable as $column) {
                    $q->orWhere($column, 'like', "%{$search}%");
                }
            });
        }

        // Direct filters
        foreach ($this->filterable as $column) {
            if ($request->filled($column)) {
                $query->where($column, $request->query($column));
            }
        }

        // Sorting
        $sortBy = $request->query('sort_by', $this->defaultSort['column']);
        $sortOrder = $request->query('sort_order', $this->defaultSort['direction']);

        if (in_array($sortBy, $this->sortable)) {
            $query->orderBy($sortBy, in_array(strtolower($sortOrder), ['asc', 'desc']) ? $sortOrder : 'desc');
        } else {
            $query->orderBy($this->defaultSort['column'], $this->defaultSort['direction']);
        }

        // Custom filters
        $this->applyFilters($query, $request);

        // Pagination
        $perPage = $request->query('per_page', 15);
        $page = $request->query('page', 1);

        if ($perPage === 'all') {
            $data = $query->get();
            return response()->json([
                'data' => $this->resource::collection($data),
                'meta' => [
                    'current_page' => 1,
                    'last_page' => 1,
                    'per_page' => count($data),
                    'total' => count($data),
                ],
            ]);
        }

        $paginator = $query->paginate(min((int) $perPage, 100), ['*'], 'page', $page);

        return response()->json([
            'data' => $this->resource::collection($paginator->items()),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());

        $item = $this->model::create($this->prepareData($data));

        return (new $this->resource($item))->response()->setStatusCode(201);
    }

    public function show(int|string $id)
    {
        return new $this->resource($this->model::findOrFail($id));
    }

    public function update(Request $request, int|string $id)
    {
        $item = $this->model::findOrFail($id);

        $data = $request->validate($this->rules(updating: true));

        $item->update($this->prepareData($data));

        return new $this->resource($item->refresh());
    }

    public function destroy(int|string $id)
    {
        $this->model::findOrFail($id)->delete();

        return response()->noContent();
    }

    /** Validation rules for store/update. */
    abstract protected function rules(bool $updating = false): array;

    /** Hook for module-specific query filters. */
    protected function applyFilters(Builder $query, Request $request): void
    {
    }

    /** Hook to transform validated data before persisting. */
    protected function prepareData(array $data): array
    {
        return $data;
    }
}
