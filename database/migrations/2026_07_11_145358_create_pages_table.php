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
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('display_name');
            $table->string('path')->unique();
            $table->string('component')->nullable();
            $table->string('icon')->nullable();
            $table->string('group_name')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index('path');
            $table->index('name');
            $table->index('is_active');
            $table->index('group_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
