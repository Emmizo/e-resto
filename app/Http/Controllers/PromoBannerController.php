<?php

namespace App\Http\Controllers;

use App\Events\PromoBannerUpdated;
use App\Models\PromoBanner;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class PromoBannerController extends Controller
{
    public function index()
    {
        $banners = PromoBanner::with('restaurant')
            ->where('restaurant_id', session('userData')['users']->restaurant_id)
            ->latest()
            ->paginate(10);
        return view('promo-banners.index', compact('banners'));
    }

    public function create()
    {
        return view('promo-banners.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'boolean',
        ]);
        $data = $request->only(['title', 'description', 'start_date', 'end_date', 'is_active']);
        $data['is_active'] = $request->has('is_active');
        $data['restaurant_id'] = session('userData')['users']->restaurant_id;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $folder = public_path('promo_banners');
            if (!file_exists($folder)) {
                mkdir($folder, 0777, true);
            }
            $filename = uniqid('banner_') . '.' . $image->getClientOriginalExtension();
            $image->move($folder, $filename);
            $data['image_path'] = config('app.url') . '/promo_banners/' . $filename;
        }
        $banner = PromoBanner::create($data);
        broadcast(new PromoBannerUpdated($banner, 'created'))->toOthers();
        return redirect()->route('promo-banners.index')->with('success', 'Promo banner created!');
    }

    public function edit($id)
    {
        $banner = PromoBanner::findOrFail($id);
        $restaurants = Restaurant::all();
        return view('promo-banners.edit', compact('banner', 'restaurants'));
    }

    public function update(Request $request, $id)
    {
        $banner = PromoBanner::findOrFail($id);
        $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'boolean',
        ]);
        $data = $request->only(['restaurant_id', 'title', 'description', 'start_date', 'end_date', 'is_active']);
        $data['is_active'] = $request->has('is_active');
        if ($request->hasFile('image')) {
            if ($banner->image_path) {
                $oldPath = public_path(str_replace(config('app.url') . '/', '', $banner->image_path));
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }
            $image = $request->file('image');
            $folder = public_path('promo_banners');
            if (!file_exists($folder)) {
                mkdir($folder, 0777, true);
            }
            $filename = uniqid('banner_') . '.' . $image->getClientOriginalExtension();
            $image->move($folder, $filename);
            $data['image_path'] = config('app.url') . '/promo_banners/' . $filename;
        }
        $banner->update($data);
        broadcast(new PromoBannerUpdated($banner, 'updated'))->toOthers();
        return redirect()->route('promo-banners.index')->with('success', 'Promo banner updated!');
    }

    public function destroy($id)
    {
        $banner = PromoBanner::findOrFail($id);
        if ($banner->image_path) {
            $oldPath = public_path(str_replace(config('app.url') . '/', '', $banner->image_path));
            if (file_exists($oldPath)) {
                @unlink($oldPath);
            }
        }
        broadcast(new PromoBannerUpdated($banner, 'deleted'))->toOthers();
        $banner->delete();
        return redirect()->route('promo-banners.index')->with('success', 'Promo banner deleted!');
    }
}
