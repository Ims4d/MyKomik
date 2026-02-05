@extends('layouts.dashboard')

@section('title', 'Comics Management')
@section('page-title', 'Comics Management')

@section('content')
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <h2 class="text-2xl font-bold text-white">All Comics</h2>
        <a href="{{ route('dashboard.comics.create') }}" class="px-4 py-2 bg-sky-600 hover:bg-sky-500 text-white font-semibold rounded-lg shadow-md transition flex items-center gap-2">
            <i class="fas fa-plus"></i> Add New Comic
        </a>
    </div>

    <!-- Search & Filter -->
    <div class="mb-6 bg-neutral-800/50 border border-neutral-700 rounded-xl p-4">
        <form action="{{ route('dashboard.comics.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-center">
            <div class="md:col-span-2">
                <label for="search" class="sr-only">Search</label>
                <input type="text" name="search" id="search" class="w-full px-4 py-2 bg-neutral-700 border border-neutral-600 rounded-lg placeholder-neutral-400 text-white focus:outline-none focus:ring-2 focus:ring-sky-500" placeholder="Search by title or author..." value="{{ request('search') }}">
            </div>
            <div>
                <label for="status" class="sr-only">Status</label>
                <select name="status" id="status" class="w-full px-4 py-2 bg-neutral-700 border border-neutral-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-sky-500">
                    <option value="">All Status</option>
                    <option value="ongoing" {{ request('status') === 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="hiatus" {{ request('status') === 'hiatus' ? 'selected' : '' }}>Hiatus</option>
                </select>
            </div>
            <button type="submit" class="w-full px-4 py-2 bg-neutral-600 hover:bg-neutral-500 text-white font-semibold rounded-lg transition flex items-center justify-center gap-2">
                <i class="fas fa-search"></i> Search
            </button>
        </form>
    </div>

    @if($comics->count() > 0)
        <div class="bg-neutral-800/50 border border-neutral-700 rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-neutral-300">
                    <thead class="text-xs text-neutral-400 uppercase bg-neutral-700/50">
                        <tr>
                            <th scope="col" class="px-6 py-3">Cover</th>
                            <th scope="col" class="px-6 py-3">Title</th>
                            <th scope="col" class="px-6 py-3">Author</th>
                            <th scope="col" class="px-6 py-3">Details</th>
                            <th scope="col" class="px-6 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($comics as $comic)
                            <tr class="border-b border-neutral-700 hover:bg-neutral-700/40 align-top">
                                <td class="px-6 py-4">
                                    <img src="{{ $comic->cover_image_url ?? asset('images/default-cover.png') }}" alt="Cover for {{ $comic->title }}" class="w-16 h-24 object-cover rounded-md border border-neutral-600">
                                </td>
                                <td class="px-6 py-4">
                                    <strong class="text-white text-base">{{ $comic->title }}</strong>
                                    <div class="text-xs text-neutral-400">ID: {{ $comic->comic_id }}</div>
                                </td>
                                <td class="px-6 py-4">{{ $comic->author ?? 'N/A' }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col gap-2">
                                        <span class="text-xs font-semibold px-2 py-1 rounded-full max-w-max
                                            @switch($comic->status)
                                                @case('ongoing') bg-green-500/20 text-green-300 @break
                                                @case('completed') bg-sky-500/20 text-sky-300 @break
                                                @default bg-yellow-500/20 text-yellow-300 @break
                                            @endswitch">
                                            {{ ucfirst($comic->status) }}
                                        </span>
                                        <span class="text-xs font-semibold px-2 py-1 rounded-full bg-neutral-600/50 text-neutral-300 max-w-max">{{ $comic->totalChapters() }} Chapters</span>
                                        <div class="flex flex-wrap gap-1 mt-1">
                                            @foreach($comic->genres->take(3) as $genre)
                                                <span class="text-xs font-semibold px-2 py-1 rounded-full bg-indigo-500/20 text-indigo-300">{{ $genre->name }}</span>
                                            @endforeach
                                            @if($comic->genres->count() > 3)
                                                 <span class="text-xs font-semibold px-2 py-1 rounded-full bg-neutral-700 text-neutral-400">+{{ $comic->genres->count() - 3 }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end items-center gap-2">
                                        <a href="{{ route('dashboard.chapters.index', $comic->comic_id) }}" class="text-blue-400 hover:text-blue-300 transition px-3 py-2 bg-blue-500/10 rounded-lg" title="Manage Chapters">
                                            <i class="fas fa-list"></i>
                                        </a>
                                        <a href="{{ route('dashboard.comics.edit', $comic->comic_id) }}" class="text-yellow-400 hover:text-yellow-300 transition px-3 py-2 bg-yellow-500/10 rounded-lg" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('dashboard.comics.destroy', $comic->comic_id) }}" method="POST" onsubmit="return confirm('Are you sure? This will delete the comic and all its chapters.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-400 transition px-3 py-2 bg-red-500/10 rounded-lg" title="Delete">
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
             {{ $comics->appends(request()->query())->links() }}
        </div>
    @else
        <div class="text-center py-16 bg-neutral-800/50 border border-neutral-700 rounded-xl">
            <i class="fas fa-book fa-4x text-neutral-500 mb-4"></i>
            <h3 class="text-xl font-semibold text-white">No Comics Found</h3>
            <p class="text-neutral-400 mt-2 mb-6">Your search returned no results, or you haven't added any comics yet.</p>
            <a href="{{ route('dashboard.comics.create') }}" class="px-4 py-2 bg-sky-600 hover:bg-sky-500 text-white font-semibold rounded-lg shadow-md transition flex items-center gap-2 mx-auto max-w-max">
                <i class="fas fa-plus"></i> Add Your First Comic
            </a>
        </div>
    @endif
@endsection