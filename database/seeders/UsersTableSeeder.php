<?php

namespace Database\Seeders;

use App\Models\RestaurantPermission;
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
        $user = DB::table('users')->insert([
            [
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
            ]
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
        ];
        foreach ($permissions as $permission) {
            RestaurantPermission::create([
                'user_id' => $user->id,
                // 'restaurant_id' => $restaurantId,
                'permission_name' => $permission,
                'granted' => true
            ]);
        }
    }
}
