<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('treatments', function (Blueprint $table) {
            $table->foreignId('catalog_id')->nullable()->constrained('treatment_catalogs')->nullOnDelete()->after('id');
            $table->foreignId('treatment_plan_id')->nullable()->constrained('treatment_plans')->nullOnDelete()->after('catalog_id');
            $table->decimal('price_at_moment', 10, 2)->nullable()->after('price');
            $table->decimal('discount', 10, 2)->default(0)->after('price_at_moment');
            $table->decimal('tax', 10, 2)->default(0)->after('discount');
            $table->decimal('total', 10, 2)->nullable()->after('tax');
            $table->foreignId('dentist_id')->nullable()->constrained('users')->nullOnDelete()->after('notes');
        });
    }

    public function down(): void
    {
        Schema::table('treatments', function (Blueprint $table) {
            $table->dropForeign(['catalog_id']);
            $table->dropForeign(['treatment_plan_id']);
            $table->dropForeign(['dentist_id']);
            $table->dropColumn(['catalog_id', 'treatment_plan_id', 'price_at_moment', 'discount', 'tax', 'total', 'dentist_id']);
        });
    }
};
