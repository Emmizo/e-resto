<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class MenuItemsTableSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing menu items
        MenuItem::query()->delete();

        // Sample menu items for Breakfast
        $breakfastMenu = Menu::where('name', 'Breakfast')->first();
        if ($breakfastMenu) {
            $this->createMenuItems($breakfastMenu->id, [
                [
                    'name' => 'Classic Pancakes',
                    'description' => 'Fluffy pancakes served with maple syrup and butter',
                    'price' => 8.99,
                    'category' => 'Food',
                    'dietary_info' => '{"contains":["gluten","dairy","eggs"]}',
                    'is_available' => true,
                ],
                [
                    'name' => 'Eggs Benedict',
                    'description' => 'Poached eggs on English muffin with hollandaise sauce',
                    'price' => 12.99,
                    'category' => 'Food',
                    'dietary_info' => '{"contains":["eggs","dairy","gluten"]}',
                    'is_available' => true,
                ],
                [
                    'name' => 'Fresh Fruit Bowl',
                    'description' => 'Seasonal fresh fruits with honey and mint',
                    'price' => 6.99,
                    'category' => 'Food',
                    'dietary_info' => '{"suitable_for":["vegan","gluten-free"]}',
                    'is_available' => true,
                ],
                [
                    'name' => 'Coffee',
                    'description' => 'Freshly brewed premium coffee',
                    'price' => 3.99,
                    'category' => 'Beverage',
                    'dietary_info' => '{"suitable_for":["vegan"]}',
                    'is_available' => true,
                ],
            ]);
        }

        // Sample menu items for Lunch
        $lunchMenu = Menu::where('name', 'Lunch')->first();
        if ($lunchMenu) {
            $this->createMenuItems($lunchMenu->id, [
                [
                    'name' => 'Grilled Chicken Sandwich',
                    'description' => 'Grilled chicken breast with lettuce, tomato, and mayo on sourdough',
                    'price' => 12.99,
                    'category' => 'Food',
                    'dietary_info' => '{"contains":["gluten","poultry"]}',
                    'is_available' => true,
                ],
                [
                    'name' => 'Caesar Salad',
                    'description' => 'Crisp romaine lettuce, parmesan cheese, croutons, and Caesar dressing',
                    'price' => 10.99,
                    'category' => 'Food',
                    'dietary_info' => '{"contains":["dairy","eggs","gluten"]}',
                    'is_available' => true,
                ],
                [
                    'name' => 'Vegetable Soup',
                    'description' => 'Homemade soup with seasonal vegetables',
                    'price' => 7.99,
                    'category' => 'Food',
                    'dietary_info' => '{"suitable_for":["vegetarian","vegan"]}',
                    'is_available' => true,
                ],
                [
                    'name' => 'Iced Tea',
                    'description' => 'Fresh brewed iced tea with lemon',
                    'price' => 3.99,
                    'category' => 'Beverage',
                    'dietary_info' => '{"suitable_for":["vegan"]}',
                    'is_available' => true,
                ],
            ]);
        }

        // Sample menu items for Dinner
        $dinnerMenu = Menu::where('name', 'Dinner')->first();
        if ($dinnerMenu) {
            $this->createMenuItems($dinnerMenu->id, [
                [
                    'name' => 'Grilled Salmon',
                    'description' => 'Fresh salmon fillet with lemon butter sauce',
                    'price' => 24.99,
                    'category' => 'Food',
                    'dietary_info' => '{"contains":["fish","dairy"]}',
                    'is_available' => true,
                ],
                [
                    'name' => 'Beef Tenderloin',
                    'description' => 'Prime beef tenderloin with red wine reduction',
                    'price' => 29.99,
                    'category' => 'Food',
                    'dietary_info' => '{"contains":["beef"]}',
                    'is_available' => true,
                ],
                [
                    'name' => 'Vegetable Risotto',
                    'description' => 'Creamy arborio rice with seasonal vegetables',
                    'price' => 18.99,
                    'category' => 'Food',
                    'dietary_info' => '{"suitable_for":["vegetarian"],"contains":["dairy"]}',
                    'is_available' => true,
                ],
                [
                    'name' => 'House Wine',
                    'description' => 'Selected red or white wine',
                    'price' => 8.99,
                    'category' => 'Beverage',
                    'dietary_info' => '{"contains":["alcohol"]}',
                    'is_available' => true,
                ],
            ]);
        }

        // Sample menu items for Snacks
        $snackMenu = Menu::where('name', 'Snack')->first();
        if ($snackMenu) {
            $this->createMenuItems($snackMenu->id, [
                [
                    'name' => 'French Fries',
                    'description' => 'Crispy golden fries with sea salt',
                    'price' => 5.99,
                    'category' => 'Food',
                    'dietary_info' => '{"suitable_for":["vegetarian","vegan"]}',
                    'is_available' => true,
                ],
                [
                    'name' => 'Chicken Wings',
                    'description' => 'Crispy wings with choice of sauce',
                    'price' => 11.99,
                    'category' => 'Food',
                    'dietary_info' => '{"contains":["poultry"]}',
                    'is_available' => true,
                ],
                [
                    'name' => 'Nachos',
                    'description' => 'Tortilla chips with cheese, jalapeños, and salsa',
                    'price' => 9.99,
                    'category' => 'Food',
                    'dietary_info' => '{"contains":["dairy"]}',
                    'is_available' => true,
                ],
                [
                    'name' => 'Soft Drinks',
                    'description' => 'Assorted carbonated beverages',
                    'price' => 2.99,
                    'category' => 'Beverage',
                    'dietary_info' => '{"suitable_for":["vegan"]}',
                    'is_available' => true,
                ],
            ]);
        }
    }

    private function createMenuItems($menuId, $items)
    {
        foreach ($items as $item) {
            MenuItem::create([
                'menu_id' => $menuId,
                'name' => $item['name'],
                'description' => $item['description'],
                'price' => $item['price'],
                'category' => $item['category'],
                'dietary_info' => $item['dietary_info'],
                'is_available' => $item['is_available'],
            ]);
        }
    }
}
