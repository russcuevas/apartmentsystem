<?php

namespace Database\Seeders;

use App\Models\Locations;
use Illuminate\Database\Seeder;

class LocationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locations = [
            'Tala',
            'Silang',
            'Balai',
            'Bulacan',
            'Maligaya',
            'Parola',
            'Bistek',
        ];

        foreach ($locations as $location) {
            Locations::create([
                'location_name' => $location,
            ]);
        }
    }
}
