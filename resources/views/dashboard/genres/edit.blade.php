@extends('layouts.dashboard')

@section('title', 'Edit Genre')
@section('page-title', 'Edit Genre')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-white">Editing Genre: <span class="text-sky-400">{{ $genre->name }}</span></h2>
        <a href="{{ route('dashboard.genres.index') }}" class="px-4 py-2 bg-neutral-700 hover:bg-neutral-600 text-white font-semibold rounded-lg shadow-md transition flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Back to All Genres
        </a>
    </div>

    <div class="max-w-xl mx-auto">
        <form action="{{ route('dashboard.genres.update', $genre->genre_id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div>
                <label for="name" class="block text-sm font-medium text-neutral-300 mb-2">Genre Name *</label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       value="{{ old('name', $genre->name) }}" 
                       required
                       class="block w-full px-4 py-3 bg-neutral-700 border 
                              {{ $errors->has('name') ? 'border-red-500' : 'border-neutral-600' }} 
                              rounded-lg placeholder-neutral-400 text-white
                              focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition">
                @error('name')
                    <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-4 pt-4">
                <button type="submit" class="px-6 py-3 bg-sky-600 hover:bg-sky-500 text-white font-bold rounded-lg shadow-lg transition flex items-center gap-2">
                    <i class="fas fa-save"></i> Update Genre
                </button>
                <a href="{{ route('dashboard.genres.index') }}" class="text-neutral-400 hover:text-white transition">Cancel</a>
            </div>
        </form>
    </div>
@endsection