@extends('layouts.dashboard')

@section('title', 'Add Chapter')
@section('page-title', 'Add New Chapter to: ' . $comic->title)

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <form action="{{ route('dashboard.chapters.store', $comic->comic_id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="chapter_number" class="form-label">Chapter Number *</label>
                            <input type="number" class="form-control @error('chapter_number') is-invalid @enderror" id="chapter_number" name="chapter_number" value="{{ old('chapter_number', $nextChapterNumber) }}" min="1" required>
                            @error('chapter_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="title" class="form-label">Chapter Title</label>
                            <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}">
                        </div>

                        <div class="mb-3">
                            <label for="release_date" class="form-label">Release Date</label>
                            <input type="date" class="form-control" id="release_date" name="release_date" value="{{ old('release_date', date('Y-m-d')) }}">
                        </div>

                        <div class="mb-3">
                            <label for="pages" class="form-label">Upload Pages * <small class="text-muted">(Select multiple images)</small></label>
                            <input type="file" class="form-control @error('pages') is-invalid @enderror" id="pages" name="pages[]" accept="image/*" multiple required>
                            <small class="text-muted">Hold Ctrl/Cmd to select multiple files. Max 5MB per image.</small>
                            @error('pages')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="alert alert-info">
                            <h6><i class="fas fa-info-circle"></i> Instructions:</h6>
                            <ul>
                                <li>Select all page images for this chapter</li>
                                <li>Images will be numbered automatically in the order selected</li>
                                <li>Supported formats: JPG, PNG, GIF</li>
                                <li>Recommended size: 800x1200px for best reading experience</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Create Chapter</button>
                    <a href="{{ route('dashboard.chapters.index', $comic->comic_id) }}" class="btn btn-secondary"><i class="fas fa-times"></i> Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
