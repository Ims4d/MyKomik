@extends('layouts.app')

@section('title', 'Masuk ke MyKomik')

@section('content')
<div class="flex items-center justify-center min-h-[calc(100vh-128px)] px-4 py-12 sm:px-6 lg:px-8">
    <div class="w-full max-w-md">
        <div class="bg-neutral-800 rounded-xl shadow-lg border border-neutral-700">
            <div class="p-8 text-center border-b border-neutral-700">
                <h2 class="mt-4 text-2xl font-bold text-white">
                    Masuk ke Akun Anda
                </h2>
                <p class="mt-2 text-sm text-neutral-400">
                    Selamat datang kembali
                </p>
            </div>

            <div class="p-8">
                @if(session('success'))
                    <div class="mb-4 bg-green-500/10 border-l-4 border-green-500 text-green-300 px-4 py-3 rounded-lg" role="alert">
                        <p class="text-sm">{{ session('success') }}</p>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-4 bg-red-500/10 border-l-4 border-red-500 text-red-300 px-4 py-3 rounded-lg" role="alert">
                        <p class="text-sm">{{ $errors->first() }}</p>
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label for="username" class="block text-sm font-medium text-neutral-300 mb-1">
                            Username
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <i class="fas fa-user text-neutral-400"></i>
                            </span>
                            <input type="text"
                                   class="block w-full pl-10 pr-4 py-2 rounded-lg border-neutral-600 bg-neutral-700 text-white shadow-sm focus:border-sky-500 focus:ring-sky-500 sm:text-sm placeholder-neutral-400 @error('username') border-red-500 @enderror"
                                   id="username"
                                   name="username"
                                   value="{{ old('username') }}"
                                   placeholder="Masukkan username Anda"
                                   required
                                   autofocus>
                        </div>
                        @error('username')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-neutral-300 mb-1">
                            Password
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <i class="fas fa-lock text-neutral-400"></i>
                            </span>
                            <input type="password"
                                   class="block w-full pl-10 pr-4 py-2 rounded-lg border-neutral-600 bg-neutral-700 text-white shadow-sm focus:border-sky-500 focus:ring-sky-500 sm:text-sm placeholder-neutral-400 @error('password') border-red-500 @enderror"
                                   id="password"
                                   name="password"
                                   placeholder="Masukkan password Anda"
                                   required>
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2.5 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-sky-600 hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 focus:ring-offset-neutral-800 transition duration-300">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Masuk
                        </button>
                    </div>
                </form>

                <div class="text-center mt-6">
                    <p class="text-sm text-neutral-400">
                        Belum punya akun?
                        <a href="{{ route('register') }}" class="font-medium text-sky-400 hover:text-sky-300">
                            Daftar di sini
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
