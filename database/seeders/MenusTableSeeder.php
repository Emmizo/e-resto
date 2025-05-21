<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Restaurant;
use Illuminate\Database\Seeder;

class MenusTableSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing menus
        Menu::query()->delete();

        // Get the first restaurant (created in UsersTableSeeder)
        $restaurant = Restaurant::first();

        if ($restaurant) {
            $menuTypes = [
                [
                    'name' => 'Breakfast',
                    'description' => 'Start your day with our delicious breakfast options.',
                    'is_active' => true,
                ],
                [
                    'name' => 'Lunch',
                    'description' => 'Perfect meals for your midday break.',
                    'is_active' => true,
                ],
                [
                    'name' => 'Dinner',
                    'description' => 'Enjoy our evening specialties and fine dining options.',
                    'is_active' => true,
                ],
                [
                    'name' => 'Snack',
                    'description' => 'Light bites and refreshments for any time of day.',
                    'is_active' => true,
                ],
            ];

            foreach ($menuTypes as $menuType) {
                Menu::create([
                    'restaurant_id' => $restaurant->id,
                    'name' => $menuType['name'],
                    'description' => $menuType['description'],
                    'is_active' => $menuType['is_active'],
                ]);
            }
        }
    }
}
