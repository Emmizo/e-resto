<?php

use App\Models\Restaurant;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Restaurant owner channel: receives new orders & reservations
Broadcast::channel('owner.{ownerId}', function ($user, $ownerId) {
    return (int) $user->id === (int) $ownerId;
});

// Per-user channel: client receives order/reservation status updates
Broadcast::channel('user.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});

// Legacy restaurant channels
Broadcast::channel('restaurant.{restaurantId}', function ($user, $restaurantId) {
    if ($user->role === 'restaurant_owner') {
        return Restaurant::where('id', $restaurantId)->where('owner_id', $user->id)->exists();
    }
    return false;
});

Broadcast::channel('service-status.{restaurantId}', function ($user, $restaurantId) {
    if ($user->role === 'restaurant_owner') {
        return Restaurant::where('id', $restaurantId)->where('owner_id', $user->id)->exists();
    }
    return false;
});
