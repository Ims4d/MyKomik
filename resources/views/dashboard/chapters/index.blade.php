@extends('layouts.dashboard')

@section('title', 'Chapters - ' . $comic->title)
@section('page-title', 'Manage Chapters')

@section('content')
    <!-- Comic Info -->
    <div class="mb-8 p-6 bg-neutral-800/50 border border-neutral-700 rounded-xl">
        <div class="flex flex-col md:flex-row items-start gap-6">
            <img src="{{ $comic->cover_image_url }}" alt="Cover for {{ $comic->title }}" class="w-32 h-48 object-cover rounded-md border border-neutral-600">
            <div class="flex-1">
                <h2 class="text-3xl font-bold text-white mb-1">{{ $comic->title }}</h2>
                <p class="text-neutral-400 text-sm mb-4 max-w-3xl">{{ \Illuminate\Support\Str::limit($comic->synopsis, 150) }}</p>
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-xs font-semibold px-2 py-1 rounded-full bg-neutral-600/50 text-neutral-300">{{ $comic->totalChapters() }} Chapters</span>
                    <span class="text-xs font-semibold px-2 py-1 rounded-full
                        @switch($comic->status)
                            @case('ongoing') bg-green-500/20 text-green-300 @break
                            @case('completed') bg-sky-500/20 text-sky-300 @break
                            @default bg-yellow-500/20 text-yellow-300 @break
                        @endswitch">
                        {{ ucfirst($comic->status) }}
                    </span>
                    @foreach($comic->genres as $genre)
                        <span class="text-xs font-semibold px-2 py-1 rounded-full bg-indigo-500/20 text-indigo-300">{{ $genre->name }}</span>
                    @endforeach
                </div>
            </div>
            <a href="{{ route('dashboard.comics.edit', $comic->comic_id) }}" class="px-4 py-2 bg-neutral-700 hover:bg-neutral-600 text-white font-semibold rounded-lg shadow-md transition flex items-center gap-2">
                <i class="fas fa-edit"></i> Edit Comic
            </a>
        </div>
    </div>

    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <h2 class="text-2xl font-bold text-white">All Chapters</h2>
        <div class="flex items-center gap-4">
            <a href="{{ route('dashboard.chapters.create', $comic->comic_id) }}" class="px-4 py-2 bg-sky-600 hover:bg-sky-500 text-white font-semibold rounded-lg shadow-md transition flex items-center gap-2">
                <i class="fas fa-plus"></i> Add New Chapter
            </a>
            <a href="{{ route('dashboard.comics.index') }}" class="px-4 py-2 bg-neutral-700 hover:bg-neutral-600 text-white font-semibold rounded-lg transition flex items-center gap-2">
                <i class="fas fa-arrow-left"></i> Back to Comics
            </a>
        </div>
    </div>

    @if($chapters->count() > 0)
        <div class="bg-neutral-800/50 border border-neutral-700 rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-neutral-300">
                    <thead class="text-xs text-neutral-400 uppercase bg-neutral-700/50">
                        <tr>
                            <th scope="col" class="px-6 py-3">Chapter #</th>
                            <th scope="col" class="px-6 py-3">Title</th>
                            <th scope="col" class="px-6 py-3">Pages</th>
                            <th scope="col" class="px-6 py-3">Release Date</th>
                            <th scope="col" class="px-6 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($chapters as $chapter)
                            <tr class="border-b border-neutral-700 hover:bg-neutral-700/40">
                                <td class="px-6 py-4 font-bold text-white">Chapter {{ $chapter->chapter_number }}</td>
                                <td class="px-6 py-4">{{ $chapter->title ?? 'N/A' }}</td>
                                <td class="px-6 py-4">
                                    <span class="text-xs font-semibold px-2 py-1 rounded-full bg-blue-500/20 text-blue-300">
                                        {{ $chapter->totalPages() }} pages
                                    </span>
                                </td>
                                <td class="px-6 py-4">{{ $chapter->release_date ? $chapter->release_date->format('M d, Y') : 'Not set' }}</td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end items-center gap-2">
                                        {{-- Future link for managing pages
                                        <a href="#" class="text-green-400 hover:text-green-300 transition px-3 py-2 bg-green-500/10 rounded-lg" title="Manage Pages">
                                            <i class="fas fa-images"></i>
                                        </a>
                                        --}}
                                        <a href="{{ route('dashboard.chapters.edit', [$comic->comic_id, $chapter->chapter_id]) }}" class="text-yellow-400 hover:text-yellow-300 transition px-3 py-2 bg-yellow-500/10 rounded-lg" title="Edit Chapter">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('dashboard.chapters.destroy', [$comic->comic_id, $chapter->chapter_id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this chapter and all its pages?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-400 transition px-3 py-2 bg-red-500/10 rounded-lg" title="Delete Chapter">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-6 p-4 bg-neutral-800/50 border border-neutral-700 rounded-xl">
             {{ $chapters->links() }}
        </div>
    @else
        <div class="text-center py-16 bg-neutral-800/50 border border-neutral-700 rounded-xl">
            <i class="fas fa-list fa-4x text-neutral-500 mb-4"></i>
            <h3 class="text-xl font-semibold text-white">No Chapters Found</h3>
            <p class="text-neutral-400 mt-2 mb-6">This comic doesn't have any chapters yet.</p>
            <a href="{{ route('dashboard.chapters.create', $comic->comic_id) }}" class="px-4 py-2 bg-sky-600 hover:bg-sky-500 text-white font-semibold rounded-lg shadow-md transition flex items-center gap-2 mx-auto max-w-max">
                <i class="fas fa-plus"></i> Add the First Chapter
            </a>
        </div>
    @endif
@endsection