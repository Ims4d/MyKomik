@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    <!-- Flash Messages -->
    @if(session('success'))
        <div id="alert-success" class="mb-6 bg-green-500/10 border-l-4 border-green-500 text-green-300 px-4 py-3 rounded-lg relative" role="alert">
            <div class="flex">
                <div class="py-1"><i class="fas fa-check-circle fa-lg text-green-500 mr-3"></i></div>
                <div>
                    <p class="font-bold">Berhasil</p>
                    <p class="text-sm">{{ session('success') }}</p>
                </div>
            </div>
            <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="document.getElementById('alert-success').style.display='none'">
                <i class="fas fa-times text-green-400 hover:text-green-200"></i>
            </button>
        </div>
    @endif

    <!-- Profile Card -->
    <div class="bg-neutral-800 rounded-xl shadow-lg border border-neutral-700 overflow-hidden">
        <div class="bg-neutral-800/50 p-4 sm:p-6 border-b border-neutral-700 flex justify-between items-center">
            <h4 class="text-xl font-bold text-white flex items-center gap-3">
                <i class="fas fa-user-circle text-sky-500"></i>
                Profil Saya
            </h4>
            <a href="{{ route('profile.edit') }}" class="inline-flex items-center gap-2 px-3 py-2 border border-neutral-600 text-sm font-medium rounded-lg text-neutral-300 bg-neutral-700 hover:bg-neutral-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 focus:ring-offset-neutral-800 transition">
                <i class="fas fa-edit"></i>
                <span class="hidden sm:inline">Ubah Profil</span>
            </a>
        </div>

        <div class="p-6 md:p-8">
            <div class="flex flex-col md:flex-row items-center md:items-start md:gap-8 mb-8">
                <!-- Avatar & Basic Info -->
                <div class="flex-shrink-0 mb-6 md:mb-0 text-center">
                    <img src="{{ $user->getAvatarUrl() }}"
                         alt="{{ $user->username }}"
                         class="w-32 h-32 rounded-full object-cover mx-auto border-4 border-neutral-700 ring-2 ring-sky-500 shadow-lg">
                    <h5 class="mt-4 text-xl font-bold text-white">{{ $user->display_name ?? $user->username }}</h5>
                    <p class="text-neutral-400">{{ '@' . $user->username }}</p>
                    <span class="mt-2 inline-block px-3 py-1 text-xs font-semibold rounded-full {{ $user->role === 'admin' ? 'bg-red-500/80 text-white' : 'bg-sky-500/80 text-white' }}">
                        {{ ucfirst($user->role) }}
                    </span>
                </div>

                <!-- Account Details -->
                <div class="flex-grow w-full border-t border-neutral-700 md:border-t-0 md:border-l md:pl-8 pt-6 md:pt-0">
                    <h5 class="mb-6 text-lg font-semibold text-white flex items-center gap-3">
                        <i class="fas fa-info-circle text-sky-500"></i>
                        Informasi Akun
                    </h5>
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-semibold text-neutral-400 uppercase tracking-wider">Username</label>
                            <p class="mt-1 text-neutral-200">{{ $user->username }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-neutral-400 uppercase tracking-wider">Nama Tampilan</label>
                            <p class="mt-1 text-neutral-200">{{ $user->display_name ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-neutral-400 uppercase tracking-wider">Bergabung</label>
                            <p class="mt-1 text-neutral-200 flex items-center gap-2">
                                <i class="far fa-calendar text-neutral-500"></i>
                                {{ $user->created_at->format('d F Y') }} ({{ $user->created_at->diffForHumans() }})
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Divider -->
            <div class="border-t border-neutral-700/50 my-8"></div>

            <!-- Statistics -->
            <div>
                <h5 class="mb-6 text-lg font-semibold text-white flex items-center gap-3">
                    <i class="fas fa-chart-bar text-sky-500"></i>
                    Aktivitas Saya
                </h5>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="bg-neutral-700/50 rounded-lg p-4 text-center border border-neutral-700 hover:border-sky-500 transition group">
                        <i class="fas fa-star text-yellow-400 text-3xl mb-2"></i>
                        <p class="text-2xl font-bold text-white">{{ $stats['total_ratings'] }}</p>
                        <p class="text-neutral-400 text-sm group-hover:text-white transition">Rating Diberikan</p>
                    </div>
                    <div class="bg-neutral-700/50 rounded-lg p-4 text-center border border-neutral-700 hover:border-sky-500 transition group">
                        <i class="fas fa-comments text-sky-400 text-3xl mb-2"></i>
                        <p class="text-2xl font-bold text-white">{{ $stats['total_comments'] }}</p>
                        <p class="text-neutral-400 text-sm group-hover:text-white transition">Komentar Dibuat</p>
                    </div>
                    <div class="bg-neutral-700/50 rounded-lg p-4 text-center border border-neutral-700 hover:border-sky-500 transition group">
                        <i class="fas fa-book-reader text-green-400 text-3xl mb-2"></i>
                        <p class="text-2xl font-bold text-white">{{ $stats['reading_progress'] }}</p>
                        <p class="text-neutral-400 text-sm group-hover:text-white transition">Chapter Dibaca</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-neutral-800/50 p-4 text-center border-t border-neutral-700">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-sky-300 hover:bg-sky-500/10 hover:text-sky-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 focus:ring-offset-neutral-800 transition">
                <i class="fas fa-arrow-left"></i> Kembali ke Beranda
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const alertSuccess = document.getElementById('alert-success');
        if (alertSuccess) {
            setTimeout(function() {
                alertSuccess.style.transition = 'opacity 0.5s ease';
                alertSuccess.style.opacity = '0';
                setTimeout(() => alertSuccess.style.display = 'none', 500);
            }, 5000);
        }
    });
</script>
@endpush
