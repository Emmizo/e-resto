<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    // Get notifications for the authenticated user, optionally filtered by restaurant_id
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Notification::where('user_id', $user->id);
        if ($request->has('restaurant_id')) {
            $query->where('restaurant_id', (int) $request->restaurant_id);
        }
        $notifications = $query->orderBy('created_at', 'desc')->limit(20)->get();
        $unreadCount = $query->where('is_read', false)->count();
        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount,
        ]);
    }

    // Mark notifications as read
    public function markAsRead(Request $request)
    {
        $user = Auth::user();
        $ids = $request->input('ids', []);
        Notification::where('user_id', $user->id)
            ->whereIn('id', $ids)
            ->update(['is_read' => true]);
        return response()->json(['status' => 'success']);
    }

    // Show all notifications in a Blade view
    public function all(Request $request)
    {
        $query = Notification::where('user_id', auth()->id());
        if ($request->filled('restaurant_id')) {
            $query->where('restaurant_id', $request->restaurant_id);
        }
        if ($request->filled('type')) {
            $query->where('data->type', $request->type);
        }
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }
        $notifications = $query->orderBy('created_at', 'desc')->paginate(50);
        return view('notifications.all', compact('notifications'));
    }

    public function show($id)
    {
        $notification = Notification::where('user_id', auth()->id())->findOrFail($id);
        $notification->update(['is_read' => true]);
        return view('notifications.show', compact('notification'));
    }

    public function markAllAsRead()
    {
        Notification::where('user_id', auth()->id())->where('is_read', false)->update(['is_read' => true]);
        return back()->with('success', 'All notifications marked as read.');
    }
}
