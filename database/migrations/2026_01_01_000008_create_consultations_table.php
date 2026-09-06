<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consultations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->string('practitioner', 100);
            $table->string('motif');
            $table->text('notes')->nullable();
            $table->date('date');
            $table->enum('status', ['clôturée', 'à suivre', 'en cours'])->default('en cours');
            $table->timestamps();

            $table->index(['patient_id', 'date']);
            $table->index('status');
            $table->index('practitioner');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consultations');
    }
};
