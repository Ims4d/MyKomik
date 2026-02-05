@extends('layouts.dashboard')

@section('title', 'Genres Management')
@section('page-title', 'Genres Management')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-white">All Genres</h2>
        <a href="{{ route('dashboard.genres.create') }}" class="px-4 py-2 bg-sky-600 hover:bg-sky-500 text-white font-semibold rounded-lg shadow-md transition flex items-center gap-2">
            <i class="fas fa-plus"></i> Add New Genre
        </a>
    </div>

    @if($genres->count() > 0)
        <div class="bg-neutral-800/50 border border-neutral-700 rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-neutral-300">
                    <thead class="text-xs text-neutral-400 uppercase bg-neutral-700/50">
                        <tr>
                            <th scope="col" class="px-6 py-3">ID</th>
                            <th scope="col" class="px-6 py-3">Name</th>
                            <th scope="col" class="px-6 py-3">Comics Count</th>
                            <th scope="col" class="px-6 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($genres as $genre)
                            <tr class="border-b border-neutral-700 hover:bg-neutral-700/40">
                                <td class="px-6 py-4 font-medium text-neutral-100">{{ $genre->genre_id }}</td>
                                <td class="px-6 py-4"><strong>{{ $genre->name }}</strong></td>
                                <td class="px-6 py-4">
                                    <span class="text-xs font-semibold px-2 py-1 rounded-full bg-sky-500/20 text-sky-300">
                                        {{ $genre->comics_count }} comics
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end items-center gap-2">
                                        <a href="{{ route('dashboard.genres.edit', $genre->genre_id) }}" class="text-yellow-400 hover:text-yellow-300 transition px-2 py-1" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('dashboard.genres.destroy', $genre->genre_id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this genre? This action cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-400 transition px-2 py-1" title="Delete">
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
        <div class="mt-6">
            {{-- Basic styling for pagination is needed. You can publish Laravel's pagination views to fully customize. --}}
            <div class="p-4 bg-neutral-800/50 border border-neutral-700 rounded-xl">
                 {{ $genres->links() }}
            </div>
        </div>
    @else
        <div class="text-center py-16 bg-neutral-800/50 border border-neutral-700 rounded-xl">
            <i class="fas fa-tags fa-4x text-neutral-500 mb-4"></i>
            <h3 class="text-xl font-semibold text-white">No Genres Found</h3>
            <p class="text-neutral-400 mt-2 mb-6">It looks like you haven't added any genres yet.</p>
            <a href="{{ route('dashboard.genres.create') }}" class="px-4 py-2 bg-sky-600 hover:bg-sky-500 text-white font-semibold rounded-lg shadow-md transition flex items-center gap-2 mx-auto max-w-max">
                <i class="fas fa-plus"></i> Add Your First Genre
            </a>
        </div>
    @endif
@endsection