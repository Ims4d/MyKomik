@extends('layouts.dashboard')

@section('title', 'Edit Comic')
@section('page-title', 'Edit Comic: ' . $comic->title)

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <form action="{{ route('dashboard.comics.update', $comic->comic_id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label for="title" class="form-label">Title *</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $comic->title) }}" required>
                            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="author" class="form-label">Author</label>
                            <input type="text" class="form-control" id="author" name="author" value="{{ old('author', $comic->author) }}">
                        </div>

                        <div class="mb-3">
                            <label for="synopsis" class="form-label">Synopsis</label>
                            <textarea class="form-control" id="synopsis" name="synopsis" rows="5">{{ old('synopsis', $comic->synopsis) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status *</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="ongoing" {{ old('status', $comic->status) === 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                                <option value="completed" {{ old('status', $comic->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="hiatus" {{ old('status', $comic->status) === 'hiatus' ? 'selected' : '' }}>Hiatus</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Genres</label>
                            <div class="row">
                                @foreach($genres as $genre)
                                    <div class="col-md-4 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="genres[]" value="{{ $genre->genre_id }}" id="genre{{ $genre->genre_id }}" 
                                                {{ $comic->genres->contains($genre->genre_id) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="genre{{ $genre->genre_id }}">{{ $genre->name }}</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Current Cover</label>
                            @if($comic->cover_image_url)
                                <img src="{{ $comic->cover_image_url }}" alt="{{ $comic->title }}" class="img-fluid rounded mb-2">
                            @else
                                <div class="bg-secondary text-white text-center p-5 rounded mb-2">No Cover</div>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="cover_image" class="form-label">New Cover Image</label>
                            <input type="file" class="form-control" id="cover_image" name="cover_image" accept="image/*">
                            <small class="text-muted">Leave empty to keep current cover</small>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update Comic</button>
                    <a href="{{ route('dashboard.comics.index') }}" class="btn btn-secondary"><i class="fas fa-times"></i> Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
