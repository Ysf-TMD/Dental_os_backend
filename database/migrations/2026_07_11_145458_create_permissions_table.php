<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('display_name');
            $table->string('action')->default('view');
            $table->foreignId('page_id')->nullable()->constrained()->onDelete('set null');
            $table->string('group_name')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->index('name');
            $table->index('action');
            $table->index('group_name');
            $table->index('page_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
