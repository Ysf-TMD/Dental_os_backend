<?php

namespace App\Modules\Stock\Controllers;

use App\Modules\Shared\Controllers\BaseCrudController;
use App\Modules\Stock\Models\Product;
use App\Modules\Stock\Resources\ProductResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ProductController extends BaseCrudController
{
    protected string $model = Product::class;

    protected string $resource = ProductResource::class;

    protected array $searchable = ['name', 'sku', 'category'];

    protected array $filterable = ['category'];

    protected function rules(bool $updating = false): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100'],
            'stock' => ['required', 'integer', 'min:0'],
            'min_stock' => ['sometimes', 'integer', 'min:0'],
            'unit' => ['required', 'string', 'max:20'],
            'expires_at' => ['nullable', 'date'],
            'price' => ['required', 'numeric', 'min:0'],
        ];
    }

    protected function applyFilters(Builder $query, Request $request): void
    {
        if ($request->boolean('low_stock')) {
            $query->whereColumn('stock', '<', 'min_stock');
        }
        if ($request->boolean('expiring_soon')) {
            $query->whereNotNull('expires_at')
                ->whereDate('expires_at', '<=', now()->addDays(60));
        }
    }
}
