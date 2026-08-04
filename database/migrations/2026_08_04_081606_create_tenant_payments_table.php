<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tenant_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->foreignId('tenant_billings_rent_id')->nullable()->constrained('tenant_billings_rents')->onDelete('set null');
            $table->foreignId('tenant_billings_electricity_id')->nullable()->constrained('tenant_billings_electricities')->onDelete('set null');
            $table->foreignId('tenant_billings_water_id')->nullable()->constrained('tenant_billings_waters')->onDelete('set null');
            $table->string('file_electricity')->nullable();
            $table->string('file_water')->nullable();
            $table->decimal('electricity_amount', 10, 2)->nullable();
            $table->decimal('water_amount', 10, 2)->nullable();
            $table->string('billing_month')->nullable();
            $table->decimal('amount', 10, 2)->default(0);
            $table->string('type')->default('CASH'); // ECASH / CASH
            $table->string('get_fullname')->nullable();
            $table->string('payment_type')->default('Rent'); // Rent, Electricity, Water
            $table->string('payment_proof')->nullable();
            $table->string('status')->default('Pending'); // Accepted / Declined / Pending
            $table->foreignId('received_by')->nullable()->constrained('admins')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenant_payments');
    }
};
