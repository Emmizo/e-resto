@extends('layouts.app')

@section('content')
<main class="content-wrapper">
    <div class="main-content manage-users">
        <div class="col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2>Terms and Conditions</h2>
                <a href="{{ route('admin.terms.create') }}" class="btn btn-primary">Add New</a>
            </div>
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <div class="card shadow-sm">
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Content Preview</th>
                                <th>Active</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($terms as $term)
                            <tr>
                                <td>{{ $term->id }}</td>
                                <td>{{ Str::limit($term->content, 60) }}</td>
                                <td>{{ $term->is_active ? 'Yes' : 'No' }}</td>
                                <td>{{ $term->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <a href="{{ route('admin.terms.edit', $term->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
