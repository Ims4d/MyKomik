@extends('layouts.dashboard')

@section('title', 'Create Comic')
@section('page-title', 'Create New Comic')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Comic Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('dashboard.comics.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('title') is-invalid @enderror" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title') }}" 
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="author" class="form-label">Author</label>
                            <input type="text" 
                                   class="form-control @error('author') is-invalid @enderror" 
                                   id="author" 
                                   name="author" 
                                   value="{{ old('author') }}">
                            @error('author')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="synopsis" class="form-label">Synopsis</label>
                            <textarea class="form-control @error('synopsis') is-invalid @enderror" 
                                      id="synopsis" 
                                      name="synopsis" 
                                      rows="5">{{ old('synopsis') }}</textarea>
                            @error('synopsis')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" 
                                    id="status" 
                                    name="status" 
                                    required>
                                <option value="ongoing" {{ old('status') === 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                                <option value="completed" {{ old('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="hiatus" {{ old('status') === 'hiatus' ? 'selected' : '' }}>Hiatus</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Genres</label>
                            <div class="row">
                                @foreach($genres as $genre)
                                    <div class="col-md-4 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" 
                                                   type="checkbox" 
                                                   name="genres[]" 
                                                   value="{{ $genre->genre_id }}" 
                                                   id="genre{{ $genre->genre_id }}"
                                                   {{ in_array($genre->genre_id, old('genres', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="genre{{ $genre->genre_id }}">
                                                {{ $genre->name }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="cover_image" class="form-label">Cover Image</label>
                            <input type="file" 
                                   class="form-control @error('cover_image') is-invalid @enderror" 
                                   id="cover_image" 
                                   name="cover_image" 
                                   accept="image/*">
                            <small class="text-muted">Recommended: 300x400px, Max: 2MB</small>
                            @error('cover_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create Comic
                            </button>
                            <a href="{{ route('dashboard.comics.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header bg-white">
                    <h6 class="mb-0"><i class="fas fa-info-circle"></i> Instructions</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-check text-success"></i> Fill in the required fields marked with *</li>
                        <li class="mb-2"><i class="fas fa-check text-success"></i> Select appropriate genres for the comic</li>
                        <li class="mb-2"><i class="fas fa-check text-success"></i> Upload a cover image (recommended)</li>
                        <li class="mb-2"><i class="fas fa-check text-success"></i> After creating, you can add chapters</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
