<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            // Rename balance to cached_balance
            $table->renameColumn('balance', 'cached_balance');
            
            // Add timestamp for when balance was last updated
            $table->timestamp('balance_updated_at')->nullable()->after('cached_balance');
        });
    }

    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn('balance_updated_at');
            $table->renameColumn('cached_balance', 'balance');
        });
    }
};
