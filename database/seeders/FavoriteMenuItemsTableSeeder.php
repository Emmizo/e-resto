<?php

namespace Database\Seeders;

use App\Models\FavoriteMenuItem;
use App\Models\MenuItem;
use App\Models\User;
use Illuminate\Database\Seeder;

class FavoriteMenuItemsTableSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing favorites
        FavoriteMenuItem::query()->delete();

        $users = User::all();
        $menuItems = MenuItem::all();

        // For demo: each user likes the first 2 menu items
        foreach ($users as $user) {
            foreach ($menuItems->take(2) as $menuItem) {
                FavoriteMenuItem::create([
                    'user_id' => $user->id,
                    'menu_item_id' => $menuItem->id,
                    'status' => true,  // liked by default
                ]);
            }
        }
    }
}
