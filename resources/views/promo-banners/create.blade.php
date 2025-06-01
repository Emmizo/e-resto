@extends('layouts.app')

@section('content')
<main class="content-wrapper">
    <div class="main-content manage-users">
<div class="container">
    <h1>Add Promo Banner</h1>
    <form id="promoBannerForm" action="{{ route('promo-banners.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" name="title" id="title" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control"></textarea>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Image</label>
            <input type="file" name="image" id="image" class="form-control">
        </div>
        <div class="mb-3">
            <label for="start_date" class="form-label">Start Date</label>
            <input type="date" name="start_date" id="start_date" class="form-control">
        </div>
        <div class="mb-3">
            <label for="end_date" class="form-label">End Date</label>
            <input type="date" name="end_date" id="end_date" class="form-control">
        </div>
        <div class="mb-3 form-check">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1" checked>
            <label for="is_active" class="form-check-label">Active</label>
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
        <a href="{{ route('promo-banners.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
    <div id="banner-alert" class="mt-3"></div>
</div>
</div>
</main>
@endsection

@section('script')
<script>
$(document).ready(function() {
    $('#promoBannerForm').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function(response) {
                $('#banner-alert').html('<div class="alert alert-success">Promo banner created!</div>');
                setTimeout(function() {
                    window.location.href = "{{ route('promo-banners.index') }}";
                }, 1200);
            },
            error: function(xhr) {
                let msg = 'An error occurred.';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    msg = '<ul>';
                    $.each(xhr.responseJSON.errors, function(key, val) {
                        msg += '<li>' + val[0] + '</li>';
                    });
                    msg += '</ul>';
                }
                $('#banner-alert').html('<div class="alert alert-danger">' + msg + '</div>');
            }
        });
    });
});
</script>
@endsection
