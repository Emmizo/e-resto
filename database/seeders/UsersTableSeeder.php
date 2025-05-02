<?php

namespace Database\Seeders;

use App\Models\Restaurant;
use App\Models\RestaurantPermission;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::query()->delete();

        $user = User::create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@gmail.com',
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('12345678'),  // Change this to a secure password
            'role' => 'admin',
            'phone_number' => '1234567890',
            'profile_picture' => null,
            'preferences' => json_encode(['theme' => 'dark']),
            'status' => 1,
            'remember_token' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        $permissions = [
            'User_Management',
            'Menu_Management',
            'Restaurant_Operations',
            'Order_and_Reservation',
            'Reservation_Management',
            'Payment_Processing',
            'Order_Management',
            'Financial_Reporting',
            'Role_Management',
            'Edit_user_management',
            'Edit_menu_management',
            'Active_User_Management',
        ];
        $firstCuisineId = \App\Models\Cuisine::query()->value('id');
        // Create a demo restaurant for the admin
        $restaurant = Restaurant::create([
            'name' => 'Admin Demo Restaurant',
            'description' => 'A demo restaurant for admin permissions.',
            'address' => '123 Admin St',
            'longitude' => 0.0,
            'latitude' => 0.0,
            'phone_number' => '1234567890',
            'email' => 'admin-restaurant@example.com',
            'website' => null,
            'opening_hours' => json_encode(['mon-fri' => '9:00-18:00']),
            'cuisine_id' => $firstCuisineId,
            'price_range' => '500-5000',
            'image' => null,
            'owner_id' => $user->id,
            'is_approved' => true,
            'status' => 1
        ]);

        foreach ($permissions as $permission) {
            RestaurantPermission::create([
                'user_id' => $user->id,
                'restaurant_id' => $restaurant->id,
                'permission_name' => $permission
            ]);
        }

        User::create([
            'first_name' => 'Demo',
            'last_name' => 'User',
            'email' => 'demo@example.com',
            'password' => Hash::make('password'),
            'role' => 'client',
            'phone_number' => '1234567890',
            'profile_picture' => null,
            'preferences' => json_encode([]),
            'status' => 1,
            'google2fa_secret' => null,
            'has_2fa_enabled' => false,
            'fcm_token' => null,
        ]);
        User::create([
            'first_name' => '2FA',
            'last_name' => 'Enabled',
            'email' => '2fa@example.com',
            'password' => Hash::make('password'),
            'role' => 'client',
            'phone_number' => '0987654321',
            'profile_picture' => null,
            'preferences' => json_encode([]),
            'status' => 1,
            'google2fa_secret' => 'SOMESECRETKEY',
            'has_2fa_enabled' => true,
            'fcm_token' => 'demo_fcm_token',
        ]);
    }
}
