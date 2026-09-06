<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('sku', 20)->unique();
            $table->string('name');
            $table->string('category', 100);
            $table->unsignedInteger('stock')->default(0);
            $table->unsignedInteger('min_stock')->default(10);
            $table->string('unit', 20)->default('u');
            $table->date('expires_at')->nullable();
            $table->decimal('price', 10, 2);
            $table->timestamps();

            $table->index('name');
            $table->index('category');
            $table->index('expires_at'); // expiry alerts
            $table->index(['stock', 'min_stock']); // low-stock alerts
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
