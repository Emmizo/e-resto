<?php

namespace Database\Seeders;

use App\Models\Cuisine;
use Illuminate\Database\Seeder;

class CuisineSeeder extends Seeder
{
    public function run()
    {
        $cuisines = [
            'Italian', 'Mexican', 'Chinese', 'Indian', 'French', 'Japanese', 'Thai', 'American', 'Mediterranean', 'Greek', 'Spanish', 'Vietnamese', 'Korean', 'Turkish', 'Lebanese'
        ];
        foreach ($cuisines as $cuisine) {
            Cuisine::firstOrCreate(['name' => $cuisine]);
        }
    }
}
