<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'menu_id',
        'name',
        'description',
        'price',
        'image',
        'category',
        'dietary_info',
        'is_available',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'price' => 'decimal:2',  // Cast price to a decimal with 2 decimal places
        'dietary_info' => 'array',  // Cast JSON field to array
        'is_available' => 'boolean',  // Cast boolean field
    ];

    /**
     * Get the menu associated with this menu item.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    public function getRestaurantAttribute()
    {
        // If menu is loaded, get restaurant from menu
        if ($this->relationLoaded('menu') && $this->menu) {
            return $this->menu->restaurant;
        }
        // Otherwise, try to load via menu
        $menu = $this->menu()->with('restaurant')->first();
        return $menu ? $menu->restaurant : null;
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function favorites()
    {
        return $this->hasMany(\App\Models\FavoriteMenuItem::class, 'menu_item_id');
    }
}
