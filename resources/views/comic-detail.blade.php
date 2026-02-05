@extends('layouts.app')

@section('title', $comic->title . ' - Comic Reader')

@section('content')
<!-- Comic Detail Header -->
<div class="relative bg-gradient-to-b from-neutral-900/90 to-neutral-900/95 text-white py-16 mb-10"
     style="background-image: linear-gradient(to bottom, rgba(23, 23, 23, 0.9), rgba(23, 23, 23, 0.95)), url('{{ $comic->cover_image_url }}'); background-size: cover; background-position: center;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <div class="lg:col-span-3 text-center lg:text-left">
                @if($comic->cover_image_url)
                    <img src="{{ $comic->cover_image_url }}"
                         alt="{{ $comic->title }}"
                         class="w-full max-w-xs mx-auto lg:mx-0 rounded-xl shadow-2xl">
                @else
                    <div class="w-full max-w-xs mx-auto lg:mx-0 h-96 bg-neutral-700 rounded-xl flex items-center justify-center">
                        <i class="fas fa-book text-6xl text-neutral-500"></i>
                    </div>
                @endif
            </div>

            <div class="lg:col-span-9">
                <h1 class="text-4xl lg:text-5xl font-bold mb-4">{{ $comic->title }}</h1>

                @if($comic->author)
                    <p class="text-xl mb-4 text-neutral-300">
                        <i class="fas fa-user mr-2"></i>{{ $comic->author }}
                    </p>
                @endif

                <!-- Genres -->
                <div class="flex flex-wrap gap-2 mb-4">
                    @foreach($comic->genres as $genre)
                        <span class="px-3 py-1 bg-sky-600 text-white rounded-full text-sm">{{ $genre->name }}</span>
                    @endforeach
                </div>

                <!-- Status & Info -->
                <div class="flex flex-wrap gap-4 mb-4 text-sm lg:text-base">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-info-circle"></i>
                        <span class="px-3 py-1 rounded {{ $comic->status === 'ongoing' ? 'bg-green-600' : ($comic->status === 'completed' ? 'bg-sky-600' : 'bg-yellow-600') }}">
                            {{ ucfirst($comic->status) }}
                        </span>
                    </div>
                    <div>
                        <i class="fas fa-list mr-1"></i>{{ $comic->totalChapters() }} Chapters
                    </div>
                    <div>
                        <i class="fas fa-calendar mr-1"></i>{{ $comic->created_at->format('M d, Y') }}
                    </div>
                </div>

                <!-- Rating -->
                <div class="mb-6">
                    <i class="fas fa-star text-yellow-400 mr-1"></i>
                    <strong class="text-2xl">{{ number_format($averageRating, 1) }}</strong>
                    <span class="text-neutral-300">/ 5.0</span>
                    <small class="text-neutral-400 ml-2">({{ $totalRatings }} ratings)</small>
                </div>

                <!-- Action Buttons -->
                @if($comic->chapters->count() > 0)
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('chapter.read', [$comic->comic_id, $comic->chapters->first()->chapter_id]) }}"
                           class="inline-flex items-center px-6 py-3 bg-sky-600 hover:bg-sky-700 text-white font-semibold rounded-lg transition shadow-lg">
                            <i class="fas fa-book-open mr-2"></i> Start Reading
                        </a>
                        @if($comic->chapters->last())
                            <a href="{{ route('chapter.read', [$comic->comic_id, $comic->chapters->last()->chapter_id]) }}"
                               class="inline-flex items-center px-6 py-3 bg-transparent hover:bg-white/10 text-white font-semibold rounded-lg border-2 border-white transition">
                                <i class="fas fa-forward mr-2"></i> Latest Chapter
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-8 space-y-6">
            <!-- Synopsis -->
            <div class="bg-neutral-800 rounded-xl shadow-lg border border-neutral-700">
                <div class="px-6 py-4 bg-sky-600 text-white rounded-t-xl">
                    <h5 class="text-xl font-bold flex items-center gap-2">
                        <i class="fas fa-align-left"></i> Synopsis
                    </h5>
                </div>
                <div class="p-6">
                    @if($comic->synopsis)
                        <p class="text-neutral-300 whitespace-pre-line">{{ $comic->synopsis }}</p>
                    @else
                        <p class="text-neutral-500 italic">No synopsis available.</p>
                    @endif
                </div>
            </div>

            <!-- Chapters List -->
            <div class="bg-neutral-800 rounded-xl shadow-lg border border-neutral-700" id="chapters-list">
                <div class="px-6 py-4 bg-green-600 text-white rounded-t-xl flex justify-between items-center">
                    <h5 class="text-xl font-bold flex items-center gap-2">
                        <i class="fas fa-list"></i> Chapters
                    </h5>
                    <span class="px-3 py-1 bg-white/20 rounded-full text-sm">{{ $comic->chapters->count() }} chapters</span>
                </div>
                <div class="p-6">
                    @if($comic->chapters->count() > 0)
                        <div class="space-y-2">
                            @foreach($comic->chapters as $chapter)
                                <a href="{{ route('chapter.read', [$comic->comic_id, $chapter->chapter_id]) }}"
                                   class="block p-4 bg-neutral-700/50 hover:bg-neutral-700 border border-neutral-600 rounded-lg transition group">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <h6 class="font-semibold text-white mb-1 group-hover:text-sky-400 transition">
                                                <i class="fas fa-book-open text-sky-500 mr-2"></i>
                                                Chapter {{ $chapter->chapter_number }}
                                                @if($chapter->title)
                                                    - {{ $chapter->title }}
                                                @endif
                                            </h6>
                                            <small class="text-neutral-400 flex flex-wrap gap-3">
                                                <span><i class="far fa-calendar mr-1"></i>{{ $chapter->release_date ? $chapter->release_date->format('M d, Y') : 'N/A' }}</span>
                                                <span><i class="fas fa-images mr-1"></i>{{ $chapter->totalPages() }} pages</span>
                                            </small>
                                        </div>
                                        <i class="fas fa-chevron-right text-neutral-500 group-hover:text-sky-500 transition"></i>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-inbox text-6xl text-neutral-600 mb-4"></i>
                            <p class="text-neutral-400">No chapters available yet.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Comments Section -->
            <div class="bg-neutral-800 rounded-xl shadow-lg border border-neutral-700">
                <div class="px-6 py-4 bg-purple-600 text-white rounded-t-xl">
                    <h5 class="text-xl font-bold flex items-center gap-2">
                        <i class="fas fa-comments"></i> Comments
                        <span class="px-3 py-1 bg-white/20 rounded-full text-sm">{{ $comments->total() }}</span>
                    </h5>
                </div>
                <div class="p-6">
                    @auth
                        <!-- Comment Form -->
                        <div class="mb-6 bg-neutral-700/50 rounded-lg p-4 border border-neutral-600">
                            <h6 class="font-semibold text-white mb-3">Leave a Comment</h6>
                            <form action="{{ route('comic.comment.store', $comic->comic_id) }}" method="POST">
                                @csrf

                                <div class="mb-3">
                                    <label for="chapter_id" class="block text-sm font-medium text-neutral-300 mb-1">
                                        <i class="fas fa-book mr-1"></i> Select Chapter
                                    </label>
                                    <select class="block w-full px-3 py-2 rounded-lg border-neutral-600 bg-neutral-700 text-white shadow-sm focus:border-sky-500 focus:ring-sky-500 @error('chapter_id') border-red-500 @enderror"
                                            id="chapter_id"
                                            name="chapter_id"
                                            required>
                                        <option value="" selected disabled>Choose a chapter...</option>
                                        @foreach($comic->chapters as $chapter)
                                            <option value="{{ $chapter->chapter_id }}">
                                                Chapter {{ $chapter->chapter_number }}
                                                @if($chapter->title) - {{ $chapter->title }} @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('chapter_id')
                                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="comment_text" class="block text-sm font-medium text-neutral-300 mb-1">
                                        <i class="fas fa-comment mr-1"></i> Your Comment
                                    </label>
                                    <textarea class="block w-full px-3 py-2 rounded-lg border-neutral-600 bg-neutral-700 text-white shadow-sm focus:border-sky-500 focus:ring-sky-500 @error('comment_text') border-red-500 @enderror"
                                              id="comment_text"
                                              name="comment_text"
                                              rows="3"
                                              placeholder="Share your thoughts..."
                                              required></textarea>
                                    @error('comment_text')
                                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-sky-600 hover:bg-sky-500 text-white font-medium rounded-lg transition shadow-md">
                                    <i class="fas fa-paper-plane mr-2"></i> Post Comment
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="bg-neutral-700/50 rounded-lg p-4 border border-neutral-600 mb-6 text-center">
                            <p class="text-neutral-300 mb-3">
                                <i class="fas fa-sign-in-alt mr-2"></i>Please log in to leave a comment
                            </p>
                            <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 bg-sky-600 hover:bg-sky-500 text-white font-medium rounded-lg transition">
                                Login
                            </a>
                        </div>
                    @endauth

                    <!-- Comments List -->
                    @if($comments->count() > 0)
                        <div class="space-y-4">
                            @foreach($comments as $comment)
                                <div class="bg-neutral-700/30 rounded-lg p-4 border border-neutral-600">
                                    <!-- Comment Header -->
                                    <div class="flex items-start gap-3 mb-3">
                                        @if($comment->user->avatar_url)
                                            <img src="{{ $comment->user->avatar_url }}"
                                                 alt="{{ $comment->user->username }}"
                                                 class="w-10 h-10 rounded-full border-2 border-neutral-600">
                                        @else
                                            <div class="w-10 h-10 rounded-full bg-neutral-600 flex items-center justify-center text-white font-bold border-2 border-neutral-500">
                                                {{ strtoupper(substr($comment->user->username, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-1">
                                                <span class="font-semibold text-white">{{ $comment->user->username }}</span>
                                                @if($comment->user->role === 'admin')
                                                    <span class="px-2 py-0.5 bg-red-600 text-white text-xs rounded">Admin</span>
                                                @endif
                                            </div>
                                            <div class="flex flex-wrap gap-2 text-xs text-neutral-400">
                                                <span><i class="far fa-clock mr-1"></i>{{ $comment->created_at->diffForHumans() }}</span>
                                                <span><i class="fas fa-book mr-1"></i>Chapter {{ $comment->chapter->chapter_number }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Comment Text -->
                                    <p class="text-neutral-300 mb-3 whitespace-pre-line">{{ $comment->comment_text }}</p>

                                    <!-- Comment Actions -->
                                    @auth
                                        <button onclick="toggleReplyForm('replyForm{{ $comment->comment_id }}')"
                                                class="text-sky-400 hover:text-sky-300 text-sm font-medium transition">
                                            <i class="fas fa-reply mr-1"></i> Reply
                                        </button>

                                        <!-- Reply Form -->
                                        <div id="replyForm{{ $comment->comment_id }}" class="hidden mt-4 bg-neutral-700/50 rounded-lg p-4 border border-neutral-600">
                                            <form action="{{ route('comic.comment.store', $comic->comic_id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="chapter_id" value="{{ $comment->chapter_id }}">
                                                <input type="hidden" name="parent_comment_id" value="{{ $comment->comment_id }}">
                                                <textarea class="block w-full px-3 py-2 rounded-lg border-neutral-600 bg-neutral-700 text-white shadow-sm focus:border-sky-500 focus:ring-sky-500 mb-3"
                                                          name="comment_text"
                                                          rows="2"
                                                          placeholder="Write your reply..."
                                                          required></textarea>
                                                <div class="flex gap-2">
                                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-sky-600 hover:bg-sky-500 text-white text-sm font-medium rounded-lg transition">
                                                        <i class="fas fa-paper-plane mr-1"></i> Send
                                                    </button>
                                                    <button type="button"
                                                            onclick="toggleReplyForm('replyForm{{ $comment->comment_id }}')"
                                                            class="inline-flex items-center px-3 py-1.5 bg-neutral-600 hover:bg-neutral-500 text-white text-sm font-medium rounded-lg transition">
                                                        Cancel
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    @endauth

                                    <!-- Replies -->
                                    @if($comment->replies->count() > 0)
                                        <div class="mt-4 ml-8 space-y-3">
                                            @foreach($comment->replies as $reply)
                                                <div class="bg-neutral-700/50 rounded-lg p-3 border border-neutral-600">
                                                    <div class="flex items-start gap-3">
                                                        @if($reply->user->avatar_url)
                                                            <img src="{{ $reply->user->avatar_url }}"
                                                                 alt="{{ $reply->user->username }}"
                                                                 class="w-8 h-8 rounded-full border-2 border-neutral-600">
                                                        @else
                                                            <div class="w-8 h-8 rounded-full bg-neutral-600 flex items-center justify-center text-white text-xs font-bold border-2 border-neutral-500">
                                                                {{ strtoupper(substr($reply->user->username, 0, 1)) }}
                                                            </div>
                                                        @endif
                                                        <div class="flex-1">
                                                            <div class="flex items-center gap-2 mb-1">
                                                                <span class="font-semibold text-white text-sm">{{ $reply->user->username }}</span>
                                                                @if($reply->user->role === 'admin')
                                                                    <span class="px-2 py-0.5 bg-red-600 text-white text-xs rounded">Admin</span>
                                                                @endif
                                                            </div>
                                                            <p class="text-neutral-300 text-sm mb-2 whitespace-pre-line">{{ $reply->comment_text }}</p>
                                                            <span class="text-xs text-neutral-400">
                                                                <i class="far fa-clock mr-1"></i>{{ $reply->created_at->diffForHumans() }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $comments->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-comments text-6xl text-neutral-600 mb-4"></i>
                            <p class="text-neutral-400">No comments yet. Be the first to comment!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-4">
            <!-- Rate Comic Card -->
            @auth
                <div class="bg-neutral-800 rounded-xl shadow-lg border border-neutral-700 mb-6">
                    <div class="p-6">
                        <h5 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                            <i class="fas fa-star text-yellow-400"></i> Rate This Comic
                        </h5>
                        @if($userRating)
                            <div class="text-center mb-4">
                                <p class="text-neutral-400 text-sm mb-2">Your rating:</p>
                                <div class="text-3xl mb-3">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $userRating->rating_value ? 'text-yellow-400' : 'text-neutral-600' }}"></i>
                                    @endfor
                                </div>
                                <p class="text-neutral-300 font-semibold">{{ $userRating->rating_value }}/5</p>
                            </div>
                            <button onclick="openRatingModal()" class="w-full py-2 bg-sky-600 hover:bg-sky-500 text-white font-medium rounded-lg transition shadow-md">
                                <i class="fas fa-edit mr-2"></i> Edit Rating
                            </button>
                        @else
                            <p class="text-neutral-400 text-sm text-center mb-4">You haven't rated this comic yet</p>
                            <button onclick="openRatingModal()" class="w-full py-2 bg-sky-600 hover:bg-sky-500 text-white font-medium rounded-lg transition shadow-md">
                                <i class="fas fa-star mr-2"></i> Rate Now
                            </button>
                        @endif
                    </div>
                </div>
            @endauth

            <!-- Comic Info Card -->
            <div class="bg-neutral-800 rounded-xl shadow-lg border border-neutral-700">
                <div class="px-6 py-4 bg-sky-600 text-white rounded-t-xl">
                    <h5 class="text-lg font-bold flex items-center gap-2">
                        <i class="fas fa-info-circle"></i> Comic Info
                    </h5>
                </div>
                <div class="p-6">
                    <table class="w-full text-sm">
                        <tr>
                            <td class="py-2 text-neutral-400"><i class="fas fa-user mr-1"></i> Author</td>
                            <td class="py-2 text-white text-right">{{ $comic->author ?: 'Unknown' }}</td>
                        </tr>
                        <tr>
                            <td class="py-2 text-neutral-400"><i class="fas fa-info-circle mr-1"></i> Status</td>
                            <td class="py-2 text-right">
                                <span class="px-2 py-1 text-xs rounded {{ $comic->status === 'ongoing' ? 'bg-green-600 text-white' : ($comic->status === 'completed' ? 'bg-sky-600 text-white' : 'bg-yellow-600 text-white') }}">
                                    {{ ucfirst($comic->status) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="py-2 text-neutral-400"><i class="fas fa-list mr-1"></i> Chapters</td>
                            <td class="py-2 text-white text-right">{{ $comic->totalChapters() }}</td>
                        </tr>
                        <tr>
                            <td class="py-2 text-neutral-400"><i class="fas fa-star mr-1"></i> Rating</td>
                            <td class="py-2 text-white text-right">{{ number_format($averageRating, 1) }}/5.0</td>
                        </tr>
                        <tr>
                            <td class="py-2 text-neutral-400"><i class="fas fa-calendar mr-1"></i> Added</td>
                            <td class="py-2 text-white text-right">{{ $comic->created_at->format('M d, Y') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Back Button -->
    <div class="mt-8">
        <a href="{{ route('home') }}" class="inline-flex items-center text-neutral-300 hover:text-sky-500 transition">
            <i class="fas fa-arrow-left mr-2"></i> Back to Home
        </a>
    </div>
</div>

<!-- Rating Modal -->
@auth
<div id="ratingModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-neutral-800 rounded-xl shadow-2xl border border-neutral-700 w-full max-w-md">
        <div class="p-6 border-b border-neutral-700 flex justify-between items-center">
            <h5 class="text-xl font-bold text-white">{{ $userRating ? 'Edit Rating' : 'Rate This Comic' }}</h5>
            <button onclick="closeRatingModal()" class="text-neutral-400 hover:text-white transition">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="p-6">
            <div class="text-center mb-6">
                <p class="text-neutral-300 mb-4">How would you rate this comic?</p>
                <div class="flex justify-center items-center gap-2 text-5xl" id="modalRatingStars">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="far fa-star text-neutral-600 hover:text-yellow-400 cursor-pointer transition-all duration-200 hover:scale-110"
                           data-rating="{{ $i }}"
                           onclick="selectRating({{ $i }})"></i>
                    @endfor
                </div>
                <p class="text-neutral-400 text-sm mt-4" id="ratingText">Click a star to rate</p>
            </div>
            <div class="flex gap-3">
                <button onclick="submitRating()"
                        id="submitRatingBtn"
                        class="flex-1 inline-flex justify-center items-center px-4 py-3 bg-sky-600 hover:bg-sky-700 text-white font-medium rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed"
                        disabled>
                    <i class="fas fa-check mr-2"></i> Submit Rating
                </button>
                <button onclick="closeRatingModal()"
                        class="inline-flex items-center px-4 py-3 bg-neutral-700 hover:bg-neutral-600 text-white font-medium rounded-lg transition">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>
@endauth

@push('scripts')
<script>
let selectedRating = {{ $userRating ? $userRating->rating_value : 0 }};

function openRatingModal() {
    document.getElementById('ratingModal').classList.remove('hidden');
    document.getElementById('ratingModal').classList.add('flex');
    if (selectedRating > 0) {
        updateModalStars(selectedRating);
    }
}

function closeRatingModal() {
    document.getElementById('ratingModal').classList.add('hidden');
    document.getElementById('ratingModal').classList.remove('flex');
}

function selectRating(rating) {
    selectedRating = rating;
    updateModalStars(rating);
    document.getElementById('submitRatingBtn').disabled = false;
}

function updateModalStars(rating) {
    const stars = document.querySelectorAll('#modalRatingStars i');
    const ratingTexts = ['', 'Terrible', 'Bad', 'Okay', 'Good', 'Excellent'];

    stars.forEach((star, index) => {
        if (index < rating) {
            star.classList.remove('far', 'text-neutral-600');
            star.classList.add('fas', 'text-yellow-400');
        } else {
            star.classList.remove('fas', 'text-yellow-400');
            star.classList.add('far', 'text-neutral-600');
        }
    });

    document.getElementById('ratingText').textContent = ratingTexts[rating] + ' (' + rating + '/5)';
}

function submitRating() {
    if (selectedRating === 0) {
        alert('Please select a rating first');
        return;
    }

    fetch('{{ route("comic.rate", $comic->comic_id) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ rating: selectedRating })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeRatingModal();
            location.reload();
        }
    })
    .catch(error => {
        alert('Error submitting rating. Please try again.');
    });
}

// Add hover effect to modal stars
document.querySelectorAll('#modalRatingStars i').forEach((star, index) => {
    star.addEventListener('mouseenter', function() {
        const rating = index + 1;
        document.querySelectorAll('#modalRatingStars i').forEach((s, i) => {
            if (i < rating) {
                s.classList.remove('far', 'text-neutral-600');
                s.classList.add('fas', 'text-yellow-400');
            } else {
                s.classList.remove('fas', 'text-yellow-400');
                s.classList.add('far', 'text-neutral-600');
            }
        });
    });

    star.addEventListener('mouseleave', function() {
        if (selectedRating > 0) {
            updateModalStars(selectedRating);
        } else {
            document.querySelectorAll('#modalRatingStars i').forEach(s => {
                s.classList.remove('fas', 'text-yellow-400');
                s.classList.add('far', 'text-neutral-600');
            });
        }
    });
});

// Toggle reply form
function toggleReplyForm(id) {
    const form = document.getElementById(id);
    form.classList.toggle('hidden');
}

// Close modal when clicking outside
document.getElementById('ratingModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeRatingModal();
    }
});
</script>
@endpush
@endsection
