@extends('layouts.dashboard')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@push('styles')
<style>
    .stat-card-icon { font-size: 2.5rem; }
</style>
@endpush

@section('content')
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
        <!-- Total Comics -->
        <div class="bg-neutral-700/50 border border-neutral-700 rounded-xl p-5 border-l-4 border-sky-500">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-neutral-400 mb-1">Total Comics</p>
                    <h3 class="text-3xl font-bold text-white">{{ $stats['total_comics'] }}</h3>
                </div>
                <div class="text-sky-500 stat-card-icon opacity-50">
                    <i class="fas fa-book"></i>
                </div>
            </div>
        </div>

        <!-- Total Chapters -->
        <div class="bg-neutral-700/50 border border-neutral-700 rounded-xl p-5 border-l-4 border-green-500!">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-neutral-400 mb-1">Total Chapters</p>
                    <h3 class="text-3xl font-bold text-white">{{ $stats['total_chapters'] }}</h3>
                </div>
                <div class="text-green-500 stat-card-icon opacity-50">
                    <i class="fas fa-list"></i>
                </div>
            </div>
        </div>

        <!-- Total Users -->
        <div class="bg-neutral-700/50 border border-neutral-700 rounded-xl p-5 border-l-4 border-yellow-500">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-neutral-400 mb-1">Total Users</p>
                    <h3 class="text-3xl font-bold text-white">{{ $stats['total_users'] }}</h3>
                </div>
                <div class="text-yellow-500 stat-card-icon opacity-50">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>

        <!-- Total Ratings -->
        <div class="bg-neutral-700/50 border border-neutral-700 rounded-xl p-5 border-l-4 border-red-500">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-neutral-400 mb-1">Total Ratings</p>
                    <h3 class="text-3xl font-bold text-white">{{ $stats['total_ratings'] }}</h3>
                </div>
                <div class="text-red-500 stat-card-icon opacity-50">
                    <i class="fas fa-star"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Recent Comics -->
        <div class="bg-neutral-800/50 border border-neutral-700 rounded-xl">
            <div class="p-4 border-b border-neutral-700">
                <h5 class="font-semibold text-white flex items-center gap-2"><i class="fas fa-book text-sky-500"></i> Recent Comics</h5>
            </div>
            <div class="p-4">
                @if($recentComics->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentComics as $comic)
                            <div class="flex justify-between items-center">
                                <div>
                                    <h6 class="font-semibold text-neutral-200">{{ $comic->title }}</h6>
                                    <small class="text-neutral-400 text-xs">
                                        by {{ $comic->author ?? 'N/A' }} &bull; {{ $comic->created_at->diffForHumans() }}
                                    </small>
                                </div>
                                <span class="text-xs font-semibold px-2 py-1 rounded-full
                                    @switch($comic->status)
                                        @case('ongoing') bg-green-500/20 text-green-300 @break
                                        @case('completed') bg-sky-500/20 text-sky-300 @break
                                        @default bg-yellow-500/20 text-yellow-300 @break
                                    @endswitch">
                                    {{ ucfirst($comic->status) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-neutral-400 text-center py-8">No comics found.</p>
                @endif
            </div>
            <div class="p-4 border-t border-neutral-700 text-right">
                <a href="{{ route('dashboard.comics.index') }}" class="text-sm font-medium text-sky-500 hover:text-sky-400 transition">
                    View All Comics <i class="fas fa-arrow-right text-xs"></i>
                </a>
            </div>
        </div>

        <!-- Recent Users -->
        <div class="bg-neutral-800/50 border border-neutral-700 rounded-xl">
            <div class="p-4 border-b border-neutral-700">
                <h5 class="font-semibold text-white flex items-center gap-2"><i class="fas fa-users text-green-500"></i> Recent Users</h5>
            </div>
            <div class="p-4">
                @if($recentUsers->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentUsers as $user)
                             <div class="flex justify-between items-center">
                                <div>
                                    <h6 class="font-semibold text-neutral-200">{{ $user->display_name ?? $user->username }}</h6>
                                    <small class="text-neutral-400 text-xs">
                                        Joined {{ $user->created_at->diffForHumans() }}
                                    </small>
                                </div>
                                <span class="text-xs font-semibold px-2 py-1 rounded-full {{ $user->role === 'admin' ? 'bg-red-500/20 text-red-300' : 'bg-blue-500/20 text-blue-300' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-neutral-400 text-center py-8">No users found.</p>
                @endif
            </div>
            <div class="p-4 border-t border-neutral-700 text-right">
                 <a href="{{ route('dashboard.users.index') }}" class="text-sm font-medium text-green-500 hover:text-green-400 transition">
                    View All Users <i class="fas fa-arrow-right text-xs"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Top Rated Comics -->
    <div class="bg-neutral-800/50 border border-neutral-700 rounded-xl">
        <div class="p-4 border-b border-neutral-700">
            <h5 class="font-semibold text-white flex items-center gap-2"><i class="fas fa-star text-yellow-500"></i> Top Rated Comics</h5>
        </div>
        <div class="overflow-x-auto">
            @if($topRatedComics->count() > 0)
                <table class="w-full text-sm text-left text-neutral-300">
                    <thead class="text-xs text-neutral-400 uppercase bg-neutral-700/30">
                        <tr>
                            <th scope="col" class="px-6 py-3">#</th>
                            <th scope="col" class="px-6 py-3">Title</th>
                            <th scope="col" class="px-6 py-3">Author</th>
                            <th scope="col" class="px-6 py-3">Avg. Rating</th>
                            <th scope="col" class="px-6 py-3">Total Ratings</th>
                            <th scope="col" class="px-6 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topRatedComics as $index => $comic)
                            <tr class="border-b border-neutral-700 hover:bg-neutral-700/40">
                                <td class="px-6 py-4 font-medium text-neutral-100">{{ $index + 1 }}</td>
                                <td class="px-6 py-4">{{ $comic->title }}</td>
                                <td class="px-6 py-4">{{ $comic->author ?? 'N/A' }}</td>
                                <td class="px-6 py-4">
                                    <span class="flex items-center gap-1 text-yellow-400">
                                        <i class="fas fa-star"></i>
                                        {{ number_format($comic->average_rating, 1) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">{{ $comic->ratings_count }}</td>
                                <td class="px-6 py-4">
                                     <span class="text-xs font-semibold px-2 py-1 rounded-full
                                        @switch($comic->status)
                                            @case('ongoing') bg-green-500/20 text-green-300 @break
                                            @case('completed') bg-sky-500/20 text-sky-300 @break
                                            @default bg-yellow-500/20 text-yellow-300 @break
                                        @endswitch">
                                        {{ ucfirst($comic->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-neutral-400 text-center py-8">No rated comics found.</p>
            @endif
        </div>
    </div>
@endsection
