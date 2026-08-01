<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admins;
use Illuminate\Support\Facades\Hash;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admins::create([
            'fullname'     => 'System Administrator',
            'email'        => 'administrator@gmail.com',
            'phone_number' => '09123456789',
            'password'     => Hash::make('123456789'),
        ]);
    }
}
