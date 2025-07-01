@extends('layouts.app')

@section('content')
<main class="content-wrapper">
    <div class="main-content manage-users">
<div class="container">
    <h1 class="mb-4">Tables</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Add Table Form -->

    <div class="card mb-4">
        <div class="card-header">Add Table</div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.tables.store') }}">
                @csrf
                <input type="hidden" name="restaurant_id" value="{{ session('userData')['users']->restaurant_id }}">
                <div class="row">
                    <div class="col-md-6">
                        <label for="table_number" class="form-label">Table Number</label>
                        <input type="text" name="table_number" id="table_number" class="form-control" required>
                    </div>
                    <div class="col-md-6 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary">Add Table</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Table Number</th>
                        <th>Restaurant</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tables as $table)
                        <tr>
                            <td>{{ $table->id }}</td>
                            <td>
                                <form method="POST" action="{{ route('admin.tables.update', $table->id) }}" class="d-inline-flex">
                                    @csrf
                                    @method('PATCH')
                                    <input type="text" name="table_number" value="{{ $table->table_number }}" class="form-control form-control-sm me-2" style="width: 90px;" required>
                                    <select name="status" class="form-control form-control-sm me-2" style="width: 110px;">
                                        <option value="available" @if($table->status === 'available') selected @endif>Available</option>
                                        <option value="occupied" @if($table->status === 'occupied') selected @endif>Occupied</option>
                                    </select>
                                    <button type="submit" class="btn btn-sm btn-success me-1">Save</button>
                                </form>
                            </td>
                            <td>{{ $table->restaurant->name ?? '-' }}</td>
                            <td>
                                @if($table->status === 'available')
                                    <span class="badge bg-success">Available</span>
                                @else
                                    <span class="badge bg-danger">Occupied</span>
                                @endif
                            </td>
                            <td>
                                <form method="POST" action="{{ route('admin.tables.toggle-status', $table->id) }}" style="display:inline-block">
                                    @csrf
                                    <button type="submit" class="btn btn-sm {{ $table->status === 'available' ? 'btn-danger' : 'btn-success' }}">
                                        {{ $table->status === 'available' ? 'Mark Occupied' : 'Mark Available' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No tables found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
</main>
@endsection
