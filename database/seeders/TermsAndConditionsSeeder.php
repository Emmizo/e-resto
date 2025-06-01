<?php

namespace Database\Seeders;

use App\Models\TermsAndConditions;
use Illuminate\Database\Seeder;

class TermsAndConditionsSeeder extends Seeder
{
    public function run()
    {
        TermsAndConditions::create([
            'content' => 'Welcome to our platform. By registering, you agree to abide by our terms and conditions. Please read them carefully before proceeding.',
            'is_active' => true,
        ]);
    }
}
