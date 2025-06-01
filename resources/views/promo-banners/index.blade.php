@extends('layouts.app')

@section('content')
<main class="content-wrapper">
    <div class="main-content manage-users">
<div class="container">
    <h1>Promo Banners</h1>
    <a href="{{ route('promo-banners.create') }}" class="btn btn-primary mb-3 admin-btn-3d float-end">Add Promo Banner</a>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table id="manageUsersTable" class="display custom-datatable" style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Restaurant</th>
                <th>Title</th>
                <th>Image</th>
                <th>Active</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($banners as $banner)
                <tr>
                    <td>{{ $banner->id }}</td>
                    <td>{{ $banner->restaurant->name ?? '-' }}</td>
                    <td>{{ $banner->title }}</td>
                    <td>
                        @if($banner->image_path)
                            <img src="{{ $banner->image_path }}" alt="Banner Image" width="80" class="img-thumbnail banner-thumb" style="cursor:pointer" data-img="{{ $banner->image_path }}">
                        @endif
                    </td>
                    <td>{{ $banner->is_active ? 'Yes' : 'No' }}</td>
                    <td>
                        <a href="{{ route('promo-banners.edit', $banner->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('promo-banners.destroy', $banner->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $banners->links() }}
</div>
</div>

<!-- Image Modal -->
<div class="modal fade" id="bannerImageModal" tabindex="-1" aria-labelledby="bannerImageModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="bannerImageModalLabel">Promo Banner Image</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <img src="" id="bannerModalImg" class="img-fluid" alt="Banner Image">
      </div>
    </div>
  </div>
</div>
</main>
@endsection

@section('script')
<script>
$(document).ready(function() {
    $('.banner-thumb').on('click', function() {
        var imgSrc = $(this).data('img');
        $('#bannerModalImg').attr('src', imgSrc);
        $('#bannerImageModal').modal('show');
    });
});
</script>
@endsection
