<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tables = [
            'tenant_billings_rents',
            'tenant_billings_electricities',
            'tenant_billings_waters',
            'tenant_payments',
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    if (!Schema::hasColumn($tableName, 'billing_year')) {
                        $table->integer('billing_year')->nullable()->after('billing_month');
                    }
                });

                // Backfill existing records: if billing_year is NULL, set billing_year = YEAR(created_at)
                DB::table($tableName)
                    ->whereNull('billing_year')
                    ->update([
                        'billing_year' => DB::raw('YEAR(created_at)')
                    ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'tenant_billings_rents',
            'tenant_billings_electricities',
            'tenant_billings_waters',
            'tenant_payments',
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'billing_year')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropColumn('billing_year');
                });
            }
        }
    }
};
