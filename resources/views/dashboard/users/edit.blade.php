@extends('layouts.dashboard')

@section('title', 'Edit User')
@section('page-title', 'Edit User')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-white">Editing User: <span class="text-sky-400">{{ $user->username }}</span></h2>
        <a href="{{ route('dashboard.users.index') }}" class="px-4 py-2 bg-neutral-700 hover:bg-neutral-600 text-white font-semibold rounded-lg shadow-md transition flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Back to All Users
        </a>
    </div>

    <div class="max-w-2xl mx-auto">
        <form action="{{ route('dashboard.users.update', $user->user_id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="username" class="block text-sm font-medium text-neutral-300 mb-2">Username *</label>
                    <input type="text" id="username" name="username" value="{{ old('username', $user->username) }}" required
                           class="w-full px-4 py-3 bg-neutral-700 border {{ $errors->has('username') ? 'border-red-500' : 'border-neutral-600' }} rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500">
                    @error('username')<p class="text-red-400 text-sm mt-2">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="display_name" class="block text-sm font-medium text-neutral-300 mb-2">Display Name</label>
                    <input type="text" id="display_name" name="display_name" value="{{ old('display_name', $user->display_name) }}"
                           class="w-full px-4 py-3 bg-neutral-700 border {{ $errors->has('display_name') ? 'border-red-500' : 'border-neutral-600' }} rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500">
                    @error('display_name')<p class="text-red-400 text-sm mt-2">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-neutral-300 mb-2">Email Address *</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                       class="w-full px-4 py-3 bg-neutral-700 border {{ $errors->has('email') ? 'border-red-500' : 'border-neutral-600' }} rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500">
                @error('email')<p class="text-red-400 text-sm mt-2">{{ $message }}</p>@enderror
            </div>

            <div class="p-4 border border-neutral-700 rounded-lg">
                <p class="text-neutral-300 mb-4">Leave password fields blank to keep the current password.</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="password" class="block text-sm font-medium text-neutral-300 mb-2">New Password</label>
                        <input type="password" id="password" name="password"
                               class="w-full px-4 py-3 bg-neutral-700 border {{ $errors->has('password') ? 'border-red-500' : 'border-neutral-600' }} rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500">
                        @error('password')<p class="text-red-400 text-sm mt-2">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-neutral-300 mb-2">Confirm New Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                               class="w-full px-4 py-3 bg-neutral-700 border rounded-lg bg-neutral-700 border-neutral-600 focus:outline-none focus:ring-2 focus:ring-sky-500">
                    </div>
                </div>
            </div>

            <div>
                <label for="role" class="block text-sm font-medium text-neutral-300 mb-2">Role *</label>
                <select id="role" name="role" required 
                        {{ auth()->user()->user_id == $user->user_id ? 'disabled' : '' }}
                        class="w-full px-4 py-3 bg-neutral-700 border {{ $errors->has('role') ? 'border-red-500' : 'border-neutral-600' }} rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500
                               {{ auth()->user()->user_id == $user->user_id ? 'bg-neutral-800 cursor-not-allowed' : '' }}">
                    <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>User</option>
                    <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
                @if(auth()->user()->user_id == $user->user_id)
                    <p class="text-neutral-400 text-sm mt-2">You cannot change your own role.</p>
                    <input type="hidden" name="role" value="{{ $user->role }}">
                @endif
                @error('role')<p class="text-red-400 text-sm mt-2">{{ $message }}</p>@enderror
            </div>

            <div class="flex items-center gap-4 pt-4">
                <button type="submit" class="px-6 py-3 bg-sky-600 hover:bg-sky-500 text-white font-bold rounded-lg shadow-lg transition flex items-center gap-2">
                    <i class="fas fa-save"></i> Update User
                </button>
                <a href="{{ route('dashboard.users.index') }}" class="text-neutral-400 hover:text-white transition">Cancel</a>
            </div>
        </form>
    </div>
@endsection
