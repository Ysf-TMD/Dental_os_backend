<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('financial_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['invoice', 'payment', 'refund', 'adjustment']);
            $table->decimal('amount', 10, 2);
            $table->string('reference')->nullable();
            $table->text('description')->nullable();
            $table->foreignId('related_id')->nullable()->comment('ID of related invoice, payment, etc.');
            $table->string('related_type')->nullable()->comment('Model type of related record');
            $table->timestamps();

            $table->index(['patient_id', 'type']);
            $table->index(['related_id', 'related_type']);
            $table->index('type');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('financial_transactions');
    }
};
