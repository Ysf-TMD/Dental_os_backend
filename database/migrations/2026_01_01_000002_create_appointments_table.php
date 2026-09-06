<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->string('practitioner', 100);
            $table->date('date');
            $table->string('start_time', 5);
            $table->string('end_time', 5);
            $table->string('reason');
            $table->enum('status', ['confirmé', 'en attente', 'terminé', 'annulé'])->default('en attente');
            $table->text('notes')->nullable();
            $table->timestamps();

            // Composite index: most agenda queries filter by patient + date or date range
            $table->index(['patient_id', 'date']);
            $table->index(['date', 'start_time']); // weekly calendar view
            $table->index('status');
            $table->index('practitioner');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
