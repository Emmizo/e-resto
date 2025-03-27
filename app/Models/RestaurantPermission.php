<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestaurantPermission extends Model
{
    protected $fillable = [
        'restaurant_id',
        'user_id',
        'permission_name',
         'is_active'
    ];
    protected $table = 'restaurant_permissions';
 // Relationship to User
 public function user()
 {
     return $this->belongsTo(User::class);
 }

 // Relationship to Restaurant
 public function restaurant()
 {
     return $this->belongsTo(Restaurant::class);
 }

 // Relationship to the base Permission (from Spatie)
 public function basePermission()
 {
     return $this->belongsTo(Permission::class, 'permission_name', 'name');
 }

 // Scope for active permissions
 public function scopeActive($query)
 {
     return $query->where('is_active', true);
 }
}
