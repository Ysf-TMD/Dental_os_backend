<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            // Identity fields
            $table->enum('title', ['M.', 'Mme', 'Mlle', 'Enfant'])->nullable()->after('id');
            $table->string('first_name_ar', 100)->nullable()->after('last_name');
            $table->string('last_name_ar', 100)->nullable()->after('first_name_ar');
            $table->string('birth_place', 100)->nullable()->after('birth_date');
            $table->string('nationality', 50)->default('Marocain')->after('birth_place');
            $table->string('passport', 50)->nullable()->after('cin');
            $table->string('photo', 255)->nullable()->after('passport');

            // Contact fields
            $table->string('phone_secondary', 30)->nullable()->after('phone');
            $table->text('address')->nullable()->after('city');
            $table->string('region', 100)->nullable()->after('address');
            $table->string('postal_code', 20)->nullable()->after('region');

            // Medical fields
            $table->enum('blood_group', ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'])->nullable()->after('postal_code');

            // Dental fields
            $table->string('referring_dentist', 100)->nullable()->after('blood_group');
            $table->date('first_visit_date')->nullable()->after('referring_dentist');
            $table->text('consultation_reason')->nullable()->after('first_visit_date');
            $table->text('oral_health_status')->nullable()->after('consultation_reason');

            // Financial fields
            $table->decimal('credit_limit', 10, 2)->default(0)->after('balance');
            $table->decimal('discount_rate', 5, 2)->default(0)->after('credit_limit');
            $table->enum('preferred_payment_method', ['Cash', 'Card', 'Transfer', 'Check'])->nullable()->after('discount_rate');

            // System fields
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->after('updated_at');
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete()->after('created_by');
            $table->softDeletes()->after('updated_by');
        });
    }

    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn([
                'title',
                'first_name_ar',
                'last_name_ar',
                'birth_place',
                'nationality',
                'passport',
                'photo',
                'phone_secondary',
                'address',
                'region',
                'postal_code',
                'blood_group',
                'referring_dentist',
                'first_visit_date',
                'consultation_reason',
                'oral_health_status',
                'credit_limit',
                'discount_rate',
                'preferred_payment_method',
                'created_by',
                'updated_by',
            ]);
            $table->dropSoftDeletes();
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
        });
    }
};
