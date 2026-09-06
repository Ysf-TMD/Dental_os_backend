<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->foreignId('patient_id')->nullable()->constrained()->nullOnDelete()->after('id');
            $table->enum('status', ['pending', 'completed', 'cancelled', 'refunded'])->default('completed')->after('method');
            $table->text('notes')->nullable()->after('reference');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->after('notes');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['patient_id']);
            $table->dropForeign(['created_by']);
            $table->dropColumn(['patient_id', 'status', 'notes', 'created_by']);
        });
    }
};
