@extends('layouts.app')

@section('title', 'Daftar Akun Baru')

@section('content')
<div class="flex items-center justify-center min-h-[calc(100vh-128px)] px-4 py-12 sm:px-6 lg:px-8">
    <div class="w-full max-w-lg">
        <div class="bg-neutral-800 rounded-xl shadow-lg border border-neutral-700">
            <div class="p-8 text-center border-b border-neutral-700">
                <a href="{{ route('home') }}" class="inline-block text-sky-500 hover:text-sky-400 transition">
                    <i class="fas fa-user-plus fa-3x"></i>
                </a>
                <h2 class="mt-4 text-2xl font-bold text-white">
                    Buat Akun Baru
                </h2>
                <p class="mt-2 text-sm text-neutral-400">
                    Bergabunglah dengan MyKomik hari ini!
                </p>
            </div>

            <div class="p-8">
                @if($errors->any())
                    <div class="mb-6 bg-red-500/10 border-l-4 border-red-500 text-red-300 px-4 py-3 rounded-lg" role="alert">
                        <h4 class="font-bold">Oops! Ada yang salah.</h4>
                        <ul class="mt-2 list-disc list-inside text-sm">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('register') }}" method="POST" class="space-y-5">
                    @csrf
                    
                    <div>
                        <label for="username" class="block text-sm font-medium text-neutral-300 mb-1">
                            Username <span class="text-red-400">*</span>
                        </label>
                        <input type="text" 
                               class="block w-full px-4 py-2 rounded-lg border-neutral-600 bg-neutral-700 text-white shadow-sm focus:border-sky-500 focus:ring-sky-500 sm:text-sm placeholder-neutral-400 @error('username') border-red-500 @enderror" 
                               id="username" 
                               name="username" 
                               value="{{ old('username') }}" 
                               placeholder="Pilih username unik"
                               required 
                               autofocus>
                        <p class="mt-2 text-xs text-neutral-400">Hanya boleh huruf, angka, setrip, dan garis bawah.</p>
                    </div>

                    <div>
                        <label for="display_name" class="block text-sm font-medium text-neutral-300 mb-1">
                            Nama Tampilan
                        </label>
                        <input type="text" 
                               class="block w-full px-4 py-2 rounded-lg border-neutral-600 bg-neutral-700 text-white shadow-sm focus:border-sky-500 focus:ring-sky-500 sm:text-sm placeholder-neutral-400 @error('display_name') border-red-500 @enderror" 
                               id="display_name" 
                               name="display_name" 
                               value="{{ old('display_name') }}" 
                               placeholder="Nama yang akan dilihat pengguna lain (opsional)">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-sm font-medium text-neutral-300 mb-1">
                                Password <span class="text-red-400">*</span>
                            </label>
                            <input type="password" 
                                   class="block w-full px-4 py-2 rounded-lg border-neutral-600 bg-neutral-700 text-white shadow-sm focus:border-sky-500 focus:ring-sky-500 sm:text-sm placeholder-neutral-400 @error('password') border-red-500 @enderror" 
                                   id="password" 
                                   name="password" 
                                   placeholder="Buat password yang kuat"
                                   required>
                            <p class="mt-2 text-xs text-neutral-400">Minimal 6 karakter, mengandung huruf dan angka.</p>
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-neutral-300 mb-1">
                                Konfirmasi Password <span class="text-red-400">*</span>
                            </label>
                            <input type="password" 
                                   class="block w-full px-4 py-2 rounded-lg border-neutral-600 bg-neutral-700 text-white shadow-sm focus:border-sky-500 focus:ring-sky-500 sm:text-sm placeholder-neutral-400" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   placeholder="Ulangi password"
                                   required>
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2.5 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-sky-600 hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 focus:ring-offset-neutral-800 transition duration-300">
                            <i class="fas fa-user-plus mr-2"></i>
                            Buat Akun
                        </button>
                    </div>
                </form>

                <div class="text-center mt-6">
                    <p class="text-sm text-neutral-400">
                        Sudah punya akun?
                        <a href="{{ route('login') }}" class="font-medium text-sky-400 hover:text-sky-300">
                            Masuk di sini
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection