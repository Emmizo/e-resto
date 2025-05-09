@extends('layouts.app')

@section('content')
<main class="content-wrapper">
    <div class="main-content manage-users">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2>All Restaurants</h2>
            </div>
            <div class="admin-card-3d shadow-3d">
                <div class="admin-card-body">
                    <table class="table admin-table-3d">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Owner</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Address</th>
                                <th>Approved</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($restaurants as $restaurant)
                            <tr>
                                <td>{{ $restaurant->id }}</td>
                                <td>{{ $restaurant->name }}</td>
                                <td>{{ $restaurant->owner ? $restaurant->owner->first_name . ' ' . $restaurant->owner->last_name : '-' }}</td>
                                <td>{{ $restaurant->email }}</td>
                                <td>{{ $restaurant->phone_number }}</td>
                                <td>{{ $restaurant->address }}</td>
                                <td>
                                    <span class="badge {{ $restaurant->is_approved ? 'bg-success' : 'bg-danger' }}">
                                        {{ $restaurant->is_approved ? 'Approved' : 'Pending' }}
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm admin-btn-3d approve-btn" style="min-width: 100px;" data-id="{{ $restaurant->id }}" data-approved="{{ $restaurant->is_approved ? 0 : 1 }}">
                                        {{ $restaurant->is_approved ? 'Unapprove' : 'Approve' }}
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.approve-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var id = this.getAttribute('data-id');
                var isApproved = this.getAttribute('data-approved');
                var token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                fetch(`/admin/restaurants/${id}/approve`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify({ is_approved: isApproved })
                })
                .then(response => response.json())
                .then(data => {
                    if(data.status === 'success') {
                        location.reload();
                    }
                });
            });
        });
    });
</script>
@endsection
