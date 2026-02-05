@extends('layouts.app')

@section('title', $comic->title . ' - Chapter ' . $chapter->chapter_number)

@push('styles')
<style>
    body > nav.top-0,
    body > footer {
        display: none;
    }
    main.grow {
        padding-top: 0;
        margin-top: -1.5rem;
    }
</style>
@endpush

@section('content')
<div class="bg-neutral-900 min-h-screen text-white">

    <!-- Reader Header -->
    <header id="readerHeader" class="bg-neutral-800/80 backdrop-blur-md border-b border-neutral-700 sticky top-0 z-40 transition-transform duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Left Side: Comic Info -->
                <div class="flex items-center gap-3 overflow-hidden">
                    <a href="{{ route('comic.show', $comic->comic_id) }}" class="flex-shrink-0 inline-flex items-center justify-center p-2 rounded-md text-neutral-300 hover:bg-neutral-700 hover:text-white transition">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <div class="overflow-hidden whitespace-nowrap">
                        <h1 class="text-lg font-bold truncate">{{ $comic->title }}</h1>
                        <p class="text-sm text-neutral-400 truncate">Chapter {{ $chapter->chapter_number }} @if($chapter->title) - {{ $chapter->title }} @endif</p>
                    </div>
                </div>

                <!-- Right Side: Navigation -->
                <div class="flex items-center gap-2">
                    <select id="chapterSelect" onchange="changeChapter(this.value)" class="hidden md:block w-full max-w-xs px-3 py-2 rounded-lg border-neutral-600 bg-neutral-700 text-white shadow-sm focus:border-sky-500 focus:ring-sky-500 sm:text-sm">
                        @foreach($allChapters as $ch)
                            <option value="{{ route('chapter.read', [$comic->comic_id, $ch->chapter_id]) }}" {{ $ch->chapter_id == $chapter->chapter_id ? 'selected' : '' }}>
                                Chapter {{ $ch->chapter_number }}
                            </option>
                        @endforeach
                    </select>
                    <button id="settingsBtn" onclick="toggleSettings(event)" class="flex-shrink-0 inline-flex items-center justify-center p-2 rounded-md text-neutral-300 hover:bg-neutral-700 hover:text-white transition">
                        <i class="fas fa-cog"></i>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Reader Content -->
    <main class="max-w-4xl mx-auto py-4 sm:py-6 lg:py-8 px-2 sm:px-4">
        @guest
            <div id="guest-alert" class="bg-blue-500/10 border-l-4 border-blue-500 text-blue-300 px-4 py-3 rounded-lg relative mb-4" role="alert">
                <div class="flex">
                    <div class="py-1"><i class="fas fa-info-circle fa-lg text-blue-500 mr-3"></i></div>
                    <div>
                        <p class="font-bold">Kamu membaca sebagai guest.</p>
                        <p class="text-sm">
                            <a href="{{ route('login') }}" class="font-semibold underline hover:text-blue-200">Log in</a> atau <a href="{{ route('register') }}" class="font-semibold underline hover:text-blue-200">buat akun</a> untuk menyimpan progres.
                        </p>
                    </div>
                </div>
                <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="document.getElementById('guest-alert').style.display='none'">
                    <i class="fas fa-times text-blue-400 hover:text-blue-200"></i>
                </button>
            </div>
        @endguest

        <!-- Page Container -->
        <div id="pagesContainer" class="space-y-2">
            @forelse($chapter->pages as $page)
                <div class="text-center">
                    <img src="{{ $page->image_url }}"
                         alt="Page {{ $page->page_number }}"
                         class="mx-auto"
                         loading="lazy">
                    <span class="text-xs text-neutral-500 mt-1 block">{{ $page->page_number }} / {{ $totalPages }}</span>
                </div>
            @empty
                <div class="text-center py-24 bg-neutral-800 rounded-lg">
                    <i class="fas fa-images fa-4x text-neutral-600 mb-4"></i>
                    <h3 class="text-xl font-bold text-neutral-300">No Pages Yet :(</h3>
                    <p class="text-neutral-500 mt-2">The pages for this chapter have not been uploaded. uhh</p>
                </div>
            @endforelse
        </div>

        <!-- Chapter Navigation -->
        <div class="mt-8 pt-6 border-t border-neutral-700">
            <div class="flex justify-between items-center">
                @if($prevChapter)
                    <a href="{{ route('chapter.read', [$comic->comic_id, $prevChapter->chapter_id]) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white font-medium rounded-lg transition">
                        <i class="fas fa-arrow-left"></i>
                        <span>Prev</span>
                    </a>
                @else
                    <span class="inline-flex items-center gap-2 px-4 py-2 bg-neutral-700 text-neutral-500 font-medium rounded-lg cursor-not-allowed">
                        <i class="fas fa-arrow-left"></i>
                        <span>Prev</span>
                    </span>
                @endif

                <a href="{{ route('comic.show', $comic->comic_id) }}" class="px-4 py-2 bg-neutral-700 hover:bg-neutral-600 text-neutral-300 font-medium rounded-lg transition">
                    All Chapters
                </a>

                @if($nextChapter)
                     <a href="{{ route('chapter.read', [$comic->comic_id, $nextChapter->chapter_id]) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white font-medium rounded-lg transition">
                        <span>Next</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                @else
                    <span class="inline-flex items-center gap-2 px-4 py-2 bg-neutral-700 text-neutral-500 font-medium rounded-lg cursor-not-allowed">
                        <span>Next</span>
                        <i class="fas fa-arrow-right"></i>
                    </span>
                @endif
            </div>
            <p class="text-center text-sm text-neutral-500 mt-4">
                @if($nextChapter)
                    Kamu telah selesai membaca chapter {{ $chapter->chapter_number }}. Lanjut ke chapter {{ $nextChapter->chapter_number }}.
                @else
                    Kamu telah membaca chapter terakhir.
                @endif
            </p>
        </div>
    </main>

    <!-- Settings Menu -->
    <div id="settingsMenu" class="hidden fixed top-16 right-4 z-50 mt-2 w-64 origin-top-right rounded-md bg-neutral-800 py-2 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none border border-neutral-700">
        <div class="px-4 py-2">
            <label for="imageWidth" class="block text-sm font-medium text-neutral-300 mb-1">Image Width</label>
            <select id="imageWidth" onchange="changeImageWidth(this.value)" class="w-full px-3 py-2 rounded-lg border-neutral-600 bg-neutral-700 text-white shadow-sm focus:border-sky-500 focus:ring-sky-500 sm:text-sm">
                <option value="default">Default</option>
                <option value="70%">70%</option>
                <option value="80%">80%</option>
                <option value="90%">90%</option>
                <option value="100%">100% (Fit)</option>
                <option value="110%">110%</option>
                <option value="120%">120%</option>
            </select>
        </div>
        <div class="border-t border-neutral-700 my-2"></div>
         <div class="px-4 py-2">
            <label for="autoHide" class="block text-sm font-medium text-neutral-300 mb-1">Auto-hide Header</label>
            <select id="autoHide" onchange="changeAutoHide(this.value)" class="w-full px-3 py-2 rounded-lg border-neutral-600 bg-neutral-700 text-white shadow-sm focus:border-sky-500 focus:ring-sky-500 sm:text-sm">
                <option value="true">Enabled</option>
                <option value="false">Disabled</option>
            </select>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let autoHideEnabled = true;
    let lastScrollTop = 0;
    const readerHeader = document.getElementById('readerHeader');

    // --- Settings ---
    function toggleSettings(event) {
        event.stopPropagation();
        document.getElementById('settingsMenu').classList.toggle('hidden');
    }

    function changeChapter(url) {
        window.location.href = url;
    }

    function changeImageWidth(width) {
        const images = document.querySelectorAll('#pagesContainer img');
        images.forEach(img => {
            if (width === 'default') {
                img.style.width = '';
                img.style.maxWidth = '';
            } else {
                img.style.width = width;
                const percent = parseInt(width);
                img.style.maxWidth = percent > 100 ? 'none' : '100%';
            }
        });
        localStorage.setItem('imageWidth', width);
    }

    function changeAutoHide(enabled) {
        autoHideEnabled = enabled === 'true';
        localStorage.setItem('autoHide', enabled);
        if (!autoHideEnabled) {
            readerHeader.classList.remove('-translate-y-full');
        }
    }

    // --- Event Listeners ---
    // Load saved settings
    window.addEventListener('load', function() {
        const savedWidth = localStorage.getItem('imageWidth') || 'default';
        const savedAutoHide = localStorage.getItem('autoHide') || 'true';

        document.getElementById('imageWidth').value = savedWidth;
        document.getElementById('autoHide').value = savedAutoHide;

        changeImageWidth(savedWidth);
        changeAutoHide(savedAutoHide);
    });

    // Auto-hide header on scroll
    window.addEventListener('scroll', function() {
        if (!autoHideEnabled) return;

        let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        if (scrollTop > lastScrollTop && scrollTop > readerHeader.offsetHeight) {
            readerHeader.classList.add('-translate-y-full');
        } else {
            readerHeader.classList.remove('-translate-y-full');
        }
        lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
    }, { passive: true });

    // Close settings when clicking outside
    document.addEventListener('click', function(event) {
        const settingsMenu = document.getElementById('settingsMenu');
        const settingsBtn = document.getElementById('settingsBtn');
        if (!settingsMenu.classList.contains('hidden') && !settingsMenu.contains(event.target) && !settingsBtn.contains(event.target)) {
            settingsMenu.classList.add('hidden');
        }
    });

    // Keyboard navigation
    document.addEventListener('keydown', function(event) {
        // block if typing in an input
        if (event.target.matches('input, textarea, select')) {
            return;
        }

        @if($prevChapter)
            if (event.key === 'ArrowLeft') {
                window.location.href = '{{ route("chapter.read", [$comic->comic_id, $prevChapter->chapter_id]) }}';
            }
        @endif

        @if($nextChapter)
            if (event.key === 'ArrowRight') {
                window.location.href = '{{ route("chapter.read", [$comic->comic_id, $nextChapter->chapter_id]) }}';
            }
        @endif
    });
</script>
@endpush
