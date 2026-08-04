<?php

namespace Database\Seeders;

use App\Models\TenantPayments;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TenantsPaymentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TenantPayments::create([
            'tenant_id'                       => 1,
            'tenant_billings_rent_id'         => 1,
            'tenant_billings_electricity_id'  => null,
            'tenant_billings_water_id'        => null,
            'file_electricity'                => null,
            'file_water'                      => null,
            'electricity_amount'              => null,
            'water_amount'                    => null,
            'billing_month'                   => 'June',
            'amount'                          => 8000.50,
            'type'                            => 'ECASH',
            'get_fullname'                    => null,
            'payment_type'                    => 'Rent',
            'payment_proof'                   => null,
            'status'                          => 'Accepted',
            'received_by'                     => 1,
        ]);

        TenantPayments::create([
            'tenant_id'                       => 1,
            'tenant_billings_rent_id'         => null,
            'tenant_billings_electricity_id'  => 1,
            'tenant_billings_water_id'        => null,
            'file_electricity'                => null,
            'file_water'                      => null,
            'electricity_amount'              => 1450.00,
            'water_amount'                    => null,
            'billing_month'                   => 'June',
            'amount'                          => 1450.00,
            'type'                            => 'CASH',
            'get_fullname'                    => 'Admin Admin',
            'payment_type'                    => 'Electricity',
            'payment_proof'                   => null,
            'status'                          => 'Accepted',
            'received_by'                     => 1,
        ]);

        TenantPayments::create([
            'tenant_id'                       => 1,
            'tenant_billings_rent_id'         => null,
            'tenant_billings_electricity_id'  => null,
            'tenant_billings_water_id'        => 1,
            'file_electricity'                => null,
            'file_water'                      => null,
            'electricity_amount'              => null,
            'water_amount'                    => 450.00,
            'billing_month'                   => 'June',
            'amount'                          => 450.00,
            'type'                            => 'CASH',
            'get_fullname'                    => 'Admin Admin',
            'payment_type'                    => 'Water',
            'payment_proof'                   => null,
            'status'                          => 'Accepted',
            'received_by'                     => 1,
        ]);
    }
}
