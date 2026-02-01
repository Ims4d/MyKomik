@extends('layouts.app')

@section('title', $comic->title)

@push('styles')
<style>
    .rating {
        display: inline-block;
        direction: rtl;
    }

    .rating > input {
        display: none;
    }

    .rating > label {
        font-size: 2rem;
        color: #ddd;
        cursor: pointer;
    }

    .rating > input:checked ~ label,
    .rating:not(:checked) > label:hover,
    .rating:not(:checked) > label:hover ~ label {
        color: #f7d106;
    }
</style>
@endpush

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-md-4">
            <img src="{{ asset($comic->cover_image_url) }}" alt="{{ $comic->title }}" class="img-fluid rounded shadow">
        </div>
        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h1 class="font-weight-bold">{{ $comic->title }}</h1>
                    <p class="text-muted">by {{ $comic->author }}</p>
                </div>
                <div class="d-flex align-items-center">
                    <span class="badge bg-primary me-2">
                        <i class="fas fa-star"></i> {{ number_format($averageRating, 1) }}
                    </span>
                    <span class="text-muted">({{ $comic->ratings->count() }} ratings)</span>
                </div>
            </div>
            
            <p class="mt-4">{{ $comic->synopsis }}</p>

            <div class="mt-4">
                @foreach($comic->genres as $genre)
                    <span class="badge bg-secondary">{{ $genre->name }}</span>
                @endforeach
            </div>
            
            <div class="mt-4">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ratingModal">
                    @if($userRating)
                        Edit Rating
                    @else
                        Rate this comic
                    @endif
                </button>
            </div>
        </div>
    </div>

    <!-- Rating Modal -->
    <div class="modal fade" id="ratingModal" tabindex="-1" aria-labelledby="ratingModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ratingModalLabel">Rate {{ $comic->title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('comics.rate', $comic) }}" method="POST">
                    @csrf
                    <div class="modal-body text-center">
                        <div class="rating">
                            @for ($i = 5; $i >= 1; $i--)
                                <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" {{ $userRating && $userRating->rating_value == $i ? 'checked' : '' }} />
                                <label for="star{{ $i }}" title="{{ $i }} stars"><i class="fas fa-star"></i></label>
                            @endfor
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit Rating</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-12">
            <h3 class="font-weight-bold">Chapters</h3>
            <ul class="list-group mt-3">
                @forelse($comic->chapters as $chapter)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="{{ route('chapters.show', [$comic, $chapter]) }}">Chapter {{ $chapter->chapter_number }}: {{ $chapter->title }}</a>
                        <span class="text-muted">{{ $chapter->release_date ? $chapter->release_date->format('M d, Y') : 'N/A' }}</span>
                    </li>
                @empty
                    <li class="list-group-item text-muted">No chapters available yet.</li>
                @endforelse
            </ul>
        </div>
    </div>
    
    <div class="row mt-5">
        <div class="col-12">
            <h3 class="font-weight-bold">Comments</h3>
            <div class="card mt-3">
                <div class="card-body">
                    <form action="{{ route('comics.comments.store', $comic) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <textarea name="body" id="body" rows="3" class="form-control" placeholder="Leave a comment..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Post Comment</button>
                    </form>
                </div>
            </div>

            <div class="mt-4">
                @forelse ($comic->comments as $comment)
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title">{{ $comment->user->display_name ?? $comment->user->username }}</h5>
                                <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                            </div>
                            <p class="card-text">{{ $comment->comment_text }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-muted">No comments yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
