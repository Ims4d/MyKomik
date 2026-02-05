@extends('layouts.app')

@section('title', 'Koleksi')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-white mb-2">Koleksiku</h1>
            <p class="text-neutral-400">Komik yang sedang anda baca</p>
        </div>
    </div>

    @if($readingProgress->isEmpty())
        <div class="flex flex-col items-center justify-center text-center py-20 bg-neutral-800 rounded-xl border border-neutral-700 shadow-sm">
            <div class="w-24 h-24 bg-neutral-700 rounded-full flex items-center justify-center mb-6">
                <i class="fas fa-box-open text-neutral-500 text-4xl"></i>
            </div>
            <h3 class="text-xl font-bold text-neutral-200 mb-2">Koleksi Kosong</h3>
            <p class="text-neutral-400 mb-6 max-w-md mx-auto">Kamu belum membaca satupun komik. Mulai petualangan barumu sekarang!</p>
            <a href="{{ route('home') }}" class="inline-flex items-center px-6 py-3 bg-sky-600 hover:bg-sky-500 text-white font-medium rounded-full transition shadow-lg shadow-sky-500/25">
                <i class="fas fa-compass mr-2"></i> Eksplor Komik
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($readingProgress as $progress)
                @php($comic = $progress->chapter->comic)
                @php($chapter = $progress->chapter)
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
                    <div class="p-4 flex flex-col grow">
                        <h5 class="text-lg font-bold mb-2 leading-tight">
                            <a href="{{ route('comic.show', $comic->comic_id) }}" class="text-white hover:text-sky-400 transition">
                                {{ Str::limit($comic->title, 40) }}
                            </a>
                        </h5>

                        <div class="mt-auto pt-4 border-t border-neutral-700/50">
                            <div class="flex justify-between items-center text-sm mb-3">
                                <span class="text-neutral-400">Terakhir dibaca:</span>
                                <span class="text-sky-300 font-medium">Ch. {{ $chapter->chapter_number }}</span>
                            </div>
                            <div class="text-xs text-neutral-500 mb-4 flex items-center gap-1">
                                <i class="far fa-calendar-alt"></i> {{ $progress->last_read_at->diffForHumans() }}
                            </div>

                            <a href="{{ route('chapter.read', ['comic_id' => $comic->comic_id, 'chapter_id' => $chapter->chapter_id]) }}" class="block w-full text-center py-2 bg-sky-600 hover:bg-sky-500 text-white text-sm font-bold rounded-lg transition shadow-md shadow-sky-600/20">
                                Lanjutkan Membaca
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
