<?php

namespace Database\Seeders;

use App\Models\TenantBillingsWater;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TenantsBillingWaterTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TenantBillingsWater::create([
            'tenant_id'        => 1,
            'billing_month'    => 'June',
            'due_date'         => '2026-06-25',
            'rent_amount'      => 450.00,
            'balance'          => 0.00,
            'proof_of_billing' => null,
            'status'           => 'Paid',
        ]);

        TenantBillingsWater::create([
            'tenant_id'        => 1,
            'billing_month'    => 'July',
            'due_date'         => '2026-07-25',
            'rent_amount'      => 480.00,
            'balance'          => 0.00,
            'proof_of_billing' => null,
            'status'           => 'Paid',
        ]);

        TenantBillingsWater::create([
            'tenant_id'        => 1,
            'billing_month'    => 'August',
            'due_date'         => '2026-08-25',
            'rent_amount'      => 500.00,
            'balance'          => 500.00,
            'proof_of_billing' => null,
            'status'           => 'Unpaid',
        ]);

        TenantBillingsWater::create([
            'tenant_id'        => 1,
            'billing_month'    => 'September',
            'due_date'         => '2026-09-25',
            'rent_amount'      => 520.00,
            'balance'          => 520.00,
            'proof_of_billing' => null,
            'status'           => 'Unpaid',
        ]);
    }
}
