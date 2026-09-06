<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lab_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->string('type');
            $table->string('lab_name');
            $table->date('sent_at');
            $table->date('due_at');
            $table->enum('status', ['en cours', 'livré', 'retard'])->default('en cours');
            $table->timestamps();

            $table->index(['patient_id', 'status']);
            $table->index('due_at'); // late order detection
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lab_orders');
    }
};
