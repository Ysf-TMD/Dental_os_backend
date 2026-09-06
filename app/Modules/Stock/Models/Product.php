<?php

namespace App\Modules\Stock\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'sku',
        'name',
        'category',
        'stock',
        'min_stock',
        'unit',
        'expires_at',
        'price',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'date:Y-m-d',
            'price' => 'decimal:2',
            'stock' => 'integer',
            'min_stock' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Product $product) {
            if (empty($product->sku)) {
                $last = static::query()->max('id') ?? 0;
                $product->sku = 'SKU-'.str_pad((string) ($last + 1 + 2000), 4, '0', STR_PAD_LEFT);
            }
        });
    }
}
