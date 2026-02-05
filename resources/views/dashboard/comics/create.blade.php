@extends('layouts.dashboard')

@section('title', 'Create Comic')
@section('page-title', 'Create New Comic')

@section('content')
<div class="flex justify-end mb-6">
    <a href="{{ route('dashboard.comics.index') }}" class="px-4 py-2 bg-neutral-700 hover:bg-neutral-600 text-white font-semibold rounded-lg shadow-md transition flex items-center gap-2">
        <i class="fas fa-arrow-left"></i> Back to All Comics
    </a>
</div>

<form action="{{ route('dashboard.comics.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Form -->
        <div class="lg:col-span-2 space-y-6">
            <div class="p-6 bg-neutral-800/50 border border-neutral-700 rounded-xl">
                <h3 class="text-xl font-bold text-white mb-6 border-b border-neutral-700 pb-4">Comic Information</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="title" class="block text-sm font-medium text-neutral-300 mb-2">Title *</label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}" required
                               class="w-full px-4 py-3 bg-neutral-700 border {{ $errors->has('title') ? 'border-red-500' : 'border-neutral-600' }} rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500">
                        @error('title')<p class="text-red-400 text-sm mt-2">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="author" class="block text-sm font-medium text-neutral-300 mb-2">Author</label>
                        <input type="text" id="author" name="author" value="{{ old('author') }}"
                               class="w-full px-4 py-3 bg-neutral-700 border {{ $errors->has('author') ? 'border-red-500' : 'border-neutral-600' }} rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500">
                        @error('author')<p class="text-red-400 text-sm mt-2">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="mt-6">
                    <label for="synopsis" class="block text-sm font-medium text-neutral-300 mb-2">Synopsis</label>
                    <textarea id="synopsis" name="synopsis" rows="6"
                              class="w-full px-4 py-3 bg-neutral-700 border {{ $errors->has('synopsis') ? 'border-red-500' : 'border-neutral-600' }} rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500">{{ old('synopsis') }}</textarea>
                    @error('synopsis')<p class="text-red-400 text-sm mt-2">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="p-6 bg-neutral-800/50 border border-neutral-700 rounded-xl">
                <h3 class="text-xl font-bold text-white mb-6 border-b border-neutral-700 pb-4">Genres & Status</h3>
                <div>
                    <label class="block text-sm font-medium text-neutral-300 mb-4">Genres *</label>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach($genres as $genre)
                            <label for="genre{{ $genre->genre_id }}" class="flex items-center gap-2 text-neutral-200 cursor-pointer hover:bg-neutral-700 p-2 rounded-lg transition">
                                <input type="checkbox" name="genres[]" value="{{ $genre->genre_id }}" id="genre{{ $genre->genre_id }}"
                                       class="h-5 w-5 bg-neutral-600 border-neutral-500 rounded text-sky-500 focus:ring-sky-500"
                                       {{ in_array($genre->genre_id, old('genres', [])) ? 'checked' : '' }}>
                                <span>{{ $genre->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('genres')<p class="text-red-400 text-sm mt-4">{{ $message }}</p>@enderror
                </div>
                <div class="mt-6">
                    <label for="status" class="block text-sm font-medium text-neutral-300 mb-2">Status *</label>
                    <select id="status" name="status" required
                            class="w-full px-4 py-3 bg-neutral-700 border {{ $errors->has('status') ? 'border-red-500' : 'border-neutral-600' }} rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500">
                        <option value="ongoing" {{ old('status') === 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                        <option value="completed" {{ old('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="hiatus" {{ old('status') === 'hiatus' ? 'selected' : '' }}>Hiatus</option>
                    </select>
                    @error('status')<p class="text-red-400 text-sm mt-2">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <div class="p-6 bg-neutral-800/50 border border-neutral-700 rounded-xl">
                <h3 class="text-xl font-bold text-white mb-6 border-b border-neutral-700 pb-4">Cover Image</h3>
                
                <div class="w-full h-64 bg-neutral-700/50 rounded-lg flex items-center justify-center border-2 border-dashed border-neutral-600 mb-4">
                    <img id="cover-preview" src="" alt="Cover preview" class="w-full h-full object-contain rounded-lg hidden">
                    <div id="cover-placeholder" class="text-center text-neutral-400">
                        <i class="fas fa-image fa-3x"></i>
                        <p class="mt-2">Image Preview</p>
                    </div>
                </div>

                <input type="file" id="cover_image" name="cover_image" accept="image/*" class="hidden">
                <label for="cover_image" class="w-full cursor-pointer px-4 py-2 bg-sky-600 hover:bg-sky-500 text-white font-semibold rounded-lg shadow-md transition flex items-center justify-center gap-2">
                    <i class="fas fa-upload"></i> Choose a file
                </label>
                <small class="block text-center text-neutral-400 mt-2">Max: 2MB. Recommended: 300x400px</small>
                @error('cover_image')<p class="text-red-400 text-sm mt-2 text-center">{{ $message }}</p>@enderror
            </div>

            <div class="p-6 bg-neutral-800/50 border border-neutral-700 rounded-xl">
                <h3 class="text-xl font-bold text-white mb-6 border-b border-neutral-700 pb-4">Actions</h3>
                <div class="flex flex-col gap-4">
                    <button type="submit" class="w-full px-6 py-3 bg-green-600 hover:bg-green-500 text-white font-bold rounded-lg shadow-lg transition flex items-center justify-center gap-2">
                        <i class="fas fa-save"></i> Create Comic
                    </button>
                    <a href="{{ route('dashboard.comics.index') }}" class="w-full text-center px-6 py-3 bg-neutral-600 hover:bg-neutral-500 text-white font-bold rounded-lg transition">Cancel</a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    document.getElementById('cover_image').addEventListener('change', function(event) {
        const preview = document.getElementById('cover-preview');
        const placeholder = document.getElementById('cover-placeholder');
        const file = event.target.files[0];
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
            }
            reader.readAsDataURL(file);
        } else {
            preview.src = '';
            preview.classList.add('hidden');
            placeholder.classList.remove('hidden');
        }
    });
</script>
@endpush