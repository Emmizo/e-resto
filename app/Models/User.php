<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable,HasApiTokens,HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
       'first_name',
        'last_name',
        'email',
        'password',
        'role',
        'phone_number',
        'profile_picture',
        'preferences',
    ];
    /**
     * Get all permissions for a specific restaurant
     */
    public function getRestaurantPermissions($restaurantId)
    {
        $restaurantEmployee = RestaurantEmployee::where('user_id', $this->id)
            ->where('restaurant_id', $restaurantId)
            ->first();

        return $restaurantEmployee
            ? json_decode($restaurantEmployee->permissions, true)
            : [];
    }

    /**
     * Check if user has any of the given permissions for a restaurant
     */
    public function hasAnyRestaurantPermission($restaurantId, array $permissionNames)
    {
        $userPermissions = $this->getRestaurantPermissions($restaurantId);

        return collect($permissionNames)
            ->contains(fn ($permission) => in_array($permission, $userPermissions));
    }

    /**
     * Get the restaurants where the user has specific permissions
     */
    public function restaurantsWithPermission($permissionName)
    {
        return RestaurantEmployee::where('user_id', $this->id)
            ->whereJsonContains('permissions', $permissionName)
            ->pluck('restaurant_id');
    }
// Add a method to check restaurant-specific permissions
public function hasRestaurantPermission($restaurantId, $permissionName)
{
    // Check if the user has a specific permission for this restaurant
    return RestaurantEmployee::where('user_id', $this->id)
        ->where('restaurant_id', $restaurantId)
        ->whereJsonContains('permissions', $permissionName)
        ->exists();
}

// Method to assign restaurant-specific permissions
public function assignRestaurantPermission($restaurantId, $permissionName)
{
    $restaurantEmployee = RestaurantEmployee::where('user_id', $this->id)
        ->where('restaurant_id', $restaurantId)
        ->first();

    if ($restaurantEmployee) {
        $currentPermissions = json_decode($restaurantEmployee->permissions, true) ?? [];

        if (!in_array($permissionName, $currentPermissions)) {
            $currentPermissions[] = $permissionName;

            $restaurantEmployee->update([
                'permissions' => json_encode($currentPermissions)
            ]);
        }
    }
}
  // Method to remove restaurant-specific permissions
  public function removeRestaurantPermission($restaurantId, $permissionName)
  {
      $restaurantEmployee = RestaurantEmployee::where('user_id', $this->id)
          ->where('restaurant_id', $restaurantId)
          ->first();

      if ($restaurantEmployee) {
          $currentPermissions = json_decode($restaurantEmployee->permissions, true) ?? [];

          $updatedPermissions = array_filter($currentPermissions, function($permission) use ($permissionName) {
              return $permission !== $permissionName;
          });

          $restaurantEmployee->update([
              'permissions' => json_encode(array_values($updatedPermissions))
          ]);
      }
  }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
