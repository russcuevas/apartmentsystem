<?php

namespace Database\Seeders;

use App\Models\TenantBillingsRent;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TenantsBillingRentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TenantBillingsRent::create([
            'tenant_id'        => 1,
            'billing_month'    => 'June',
            'due_date'         => '2026-06-15',
            'rent_amount'      => 8000.50,
            'balance'          => 0.00,
            'proof_of_billing' => null,
            'status'           => 'Paid',
        ]);

        TenantBillingsRent::create([
            'tenant_id'        => 1,
            'billing_month'    => 'July',
            'due_date'         => '2026-07-15',
            'rent_amount'      => 8000.50,
            'balance'          => 0.00,
            'proof_of_billing' => null,
            'status'           => 'Paid',
        ]);

        TenantBillingsRent::create([
            'tenant_id'        => 1,
            'billing_month'    => 'August',
            'due_date'         => '2026-08-15',
            'rent_amount'      => 8000.50,
            'balance'          => 8000.50,
            'proof_of_billing' => null,
            'status'           => 'Unpaid',
        ]);

        TenantBillingsRent::create([
            'tenant_id'        => 1,
            'billing_month'    => 'September',
            'due_date'         => '2026-09-15',
            'rent_amount'      => 8000.50,
            'balance'          => 8000.50,
            'proof_of_billing' => null,
            'status'           => 'Unpaid',
        ]);
    }
}
