<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - MyKomik</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="bg-neutral-900 text-neutral-100 font-sans antialiased">

    <div class="flex h-screen bg-neutral-900">
        <!-- Sidebar -->
        <aside id="sidebar" class="w-64 bg-neutral-800/80 backdrop-blur-md border-r border-neutral-700 flex-col justify-between hidden md:flex transition-all duration-300">
            <div>
                <!-- Navigation Links -->
                <nav class="flex-1 px-4 py-4 space-y-2">
                    <a href="{{ route('dashboard.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg transition {{ request()->routeIs('dashboard.index') ? 'bg-sky-500 text-white' : 'text-neutral-300 hover:bg-neutral-700 hover:text-white' }}">
                        <i class="fas fa-tachometer-alt w-5 text-center"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('dashboard.comics.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg transition {{ request()->routeIs('dashboard.comics.*') ? 'bg-sky-500 text-white' : 'text-neutral-300 hover:bg-neutral-700 hover:text-white' }}">
                        <i class="fas fa-book w-5 text-center"></i>
                        <span>Comics</span>
                    </a>
                    <a href="{{ route('dashboard.genres.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg transition {{ request()->routeIs('dashboard.genres.*') ? 'bg-sky-500 text-white' : 'text-neutral-300 hover:bg-neutral-700 hover:text-white' }}">
                        <i class="fas fa-tags w-5 text-center"></i>
                        <span>Genres</span>
                    </a>
                    <a href="{{ route('dashboard.users.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg transition {{ request()->routeIs('dashboard.users.*') ? 'bg-sky-500 text-white' : 'text-neutral-300 hover:bg-neutral-700 hover:text-white' }}">
                        <i class="fas fa-users w-5 text-center"></i>
                        <span>Users</span>
                    </a>

                    <div class="pt-4 mt-4 border-t border-neutral-700">
                         <a href="{{ route('home') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg transition text-neutral-300 hover:bg-neutral-700 hover:text-white">
                            <i class="fas fa-home w-5 text-center"></i>
                            <span>Back to Site</span>
                        </a>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-3 px-4 py-2 rounded-lg transition text-red-400 hover:bg-neutral-700 hover:text-red-300 text-left">
                                <i class="fas fa-sign-out-alt w-5 text-center"></i>
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                </nav>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Navbar -->
            <header class="bg-neutral-800/80 backdrop-blur-md border-b border-neutral-700 sticky top-0 z-40">
                <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
                     <div class="flex items-center justify-between h-16">
                        <!-- Mobile Sidebar Toggle -->
                        <div class="flex items-center">
                            <button id="sidebar-toggle" class="md:hidden p-2 rounded-md text-neutral-400 hover:text-white hover:bg-neutral-700 focus:outline-none focus:ring-2 focus:ring-sky-500">
                                <i class="fas fa-bars"></i>
                            </button>
                            <h1 class="text-xl font-semibold text-neutral-100 ml-2 md:ml-0">@yield('page-title', 'Dashboard')</h1>
                        </div>

                         <!-- User Info -->
                         <div class="flex items-center gap-4">
                            <div class="text-right hidden sm:block">
                                <div class="text-white font-medium">{{ Auth::user()->display_name ?? Auth::user()->username }}</div>
                                <div class="text-xs text-neutral-400">{{ Auth::user()->role }}</div>
                            </div>
                            <img class="h-10 w-10 rounded-full object-cover border-2 border-neutral-600" src="{{ Auth::user()->getAvatarUrl() }}" alt="{{ Auth::user()->username }}">
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-neutral-900 p-4 sm:p-6 lg:p-8">
                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="bg-green-500/10 border border-green-500/30 text-green-400 px-4 py-3 rounded-lg relative mb-4" role="alert">
                        <strong class="font-bold"><i class="fas fa-check-circle"></i> Success!</strong>
                        <span class="block sm:inline ml-2">{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                     <div class="bg-red-500/10 border border-red-500/30 text-red-400 px-4 py-3 rounded-lg relative mb-4" role="alert">
                        <strong class="font-bold"><i class="fas fa-exclamation-circle"></i> Error!</strong>
                        <span class="block sm:inline ml-2">{{ session('error') }}</span>
                    </div>
                @endif

                @if($errors->any())
                    <div class="bg-red-500/10 border border-red-500/30 text-red-400 px-4 py-3 rounded-lg relative mb-4" role="alert">
                        <strong class="font-bold"><i class="fas fa-exclamation-triangle"></i> Whoops!</strong>
                        <span class="block sm:inline ml-2">There were some problems with your input.</span>
                        <ul class="mt-2 list-disc list-inside text-sm">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="bg-neutral-800 border border-neutral-700 rounded-2xl p-4 sm:p-6 lg:p-8">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    @stack('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebar-toggle');

            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('hidden');
            });
        });
    </script>
</body>
</html>
