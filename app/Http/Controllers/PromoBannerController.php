<?php

namespace App\Http\Controllers;

use App\Events\PromoBannerUpdated;
use App\Models\PromoBanner;
use Illuminate\Http\Request;

class PromoBannerController extends Controller
{
    private function restaurantId(): int
    {
        $id = session('userData')['users']->restaurant_id ?? null;
        if (!$id) {
            abort(403, 'No restaurant associated with your account.');
        }
        return $id;
    }

    private function storeImage(Request $request, ?string $existing = null): ?string
    {
        if (!$request->hasFile('image')) {
            return $existing;
        }
        if ($existing) {
            $old = public_path(str_replace(config('app.url') . '/', '', $existing));
            if (file_exists($old)) {
                @unlink($old);
            }
        }
        $folder = public_path('promo_banners');
        if (!file_exists($folder)) {
            mkdir($folder, 0777, true);
        }
        $file = $request->file('image');
        $filename = uniqid('banner_') . '.' . $file->getClientOriginalExtension();
        $file->move($folder, $filename);
        return config('app.url') . '/promo_banners/' . $filename;
    }

    public function index()
    {
        $user = auth()->user();
        if ($user->role === 'admin') {
            $banners = PromoBanner::with('restaurant')->latest()->paginate(20);
            return view('promo-banners.index', compact('banners'));
        }
        $restaurantId = $this->restaurantId();
        $banners = PromoBanner::with('restaurant')
            ->where('restaurant_id', $restaurantId)
            ->latest()
            ->paginate(10);
        return view('promo-banners.index', compact('banners'));
    }

    public function create()
    {
        if (auth()->user()->role === 'admin') {
            abort(403, 'Admins cannot create promo banners.');
        }
        $this->restaurantId();
        return view('promo-banners.create');
    }

    public function store(Request $request)
    {
        if (auth()->user()->role === 'admin') {
            abort(403, 'Admins cannot create promo banners.');
        }
        $restaurantId = $this->restaurantId();

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'start_date'  => 'nullable|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',
        ]);

        // Always publish immediately — no manual activation needed
        $validated['is_active']     = true;
        $validated['restaurant_id'] = $restaurantId;
        $validated['image_path']    = $this->storeImage($request);

        $banner = PromoBanner::create($validated);
        broadcast(new PromoBannerUpdated($banner, 'created'));

        if ($request->expectsJson()) {
            return response()->json([
                'status'   => 200,
                'message'  => 'Promo banner created!',
                'redirect' => route('promo-banners.index'),
            ]);
        }
        return redirect()->route('promo-banners.index')->with('success', 'Promo banner created!');
    }

    public function edit($id)
    {
        $restaurantId = $this->restaurantId();
        $banner = PromoBanner::where('restaurant_id', $restaurantId)->findOrFail($id);
        return view('promo-banners.edit', compact('banner'));
    }

    public function update(Request $request, $id)
    {
        $restaurantId = $this->restaurantId();
        $banner = PromoBanner::where('restaurant_id', $restaurantId)->findOrFail($id);

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'start_date'  => 'nullable|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',
        ]);

        // Preserve current active state — only admin can change it via toggle
        $validated['image_path'] = $this->storeImage($request, $banner->image_path);

        $banner->update($validated);
        broadcast(new PromoBannerUpdated($banner, 'updated'));

        if ($request->expectsJson()) {
            return response()->json([
                'status'   => 200,
                'message'  => 'Promo banner updated!',
                'redirect' => route('promo-banners.index'),
            ]);
        }
        return redirect()->route('promo-banners.index')->with('success', 'Promo banner updated!');
    }

    public function toggleActive($id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }
        $banner = PromoBanner::findOrFail($id);
        $banner->is_active = !$banner->is_active;
        $banner->save();
        broadcast(new PromoBannerUpdated($banner, 'updated'));
        return response()->json(['status' => 200, 'is_active' => $banner->is_active]);
    }

    public function destroy($id)
    {
        $restaurantId = $this->restaurantId();
        $banner = PromoBanner::where('restaurant_id', $restaurantId)->findOrFail($id);

        if ($banner->image_path) {
            $old = public_path(str_replace(config('app.url') . '/', '', $banner->image_path));
            if (file_exists($old)) {
                @unlink($old);
            }
        }
        broadcast(new PromoBannerUpdated($banner, 'deleted'));
        $banner->delete();

        return redirect()->route('promo-banners.index')->with('success', 'Promo banner deleted!');
    }
}
