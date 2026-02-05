<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'MyKomik')</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="bg-neutral-900 text-neutral-100 min-h-screen flex flex-col font-sans antialiased">
    <nav class="bg-neutral-800/80 backdrop-blur-md border-b border-neutral-700 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Logo & Brand -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-2 text-xl font-bold text-sky-500 hover:text-sky-400 transition">
                        <span>MyKomik</span>
                    </a>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-6">
                    <a href="{{ route('home') }}" class="text-sm font-medium {{ request()->routeIs('home') ? 'text-sky-500' : 'text-neutral-300 hover:text-white' }} transition">
                        Beranda
                    </a>

                    @auth
                        <a href="{{ route('library') }}" class="text-sm font-medium {{ request()->routeIs('library') ? 'text-sky-500' : 'text-neutral-300 hover:text-white' }} transition">
                            Koleksiku
                        </a>
                        @if(Auth::user()->role === 'admin')
                            <a href="{{ route('dashboard.index') }}" class="text-sm font-medium text-neutral-300 hover:text-white transition flex items-center gap-1">
                                Dasboard
                            </a>
                        @endif

                        <!-- User Dropdown -->
                        <div class="relative ml-3">
                            <button type="button" class="flex items-center gap-2 text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 focus:ring-offset-neutral-800" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                <span class="sr-only">Open user menu</span>
                                <img class="h-8 w-8 rounded-full object-cover border border-neutral-600" src="{{ Auth::user()->getAvatarUrl() }}" alt="{{ Auth::user()->username }}">
                                <span class="hidden lg:block text-neutral-300 font-medium">{{ Auth::user()->display_name ?? Auth::user()->username }}</span>
                                <i class="fas fa-chevron-down text-xs text-neutral-400 hidden lg:block"></i>
                            </button>

                            <!-- Dropdown menu -->
                            <div class="hidden absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-neutral-800 py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none border border-neutral-700" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1" id="user-menu-dropdown">
                                <a href="{{ route('profile.show') }}" class="px-4 py-2 text-sm text-neutral-300 hover:bg-neutral-700 hover:text-white flex items-center gap-2" role="menuitem">
                                    <i class="fas fa-user-circle w-4"></i> Profil Saya
                                </a>
                                <div class="border-t border-neutral-700 my-1"></div>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-400 hover:bg-neutral-700 hover:text-red-300 flex items-center gap-2" role="menuitem">
                                        <i class="fas fa-sign-out-alt w-4"></i> Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-white bg-sky-600 rounded-full hover:bg-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 focus:ring-offset-neutral-800 transition shadow-lg shadow-sky-500/20">
                            Masuk
                        </a>
                    @endauth
                </div>

                <!-- Mobile menu button -->
                <div class="flex items-center md:hidden">
                    <button type="button" class="inline-flex items-center justify-center p-2 rounded-md text-neutral-400 hover:text-white hover:bg-neutral-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-sky-500 transition" aria-controls="mobile-menu" aria-expanded="false" id="mobile-menu-button">
                        <span class="sr-only">Open main menu</span>
                        <!-- Icon when menu is closed -->
                        <svg class="block h-6 w-6" id="menu-open-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                        <!-- Icon when menu is open -->
                        <svg class="hidden h-6 w-6" id="menu-close-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu, show/hide based on menu state. -->
        <div class="hidden md:hidden border-t border-neutral-700 bg-neutral-800" id="mobile-menu">
            <div class="space-y-1 px-2 pt-2 pb-3">
                <a href="{{ route('home') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('home') ? 'bg-neutral-900 text-white' : 'text-neutral-300 hover:bg-neutral-700 hover:text-white' }}">Beranda</a>
                @auth
                    <a href="{{ route('library') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('library') ? 'bg-neutral-900 text-white' : 'text-neutral-300 hover:bg-neutral-700 hover:text-white' }}">Koleksiku</a>
                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('dashboard.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-neutral-300 hover:bg-neutral-700 hover:text-white">Dasbor</a>
                    @endif

                    <div class="border-t border-neutral-700 my-2 pt-2">
                        <div class="flex items-center px-3 mb-3">
                            <div class="shrink-0">
                                <img class="h-10 w-10 rounded-full object-cover border border-neutral-600" src="{{ Auth::user()->getAvatarUrl() }}" alt="{{ Auth::user()->username }}">
                            </div>
                            <div class="ml-3">
                                <div class="text-base font-medium leading-none text-white">{{ Auth::user()->display_name ?? Auth::user()->username }}</div>
                                <div class="text-sm font-medium leading-none text-neutral-400 mt-1">{{ Auth::user()->email }}</div>
                            </div>
                        </div>
                        <a href="{{ route('profile.show') }}" class="block px-3 py-2 rounded-md text-base font-medium text-neutral-300 hover:bg-neutral-700 hover:text-white">Profil Saya</a>
                        <form action="{{ route('logout') }}" method="POST" class="mt-1">
                            @csrf
                            <button type="submit" class="block w-full text-left px-3 py-2 rounded-md text-base font-medium text-red-400 hover:bg-neutral-700 hover:text-red-300">Keluar</button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="block w-full text-center px-3 py-2 mt-4 rounded-md text-base font-medium text-white bg-sky-600 hover:bg-sky-500">Masuk</a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="grow">
        @yield('content')
    </main>

    <footer class="bg-neutral-800 border-t border-neutral-700 py-8 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-neutral-400 text-sm">&copy; {{ date('Y') }} MyKomik. Hak cipta dilindungi.</p>
        </div>
    </footer>

    <!-- Layout Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // User Dropdown Toggle
            const userMenuButton = document.getElementById('user-menu-button');
            const userMenuDropdown = document.getElementById('user-menu-dropdown');

            if (userMenuButton && userMenuDropdown) {
                userMenuButton.addEventListener('click', function (e) {
                    e.stopPropagation();
                    const isExpanded = userMenuButton.getAttribute('aria-expanded') === 'true';
                    userMenuButton.setAttribute('aria-expanded', !isExpanded);
                    userMenuDropdown.classList.toggle('hidden');
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', function (event) {
                    if (!userMenuButton.contains(event.target) && !userMenuDropdown.contains(event.target)) {
                        userMenuDropdown.classList.add('hidden');
                        userMenuButton.setAttribute('aria-expanded', 'false');
                    }
                });
            }

            // Mobile Menu Toggle
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            const menuOpenIcon = document.getElementById('menu-open-icon');
            const menuCloseIcon = document.getElementById('menu-close-icon');

            if (mobileMenuButton && mobileMenu && menuOpenIcon && menuCloseIcon) {
                mobileMenuButton.addEventListener('click', function () {
                    const isExpanded = mobileMenuButton.getAttribute('aria-expanded') === 'true';

                    // Toggle aria-expanded
                    mobileMenuButton.setAttribute('aria-expanded', !isExpanded);

                    // Toggle menu visibility
                    mobileMenu.classList.toggle('hidden');

                    // Toggle icons
                    if (mobileMenu.classList.contains('hidden')) {
                        // Menu is closed
                        menuOpenIcon.classList.remove('hidden');
                        menuOpenIcon.classList.add('block');
                        menuCloseIcon.classList.remove('block');
                        menuCloseIcon.classList.add('hidden');
                    } else {
                        // Menu is open
                        menuOpenIcon.classList.remove('block');
                        menuOpenIcon.classList.add('hidden');
                        menuCloseIcon.classList.remove('hidden');
                        menuCloseIcon.classList.add('block');
                    }
                });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
