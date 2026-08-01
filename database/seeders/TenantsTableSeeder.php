<?php

namespace Database\Seeders;

use App\Models\Tenants;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class TenantsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Tenants::create([
            'fullname' => 'Russel Vincent ',
            'password' => Hash::make('123456789'),
            'phone_number' => '09495748302',
            'location_id' => 1,
        ]);
    }
}
