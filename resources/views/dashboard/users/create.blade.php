@extends('layouts.dashboard')

@section('title', 'Create User')
@section('page-title', 'Create New User')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-white">Create a New User</h2>
        <a href="{{ route('dashboard.users.index') }}" class="px-4 py-2 bg-neutral-700 hover:bg-neutral-600 text-white font-semibold rounded-lg shadow-md transition flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Back to All Users
        </a>
    </div>

    <div class="max-w-2xl mx-auto">
        <form action="{{ route('dashboard.users.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="username" class="block text-sm font-medium text-neutral-300 mb-2">Username *</label>
                    <input type="text" id="username" name="username" value="{{ old('username') }}" required
                           class="w-full px-4 py-3 bg-neutral-700 border {{ $errors->has('username') ? 'border-red-500' : 'border-neutral-600' }} rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500">
                    @error('username')<p class="text-red-400 text-sm mt-2">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="display_name" class="block text-sm font-medium text-neutral-300 mb-2">Display Name</label>
                    <input type="text" id="display_name" name="display_name" value="{{ old('display_name') }}"
                           class="w-full px-4 py-3 bg-neutral-700 border {{ $errors->has('display_name') ? 'border-red-500' : 'border-neutral-600' }} rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500">
                    @error('display_name')<p class="text-red-400 text-sm mt-2">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-neutral-300 mb-2">Email Address *</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                       class="w-full px-4 py-3 bg-neutral-700 border {{ $errors->has('email') ? 'border-red-500' : 'border-neutral-600' }} rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500">
                @error('email')<p class="text-red-400 text-sm mt-2">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="password" class="block text-sm font-medium text-neutral-300 mb-2">Password *</label>
                    <input type="password" id="password" name="password" required
                           class="w-full px-4 py-3 bg-neutral-700 border {{ $errors->has('password') ? 'border-red-500' : 'border-neutral-600' }} rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500">
                    @error('password')<p class="text-red-400 text-sm mt-2">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-neutral-300 mb-2">Confirm Password *</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                           class="w-full px-4 py-3 bg-neutral-700 border rounded-lg bg-neutral-700 border-neutral-600 focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>
            </div>

            <div>
                <label for="role" class="block text-sm font-medium text-neutral-300 mb-2">Role *</label>
                <select id="role" name="role" required
                        class="w-full px-4 py-3 bg-neutral-700 border {{ $errors->has('role') ? 'border-red-500' : 'border-neutral-600' }} rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500">
                    <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>User</option>
                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
                @error('role')<p class="text-red-400 text-sm mt-2">{{ $message }}</p>@enderror
            </div>


            <div class="flex items-center gap-4 pt-4">
                <button type="submit" class="px-6 py-3 bg-sky-600 hover:bg-sky-500 text-white font-bold rounded-lg shadow-lg transition flex items-center gap-2">
                    <i class="fas fa-save"></i> Create User
                </button>
                <a href="{{ route('dashboard.users.index') }}" class="text-neutral-400 hover:text-white transition">Cancel</a>
            </div>
        </form>
    </div>
@endsection
