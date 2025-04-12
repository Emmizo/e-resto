<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, HasRoles;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'role',
        'phone_number',
        'profile_picture',
        'preferences',
        'status',
        'google2fa_secret',
        'has_2fa_enabled',
        'fcm_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'google2fa_secret'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'preferences' => 'array',
        'status' => 'integer',
        'has_2fa_enabled' => 'boolean'
    ];

    /**
     * Get the current restaurant ID from session
     */
    public function getCurrentRestaurantId()
    {
        return session('userData.users.restaurant_id');
    }

    /**
     * Check if user has permission for a specific restaurant
     *
     * @param string $permissionName
     * @param int $restaurantId
     * @return bool
     */
    // In your User model
    public function hasRestaurantPermission($permissionName, $restaurantId = null)
    {
        $restaurantId = session('userData')['users']->restaurant_id;
        $restaurantId = $restaurantId ?? $this->getCurrentRestaurantId();

        // Global admin always has full access
        if ($this->role === 'admin') {
            return true;
        }

        // Restaurant owner has full access to their restaurant
        if ($this->role === 'restaurant_owner' &&
            Restaurant::where('owner_id', $this->id)
                ->where('id', $restaurantId)
                ->exists()) {
            return true;
        }

        // Check Spatie global permissions first
        if ($this->hasPermissionTo($permissionName)) {
            return true;
        }

        // Check restaurant-specific permissions
        return RestaurantPermission::where('user_id', $this->id)
            ->where('restaurant_id', $restaurantId)
            ->where('permission_name', $permissionName)
            ->active()
            ->exists();
    }

    /**
     * Get all permissions for a specific restaurant
     *
     * @param int|null $restaurantId
     * @return array
     */
    public function getRestaurantPermissions($restaurantId = null)
    {
        $restaurantId = session('userData')['users']->restaurant_id;
        // Use current restaurant ID if not provided
        $restaurantId = $restaurantId ?? $this->getCurrentRestaurantId();

        // Get global permissions
        $globalPermissions = $this->getAllPermissions()->pluck('name')->toArray();

        // Get restaurant-specific permissions
        $restaurantPermissions = RestaurantPermission::where('user_id', $this->id)
            ->where('restaurant_id', $restaurantId)
            ->where('is_active', true)
            ->pluck('permission_name')
            ->toArray();

        // Combine and remove duplicates
        return array_values(array_unique(array_merge($globalPermissions, $restaurantPermissions)));
    }

    /**
     * Relationship to restaurant permissions
     */
    public function restaurantPermissions()
    {
        return $this->hasMany(RestaurantPermission::class);
    }

    /**
     * Grant a specific restaurant permission
     *
     * @param string $permissionName
     * @param int|null $restaurantId
     * @return self
     */
    public function grantRestaurantPermission($permissionName, $restaurantId = null)
    {
        $restaurantId = session('userData')['users']->restaurant_id;
        $restaurantId = $restaurantId ?? $this->getCurrentRestaurantId();

        RestaurantPermission::updateOrCreate(
            [
                'user_id' => $this->id,
                'restaurant_id' => $restaurantId,
                'permission_name' => $permissionName
            ],
            ['is_active' => true]
        );

        return $this;
    }

    /**
     * Revoke a specific restaurant permission
     *
     * @param string $permissionName
     * @param int|null $restaurantId
     * @return self
     */
    public function revokeRestaurantPermission($permissionName, $restaurantId = null)
    {
        $restaurantId = session('userData')['users']->restaurant_id;
        $restaurantId = $restaurantId ?? $this->getCurrentRestaurantId();

        RestaurantPermission::where('user_id', $this->id)
            ->where('restaurant_id', $restaurantId)
            ->where('permission_name', $permissionName)
            ->update(['is_active' => false]);

        return $this;
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function restaurantEmployees()
    {
        return $this->hasMany(RestaurantEmployee::class);
    }
}
