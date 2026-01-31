@extends('layouts.dashboard')

@section('title', 'Users Management')
@section('page-title', 'Users Management')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-users"></i> All Users</h5>
            <a href="{{ route('dashboard.users.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Add New User</a>
        </div>
        
        <div class="card-body">
            <form action="{{ route('dashboard.users.index') }}" method="GET" class="row g-3 mb-4">
                <div class="col-md-6">
                    <input type="text" name="search" class="form-control" placeholder="Search by username or display name..." value="{{ request('search') }}">
                </div>
                <div class="col-md-4">
                    <select name="role" class="form-select">
                        <option value="">All Roles</option>
                        <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>User</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-secondary w-100"><i class="fas fa-search"></i> Search</button>
                </div>
            </form>

            @if($users->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Display Name</th>
                                <th>Role</th>
                                <th>Created At</th>
                                <th width="150">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->user_id }}</td>
                                    <td><strong>{{ $user->username }}</strong></td>
                                    <td>{{ $user->display_name ?? '-' }}</td>
                                    <td><span class="badge bg-{{ $user->role === 'admin' ? 'danger' : 'info' }}">{{ ucfirst($user->role) }}</span></td>
                                    <td>{{ $user->created_at }}</td>
                                    <td>
                                        <a href="{{ route('dashboard.users.edit', $user->user_id) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                        @if(auth()->user()->user_id != $user->user_id)
                                            <form action="{{ route('dashboard.users.destroy', $user->user_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this user?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">{{ $users->links() }}</div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No users found.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection