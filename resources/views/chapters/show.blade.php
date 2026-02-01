@extends('layouts.app')

@section('title', $comic->title . ' - Chapter ' . $chapter->chapter_number)

@section('content')
<div class="container my-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('comics.show', $comic) }}">{{ $comic->title }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">Chapter {{ $chapter->chapter_number }}</li>
        </ol>
    </nav>

    <h1 class="text-center">{{ $chapter->title ?? 'Chapter ' . $chapter->chapter_number }}</h1>
    <p class="text-center text-muted">{{ $comic->title }}</p>

    <div class="row mt-4">
        <div class="col-12 text-center">
            @forelse ($chapter->pages as $page)
                <img src="{{ asset($page->image_url) }}" class="img-fluid mb-3" alt="Page {{ $page->page_number }}">
            @empty
                <p class="text-muted">No pages available for this chapter.</p>
            @endforelse
        </div>
    </div>

    {{-- Chapter navigation (placeholder for now) --}}
    <div class="d-flex justify-content-between mt-4">
        @if ($previousChapter)
            <a href="{{ route('chapters.show', [$comic, $previousChapter]) }}" class="btn btn-secondary">Previous Chapter</a>
        @else
            <button class="btn btn-secondary" disabled>Previous Chapter</button>
        @endif

        @if ($nextChapter)
            <a href="{{ route('chapters.show', [$comic, $nextChapter]) }}" class="btn btn-primary">Next Chapter</a>
        @else
            <button class="btn btn-primary" disabled>Next Chapter</button>
        @endif
    </div>
</div>
@endsection
