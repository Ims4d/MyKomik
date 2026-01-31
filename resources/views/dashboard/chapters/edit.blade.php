@extends('layouts.dashboard')

@section('title', 'Edit Chapter')
@section('page-title', 'Edit Chapter ' . $chapter->chapter_number)

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <form action="{{ route('dashboard.chapters.update', [$comic->comic_id, $chapter->chapter_id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="chapter_number" class="form-label">Chapter Number *</label>
                            <input type="number" class="form-control" id="chapter_number" name="chapter_number" value="{{ old('chapter_number', $chapter->chapter_number) }}" min="1" required>
                        </div>

                        <div class="mb-3">
                            <label for="title" class="form-label">Chapter Title</label>
                            <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $chapter->title) }}">
                        </div>

                        <div class="mb-3">
                            <label for="release_date" class="form-label">Release Date</label>
                            <input type="date" class="form-control" id="release_date" name="release_date" value="{{ old('release_date', $chapter->release_date ? $chapter->release_date->format('Y-m-d') : '') }}">
                        </div>

                        <div class="mb-3">
                            <label for="new_pages" class="form-label">Add More Pages <small class="text-muted">(Optional)</small></label>
                            <input type="file" class="form-control" id="new_pages" name="new_pages[]" accept="image/*" multiple>
                            <small class="text-muted">Add new pages to the end of this chapter</small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h6>Current Pages ({{ $chapter->totalPages() }} pages):</h6>
                        <div class="row">
                            @foreach($chapter->pages as $page)
                                <div class="col-md-3 mb-3 text-center">
                                    <img src="{{ $page->image_url }}" alt="Page {{ $page->page_number }}" class="img-thumbnail mb-2" style="max-height: 150px;">
                                    <p class="mb-1"><small>Page {{ $page->page_number }}</small></p>
                                    <form action="{{ route('dashboard.pages.destroy', $page->page_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this page?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update Chapter</button>
                    <a href="{{ route('dashboard.chapters.index', $comic->comic_id) }}" class="btn btn-secondary"><i class="fas fa-times"></i> Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
