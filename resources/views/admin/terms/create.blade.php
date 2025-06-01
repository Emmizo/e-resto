@extends('layouts.app')
@section('content')

<main class="content-wrapper">
    <div class="main-content manage-users">
        <div class="col-lg-8">
            <div class="admin-card-3d shadow-3d">
                <div class="admin-card-body">
                    <h2 class="mb-4">Add Terms and Conditions</h2>
                    <form method="POST" action="{{ route('admin.terms.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="content" class="form-label">Content</label>
                            <textarea name="content" id="content" class="form-control" rows="10" required>{{ old('content') }}</textarea>
                            @error('content')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" {{ old('is_active') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>
                        <button type="submit" class="btn admin-btn-3d">Save</button>
                        <a href="{{ route('admin.terms.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
