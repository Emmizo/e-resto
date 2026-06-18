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
        'stock_quantity',
        'total_sold',
        'track_inventory',
    ];

    protected $casts = [
        'price'           => 'decimal:2',
        'dietary_info'    => 'array',
        'is_available'    => 'boolean',
        'track_inventory' => 'boolean',
    ];

    /** Returns true when the item can still be ordered */
    public function isInStock(): bool
    {
        if (!$this->track_inventory) return true;
        return $this->stock_quantity === null || $this->stock_quantity > 0;
    }

    /** Deduct quantity sold and auto-disable when stock hits 0 */
    public function deductStock(int $qty): void
    {
        if (!$this->track_inventory) return;
        $this->increment('total_sold', $qty);
        if ($this->stock_quantity !== null) {
            $newStock = max(0, $this->stock_quantity - $qty);
            $this->stock_quantity = $newStock;
            if ($newStock === 0) {
                $this->is_available = false;
            }
            $this->save();
        }
    }

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
