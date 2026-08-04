<?php

namespace Database\Seeders;

use App\Models\TenantBillingsElectricity;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TenantsBillingElectrictyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TenantBillingsElectricity::create([
            'tenant_id'        => 1,
            'billing_month'    => 'June',
            'due_date'         => '2026-06-20',
            'rent_amount'      => 1450.00,
            'balance'          => 0.00,
            'proof_of_billing' => null,
            'status'           => 'Paid',
        ]);

        TenantBillingsElectricity::create([
            'tenant_id'        => 1,
            'billing_month'    => 'July',
            'due_date'         => '2026-07-20',
            'rent_amount'      => 1520.00,
            'balance'          => 0.00,
            'proof_of_billing' => null,
            'status'           => 'Paid',
        ]);

        TenantBillingsElectricity::create([
            'tenant_id'        => 1,
            'billing_month'    => 'August',
            'due_date'         => '2026-08-20',
            'rent_amount'      => 1680.00,
            'balance'          => 1680.00,
            'proof_of_billing' => null,
            'status'           => 'Unpaid',
        ]);

        TenantBillingsElectricity::create([
            'tenant_id'        => 1,
            'billing_month'    => 'September',
            'due_date'         => '2026-09-20',
            'rent_amount'      => 1600.00,
            'balance'          => 1600.00,
            'proof_of_billing' => null,
            'status'           => 'Unpaid',
        ]);
    }
}
