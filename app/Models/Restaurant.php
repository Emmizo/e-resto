<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    protected $fillable = [
        'name',
        'description',
        'address',
        'longitude',
        'latitude',
        'phone_number',
        'email',
        'website',
        'opening_hours',
        'cuisine_type',
        'price_range',
        'image',
        'owner_id',
        'is_approved',
    ];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'opening_hours' => 'array', // Cast JSON field to array
        'is_approved' => 'boolean', // Cast boolean field
    ];

    /**
     * Get the owner of the restaurant.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
    //
}
