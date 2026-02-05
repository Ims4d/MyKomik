@extends('layouts.dashboard')

@section('title', 'Users Management')
@section('page-title', 'Users Management')

@section('content')
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <h2 class="text-2xl font-bold text-white">All Users</h2>
        <a href="{{ route('dashboard.users.create') }}" class="px-4 py-2 bg-sky-600 hover:bg-sky-500 text-white font-semibold rounded-lg shadow-md transition flex items-center gap-2">
            <i class="fas fa-plus"></i> Add New User
        </a>
    </div>

    <!-- Search & Filter -->
    <div class="mb-6 bg-neutral-800/50 border border-neutral-700 rounded-xl p-4">
        <form action="{{ route('dashboard.users.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-center">
            <div class="md:col-span-2">
                <label for="search" class="sr-only">Search</label>
                <input type="text" name="search" id="search" class="w-full px-4 py-2 bg-neutral-700 border border-neutral-600 rounded-lg placeholder-neutral-400 text-white focus:outline-none focus:ring-2 focus:ring-sky-500" placeholder="Search by username, display name, or email..." value="{{ request('search') }}">
            </div>
            <div>
                <label for="role" class="sr-only">Role</label>
                <select name="role" id="role" class="w-full px-4 py-2 bg-neutral-700 border border-neutral-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-sky-500">
                    <option value="">All Roles</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>User</option>
                </select>
            </div>
            <button type="submit" class="w-full px-4 py-2 bg-neutral-600 hover:bg-neutral-500 text-white font-semibold rounded-lg transition flex items-center justify-center gap-2">
                <i class="fas fa-search"></i> Search
            </button>
        </form>
    </div>

    @if($users->count() > 0)
        <div class="bg-neutral-800/50 border border-neutral-700 rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-neutral-300">
                    <thead class="text-xs text-neutral-400 uppercase bg-neutral-700/50">
                        <tr>
                            <th scope="col" class="px-6 py-3">User</th>
                            <th scope="col" class="px-6 py-3">Role</th>
                            <th scope="col" class="px-6 py-3">Joined Date</th>
                            <th scope="col" class="px-6 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr class="border-b border-neutral-700 hover:bg-neutral-700/40">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        <img class="w-10 h-10 rounded-full object-cover" src="{{ $user->getAvatarUrl() }}" alt="{{ $user->username }}">
                                        <div>
                                            <div class="font-bold text-white">{{ $user->display_name ?? $user->username }}</div>
                                            <div class="text-neutral-400">@<span>{{ $user->username }}</span></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-xs font-semibold px-2 py-1 rounded-full {{ $user->role === 'admin' ? 'bg-red-500/20 text-red-300' : 'bg-blue-500/20 text-blue-300' }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    {{ $user->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end items-center gap-2">
                                        <a href="{{ route('dashboard.users.edit', $user->user_id) }}" class="text-yellow-400 hover:text-yellow-300 transition px-3 py-2 bg-yellow-500/10 rounded-lg" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if(auth()->user()->user_id != $user->user_id)
                                            <form action="{{ route('dashboard.users.destroy', $user->user_id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-400 transition px-3 py-2 bg-red-500/10 rounded-lg" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-6 p-4 bg-neutral-800/50 border border-neutral-700 rounded-xl">
             {{ $users->appends(request()->query())->links() }}
        </div>
    @else
        <div class="text-center py-16 bg-neutral-800/50 border border-neutral-700 rounded-xl">
            <i class="fas fa-users fa-4x text-neutral-500 mb-4"></i>
            <h3 class="text-xl font-semibold text-white">No Users Found</h3>
            <p class="text-neutral-400 mt-2">Your search returned no results, or no users are registered.</p>
        </div>
    @endif
@endsection
