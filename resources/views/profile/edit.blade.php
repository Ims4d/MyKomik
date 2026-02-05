@extends('layouts.app')

@section('title', 'Ubah Profil')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <h3 class="text-2xl font-bold text-white flex items-center gap-3">
            <i class="fas fa-user-edit text-sky-500"></i>
            Ubah Profil
        </h3>
        <a href="{{ route('profile.show') }}" class="inline-flex items-center gap-2 px-4 py-2 border border-neutral-600 text-sm font-medium rounded-lg text-neutral-300 bg-neutral-700 hover:bg-neutral-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 focus:ring-offset-neutral-800 transition">
            <i class="fas fa-arrow-left"></i> Kembali ke Profil
        </a>
    </div>

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

    @if($errors->any())
    <div id="alert-errors" class="mb-6 bg-red-500/10 border-l-4 border-red-500 text-red-300 px-4 py-3 rounded-lg" role="alert">
        <div class="flex">
            <div class="py-1"><i class="fas fa-exclamation-circle fa-lg text-red-500 mr-3"></i></div>
            <div>
                <p class="font-bold">Terjadi Kesalahan</p>
                <ul class="mt-2 list-disc list-inside text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
         <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="document.getElementById('alert-errors').style.display='none'">
            <i class="fas fa-times text-red-400 hover:text-red-200"></i>
        </button>
    </div>
    @endif

    <!-- Edit Profile Information -->
    <div class="bg-neutral-800 rounded-xl shadow-lg border border-neutral-700 overflow-hidden mb-8">
        <div class="p-5 sm:p-6 border-b border-neutral-700">
            <h5 class="text-lg font-semibold text-white flex items-center gap-3">
                <i class="fas fa-user-circle text-sky-500"></i>
                Informasi Profil
            </h5>
        </div>
        <div class="p-5 sm:p-6 bg-neutral-800/50">
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Avatar Section -->
                    <div class="md:col-span-1 text-center">
                         <label class="block text-sm font-medium text-neutral-300 mb-2">
                            <i class="fas fa-image"></i> Avatar Profil
                        </label>
                        <img src="{{ $user->getAvatarUrl() }}" alt="{{ $user->username }}" class="w-32 h-32 rounded-full object-cover mx-auto border-4 border-neutral-700 ring-2 ring-sky-500 shadow-lg" id="avatar-preview">
                        <div class="mt-4">
                            <label for="avatar" class="cursor-pointer inline-flex items-center gap-2 px-3 py-2 border border-neutral-600 text-sm font-medium rounded-lg text-neutral-300 bg-neutral-700 hover:bg-neutral-600 transition">
                                <i class="fas fa-upload text-xs"></i>
                                Pilih Gambar
                            </label>
                            <input type="file" id="avatar" name="avatar" class="hidden" accept="image/*" onchange="previewAvatar(event)">
                            <p class="text-xs text-neutral-500 mt-2">Max 2MB. JPG, PNG, GIF.</p>
                            @error('avatar')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                         @if($user->avatar)
                        <div class="mt-4">
                            <button type="button" onclick="deleteAvatar()" class="text-red-500 hover:text-red-400 text-xs font-medium flex items-center gap-1 mx-auto">
                                <i class="fas fa-trash"></i> Hapus Avatar
                            </button>
                        </div>
                        @endif
                    </div>

                    <!-- Fields Section -->
                    <div class="md:col-span-2 space-y-5">
                         <div>
                            <label for="username" class="block text-sm font-medium text-neutral-300 mb-1">
                                Username <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="username" name="username" value="{{ old('username', $user->username) }}" required
                                   class="block w-full px-4 py-2 rounded-lg border-neutral-600 bg-neutral-700 text-white shadow-sm focus:border-sky-500 focus:ring-sky-500 sm:text-sm @error('username') border-red-500 @enderror">
                            <p class="text-xs text-neutral-500 mt-1">Hanya huruf, angka, setrip, dan garis bawah.</p>
                            @error('username')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="display_name" class="block text-sm font-medium text-neutral-300 mb-1">
                                Nama Tampilan
                            </label>
                            <input type="text" id="display_name" name="display_name" value="{{ old('display_name', $user->display_name) }}"
                                   class="block w-full px-4 py-2 rounded-lg border-neutral-600 bg-neutral-700 text-white shadow-sm focus:border-sky-500 focus:ring-sky-500 sm:text-sm @error('display_name') border-red-500 @enderror">
                            <p class="text-xs text-neutral-500 mt-1">Ini akan ditampilkan kepada pengguna lain.</p>
                             @error('display_name')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-neutral-700 flex justify-end">
                    <button type="submit" class="w-full sm:w-auto inline-flex justify-center items-center gap-2 px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-sky-600 hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 focus:ring-offset-neutral-800 transition">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Change Password -->
    <div class="bg-neutral-800 rounded-xl shadow-lg border border-neutral-700 overflow-hidden">
        <div class="p-5 sm:p-6 border-b border-neutral-700">
             <h5 class="text-lg font-semibold text-white flex items-center gap-3">
                <i class="fas fa-key text-yellow-500"></i>
                Ubah Kata Sandi
            </h5>
        </div>
        <div class="p-5 sm:p-6 bg-neutral-800/50">
             <form action="{{ route('profile.password') }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                 <div>
                    <label for="current_password" class="block text-sm font-medium text-neutral-300 mb-1">
                        Kata Sandi Saat Ini <span class="text-red-500">*</span>
                    </label>
                    <input type="password" id="current_password" name="current_password" required
                           class="block w-full px-4 py-2 rounded-lg border-neutral-600 bg-neutral-700 text-white shadow-sm focus:border-sky-500 focus:ring-sky-500 sm:text-sm @error('current_password', 'updatePassword') border-red-500 @enderror">
                     @error('current_password', 'updatePassword')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                 <div>
                    <label for="new_password" class="block text-sm font-medium text-neutral-300 mb-1">
                        Kata Sandi Baru <span class="text-red-500">*</span>
                    </label>
                    <input type="password" id="new_password" name="new_password" required
                           class="block w-full px-4 py-2 rounded-lg border-neutral-600 bg-neutral-700 text-white shadow-sm focus:border-sky-500 focus:ring-sky-500 sm:text-sm @error('new_password', 'updatePassword') border-red-500 @enderror">
                     <p class="text-xs text-neutral-500 mt-1">Minimal 6 karakter dengan huruf dan angka.</p>
                     @error('new_password', 'updatePassword')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="new_password_confirmation" class="block text-sm font-medium text-neutral-300 mb-1">
                        Konfirmasi Kata Sandi Baru <span class="text-red-500">*</span>
                    </label>
                    <input type="password" id="new_password_confirmation" name="new_password_confirmation" required
                           class="block w-full px-4 py-2 rounded-lg border-neutral-600 bg-neutral-700 text-white shadow-sm focus:border-sky-500 focus:ring-sky-500 sm:text-sm">
                </div>

                <div class="!mt-8 pt-6 border-t border-neutral-700 flex justify-end">
                    <button type="submit" class="w-full sm:w-auto inline-flex justify-center items-center gap-2 px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-neutral-800 bg-yellow-500 hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 focus:ring-offset-neutral-800 transition">
                        <i class="fas fa-key"></i> Ubah Kata Sandi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-hide alerts
        const alerts = document.querySelectorAll('#alert-success, #alert-errors');
        alerts.forEach(function(alert) {
            setTimeout(function() {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.style.display = 'none', 500);
            }, 5000);
        });
    });

    // Avatar preview
    function previewAvatar(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('avatar-preview').src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    }

    // Delete avatar
    function deleteAvatar() {
        if (confirm('Hapus avatar kustom Anda? Anda akan menggunakan avatar inisial default.')) {
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("profile.avatar.delete") }}';

            var csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);

            var methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(methodInput);

            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
@endpush
@endsection