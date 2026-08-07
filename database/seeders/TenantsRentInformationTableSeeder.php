<?php

namespace Database\Seeders;

use App\Models\TenantsRentInformation;
use Illuminate\Database\Seeder;

class TenantsRentInformationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TenantsRentInformation::create([
            'tenant_id' => 1,
            'room' => 'Room 101',
            'monthly_rental' => 8000.50,
            'start_date' => '2026-01-01',
            'move_out' => false,
        ]);
    }
}
