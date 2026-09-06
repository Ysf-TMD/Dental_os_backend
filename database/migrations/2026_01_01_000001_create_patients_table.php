<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('phone', 30);
            $table->string('email')->nullable();
            $table->enum('gender', ['M', 'F']);
            $table->date('birth_date');
            $table->string('cin', 20)->nullable();
            $table->string('city', 100)->nullable();
            $table->date('last_visit')->nullable();
            $table->enum('status', ['actif', 'nouveau', 'inactif'])->default('nouveau');
            $table->decimal('balance', 10, 2)->default(0);
            $table->timestamps();

            // Optimized indexes for frequent queries
            $table->index(['last_name', 'first_name']); // name search
            $table->index('phone');
            $table->index('status');   // status filters
            $table->index('city');     // city filters
            $table->index('last_visit');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
