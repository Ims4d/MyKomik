@extends('layouts.app')

@section('title', 'Beranda - Jelajahi Komik')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Sidebar Filters -->
        <div class="lg:col-span-1">
            <div class="bg-neutral-800 rounded-xl shadow-lg border border-neutral-700 sticky top-24 z-30">
                <div class="p-4 flex justify-between items-center cursor-pointer lg:cursor-auto" id="filter-header">
                    <h5 class="font-bold text-lg text-sky-500 flex items-center gap-2">
                        <i class="fas fa-filter"></i> Filter
                    </h5>
                    <i class="fas fa-chevron-down text-neutral-400 lg:hidden! transition-transform duration-300" id="filter-icon"></i>
                </div>
                <div class="p-4 hidden lg:block border-t border-neutral-700" id="filter-content">
                    <form action="{{ route('home') }}" method="GET" id="filterForm" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-neutral-300 mb-1">
                                <i class="fas fa-search mr-1"></i> Cari
                            </label>
                            <input type="text"
                                   class="block w-full px-4 py-2 rounded-lg border-neutral-600 bg-neutral-700 text-white shadow-sm focus:border-sky-500 focus:ring-sky-500 sm:text-sm placeholder-neutral-400"
                                   name="search"
                                   placeholder="Judul atau penulis..."
                                   value="{{ request('search') }}">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-neutral-300 mb-1">
                                <i class="fas fa-tags mr-1"></i> Genre
                            </label>
                            <select class="block w-full px-3 py-2 rounded-lg border-neutral-600 bg-neutral-700 text-white shadow-sm focus:border-sky-500 focus:ring-sky-500 sm:text-sm" name="genre">
                                <option value="">Semua Genre</option>
                                @foreach($genres as $genre)
                                    <option value="{{ $genre->genre_id }}"
                                            {{ request('genre') == $genre->genre_id ? 'selected' : '' }}>
                                        {{ $genre->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-neutral-300 mb-1">
                                <i class="fas fa-info-circle mr-1"></i> Status
                            </label>
                            <select class="block w-full px-3 py-2 rounded-lg border-neutral-600 bg-neutral-700 text-white shadow-sm focus:border-sky-500 focus:ring-sky-500 sm:text-sm" name="status">
                                <option value="">Semua Status</option>
                                <option value="ongoing" {{ request('status') === 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="hiatus" {{ request('status') === 'hiatus' ? 'selected' : '' }}>Hiatus</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-neutral-300 mb-1">
                                <i class="fas fa-sort mr-1"></i> Urutkan
                            </label>
                            <select class="block w-full px-3 py-2 rounded-lg border-neutral-600 bg-neutral-700 text-white shadow-sm focus:border-sky-500 focus:ring-sky-500 sm:text-sm" name="sort">
                                <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Terbaru</option>
                                <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Terlama</option>
                                <option value="title_asc" {{ request('sort') === 'title_asc' ? 'selected' : '' }}>Judul (A-Z)</option>
                                <option value="title_desc" {{ request('sort') === 'title_desc' ? 'selected' : '' }}>Judul (Z-A)</option>
                            </select>
                        </div>

                        <div class="grid grid-cols-1 gap-2 pt-2">
                            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-sky-600 hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 focus:ring-offset-neutral-800 transition">
                                <i class="fas fa-search mr-2"></i> Terapkan
                            </button>
                            <a href="{{ route('home') }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-neutral-600 text-sm font-medium rounded-lg text-neutral-300 bg-transparent hover:bg-neutral-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-500 focus:ring-offset-neutral-800 transition">
                                <i class="fas fa-redo mr-2"></i> Atur Ulang
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-3">
            @if(request()->is('home') && !request()->has('page'))
            <div class="mb-12">
                <h4 class="text-2xl font-bold mb-6 text-white flex items-center gap-2">
                    <span class="bg-sky-600 w-1 h-8 rounded-full inline-block"></span> Komik Populer
                </h4>
                @if($featuredComics->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($featuredComics as $comic)
                            <div class="bg-neutral-800 rounded-xl shadow-lg border border-neutral-700 overflow-hidden hover:transform hover:scale-[1.02] transition duration-300 flex flex-col h-full group">
                                <div class="relative h-64 overflow-hidden">
                                    <a href="{{ route('comic.show', $comic->comic_id) }}">
                                        @if($comic->cover_image_url)
                                            <img src="{{ $comic->cover_image_url }}"
                                                 alt="{{ $comic->title }}"
                                                 class="w-full h-full object-cover group-hover:opacity-90 transition">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center bg-neutral-700 text-neutral-500">
                                                <i class="fas fa-book fa-3x"></i>
                                            </div>
                                        @endif
                                    </a>
                                </div>
                                <div class="p-4 flex flex-col flex-grow">
                                    <h5 class="text-lg font-bold mb-2 leading-tight">
                                        <a href="{{ route('comic.show', $comic->comic_id) }}" class="text-white hover:text-sky-400 transition">
                                            {{ Str::limit($comic->title, 40) }}
                                        </a>
                                    </h5>
                                    <div class="mt-auto flex justify-between items-center pt-4">
                                        <small class="text-neutral-400 flex items-center gap-1">
                                            <i class="fas fa-list"></i> {{ $comic->chapters_count }} chapters
                                        </small>
                                        <a href="{{ route('comic.show', $comic->comic_id) }}" class="inline-flex items-center px-3 py-1 bg-sky-600 hover:bg-sky-500 text-white text-xs font-bold uppercase tracking-wide rounded-full transition">
                                            Baca <i class="fas fa-arrow-right ml-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-neutral-400 italic">Belum ada komik populer untuk ditampilkan.</p>
                @endif
                <div class="my-8 border-t border-neutral-700"></div>
            </div>
            @endif

            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                <h4 class="text-xl font-bold text-white flex items-center gap-2">
                    @if(request('search'))
                        Hasil pencarian: "{{ request('search') }}"
                    @elseif(request('genre'))
                        Genre: {{ $genres->firstWhere('genre_id', request('genre'))->name ?? '' }}
                    @else
                        Semua Komik
                    @endif
                </h4>
                <span class="px-3 py-1 bg-neutral-700 text-neutral-300 rounded-full text-sm font-medium border border-neutral-600">
                    Total: {{ $comics->total() }}
                </span>
            </div>

            @if($comics->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($comics as $comic)
                        <div class="bg-neutral-800 rounded-xl shadow-lg border border-neutral-700 overflow-hidden hover:shadow-xl transition duration-300 flex flex-col h-full group">
                            <div class="relative h-64 overflow-hidden">
                                <span class="absolute top-2 right-2 px-2 py-1 rounded-md text-xs font-bold uppercase tracking-wide z-10 shadow-md {{ $comic->status === 'ongoing' ? 'bg-green-500/90 text-white' : ($comic->status === 'completed' ? 'bg-sky-500/90 text-white' : 'bg-yellow-500/90 text-black') }}">
                                    {{ ucfirst($comic->status) }}
                                </span>

                                <a href="{{ route('comic.show', $comic->comic_id) }}">
                                    @if($comic->cover_image_url)
                                        <img src="{{ $comic->cover_image_url }}"
                                             alt="{{ $comic->title }}"
                                             class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-neutral-700 text-neutral-500">
                                            <i class="fas fa-book fa-3x"></i>
                                        </div>
                                    @endif
                                </a>
                            </div>
                            <div class="p-4 flex flex-col flex-grow">
                                <h5 class="text-lg font-bold mb-2 leading-tight">
                                    <a href="{{ route('comic.show', $comic->comic_id) }}" class="text-white hover:text-sky-400 transition">
                                        {{ Str::limit($comic->title, 40) }}
                                    </a>
                                </h5>

                                @if($comic->author)
                                    <p class="text-neutral-400 text-sm mb-3 flex items-center gap-2">
                                        <i class="fas fa-user text-neutral-500"></i> {{ $comic->author }}
                                    </p>
                                @endif

                                <div class="mb-4 flex flex-wrap gap-1">
                                    @foreach($comic->genres->take(2) as $genre)
                                        <span class="px-2 py-0.5 rounded text-xs font-medium bg-neutral-700 text-sky-300 border border-neutral-600">
                                            {{ $genre->name }}
                                        </span>
                                    @endforeach
                                </div>

                                <div class="mt-auto pt-3 border-t border-neutral-700/50 flex justify-between items-center">
                                    <small class="text-neutral-500 flex items-center gap-1">
                                        <i class="fas fa-list"></i> {{ $comic->totalChapters() }} ch
                                    </small>
                                    <a href="{{ route('comic.show', $comic->comic_id) }}" class="text-sky-400 hover:text-sky-300 text-sm font-semibold flex items-center gap-1 transition">
                                        Baca <i class="fas fa-chevron-right text-xs"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $comics->links() }}
                </div>
            @else
                <div class="text-center py-12 bg-neutral-800 rounded-xl border border-neutral-700">
                    <i class="fas fa-inbox fa-4x text-neutral-600 mb-4"></i>
                    <h4 class="text-xl font-bold text-neutral-300 mb-2">Komik tidak ditemukan</h4>
                    <p class="text-neutral-500 max-w-md mx-auto mb-6">
                        @if(request('search') || request('genre') || request('status'))
                            Coba sesuaikan filter atau kata kunci pencarian Anda untuk menemukan apa yang Anda cari.
                        @else
                            Belum ada komik yang tersedia saat ini.
                        @endif
                    </p>
                    @if(request('search') || request('genre') || request('status'))
                        <a href="{{ route('home') }}" class="inline-flex items-center px-4 py-2 bg-sky-600 hover:bg-sky-500 text-white font-medium rounded-lg transition shadow-lg shadow-sky-500/25">
                            <i class="fas fa-redo mr-2"></i> Hapus Filter
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const header = document.getElementById('filter-header');
        const content = document.getElementById('filter-content');
        const icon = document.getElementById('filter-icon');

        if(header && content && icon) {
            header.addEventListener('click', function() {
                if (window.innerWidth < 1024) {
                    content.classList.toggle('hidden');
                    icon.classList.toggle('rotate-180');
                }
            });
        }
    });
</script>
@endpush
