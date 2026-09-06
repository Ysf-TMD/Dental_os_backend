<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patient_medical_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->boolean('diabetes')->default(false);
            $table->boolean('hypertension')->default(false);
            $table->boolean('asthma')->default(false);
            $table->boolean('epilepsy')->default(false);
            $table->boolean('heart_disease')->default(false);
            $table->boolean('hepatitis')->default(false);
            $table->boolean('hiv')->default(false);
            $table->boolean('pregnancy')->default(false);
            $table->boolean('cancer')->default(false);
            $table->boolean('bleeding_disorder')->default(false);
            $table->text('other_conditions')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('patient_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patient_medical_histories');
    }
};
