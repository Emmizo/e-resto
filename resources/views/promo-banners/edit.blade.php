@extends('layouts.app')

@section('content')
<main class="content-wrapper">
    <div class="main-content manage-users">
<div class="container">
    <h1>Edit Promo Banner</h1>
    <form action="{{ route('promo-banners.update', $banner->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="restaurant_id" class="form-label">Restaurant</label>
            <select name="restaurant_id" id="restaurant_id" class="form-control" required>
                <option value="">Select Restaurant</option>
                @foreach($restaurants as $restaurant)
                    <option value="{{ $restaurant->id }}" {{ $banner->restaurant_id == $restaurant->id ? 'selected' : '' }}>{{ $restaurant->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ $banner->title }}" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control">{{ $banner->description }}</textarea>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Image</label>
            @if($banner->image_path)
                <div class="mb-2">
                    <img src="{{ asset( $banner->image_path) }}" alt="Banner Image" width="120">
                </div>
            @endif
            <input type="file" name="image" id="image" class="form-control">
        </div>
        <div class="mb-3">
            <label for="start_date" class="form-label">Start Date</label>
            <input type="date" name="start_date" id="start_date" class="form-control" value="{{ $banner->start_date }}">
        </div>
        <div class="mb-3">
            <label for="end_date" class="form-label">End Date</label>
            <input type="date" name="end_date" id="end_date" class="form-control" value="{{ $banner->end_date }}">
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1" {{ $banner->is_active ? 'checked' : '' }}>
            <label for="is_active" class="form-check-label">Active</label>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('promo-banners.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</div>
</main>
@endsection
