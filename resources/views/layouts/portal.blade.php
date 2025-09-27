<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ mobileMenuOpen: false }" x-cloak>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'SideHunt'))</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        [x-cloak] { display: none !important; }
        
        html, body { 
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif; 
            overflow-x: hidden; /* Prevent horizontal scroll */
        }
        
        .nav-link { 
            font-size: 0.95rem; 
            font-weight: 500; 
            color: #4b5563; 
            transition: color 0.2s ease-in-out; 
        }
        .nav-link:hover { color: #111827; }
        
        /* Prevent excessive whitespace */
        .container { max-width: 100%; }
        
        /* Fix mobile layout issues */
        @media (max-width: 640px) {
            .container { padding-left: 1rem; padding-right: 1rem; }
        }
        
        /* Remove any default spacing that might cause issues */
        * {
            box-sizing: border-box;
        }
    </style>

    @stack('styles')
    @yield('css')
    @hasSection('peta')
    <style>
        @yield('peta')
    </style>
    @endif
</head>
<body class="bg-gray-50 text-gray-900">
    <div class="flex flex-col">
        <header class="bg-white border-b border-gray-200 shadow-sm sticky top-0 z-40">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    <div class="flex items-center gap-3">
                        <a href="{{ url('/') }}" class="flex items-center gap-2">
                            <img src="{{ asset('portal/img/logo.svg') }}" alt="{{ config('app.name', 'SideHunt') }}" class="h-8 w-8">
                            <span class="text-lg font-semibold text-gray-900">{{ config('app.name', 'SideHunt') }}</span>
                        </a>
                        <nav class="hidden lg:flex items-center gap-6 ml-6">
                            <a href="{{ url('/Index') }}" class="nav-link {{ request()->is('Index') ? 'text-blue-600' : '' }}">Beranda</a>
                            <a href="{{ url('/kerja') }}" class="nav-link {{ request()->is('kerja*') ? 'text-blue-600' : '' }}">Cari Pekerjaan</a>
                            @auth
                                <a href="{{ url('/management') }}" class="nav-link {{ request()->is('management*') ? 'text-blue-600' : '' }}">Management</a>
                            @endauth
                            @if(session()->has('account') && in_array(session('account')->role, ['mitra','admin','user']))
                                @if(Route::has('portal.lowongan.terdaftar'))
                                    <a href="{{ route('portal.lowongan.terdaftar') }}" class="nav-link {{ request()->routeIs('portal.lowongan.*') ? 'text-blue-600' : '' }}">Lowongan Anda</a>
                                @elseif(Route::has('portal.mitra.lowongan.terdaftar'))
                                    <a href="{{ route('portal.mitra.lowongan.terdaftar') }}" class="nav-link {{ request()->routeIs('portal.mitra.lowongan.*') ? 'text-blue-600' : '' }}">Lowongan Anda</a>
                                @endif
                            @endif
                        </nav>
                    </div>
                    <div class="flex items-center gap-4">
                        @guest
                            <a href="{{ url('/Login') }}" class="hidden sm:inline-flex items-center px-3 py-2 text-sm font-semibold text-blue-600 border border-blue-200 rounded-lg hover:bg-blue-50">Login</a>
                            <a href="{{ url('/Register') }}" class="inline-flex items-center px-3 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700">Register</a>
                        @else
                            <div class="hidden sm:flex items-center gap-4">
                                <div class="text-right">
                                    <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->nama }}</p>
                                    <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                                </div>
                                <img src="{{ asset('profiledefault.png') }}" alt="Avatar" class="h-10 w-10 rounded-full object-cover border border-gray-200">
                            </div>
                        @endguest
                        <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden inline-flex items-center justify-center rounded-md p-2 text-gray-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <span class="sr-only">Toggle navigation</span>
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" d="M3.75 5.25h16.5m-16.5 6.75h16.5m-16.5 6.75h16.5" />
                                <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div x-show="mobileMenuOpen" x-transition class="lg:hidden border-t border-gray-200 bg-white">
                <nav class="px-4 pt-4 pb-6 space-y-4">
                    <a href="{{ url('/Index') }}" class="block text-sm font-medium text-gray-700 {{ request()->is('Index') ? 'text-blue-600' : '' }}">Beranda</a>
                    <a href="{{ url('/kerja') }}" class="block text-sm font-medium text-gray-700 {{ request()->is('kerja*') ? 'text-blue-600' : '' }}">Cari Pekerjaan</a>
                    @auth
                        <a href="{{ url('/management') }}" class="block text-sm font-medium text-gray-700 {{ request()->is('management*') ? 'text-blue-600' : '' }}">Management</a>
                    @endauth
                    @if(session()->has('account') && in_array(session('account')->role, ['mitra','admin','user']))
                        @if(Route::has('portal.lowongan.terdaftar'))
                            <a href="{{ route('portal.lowongan.terdaftar') }}" class="block text-sm font-medium text-gray-700 {{ request()->routeIs('portal.lowongan.*') ? 'text-blue-600' : '' }}">Lowongan Anda</a>
                        @elseif(Route::has('portal.mitra.lowongan.terdaftar'))
                            <a href="{{ route('portal.mitra.lowongan.terdaftar') }}" class="block text-sm font-medium text-gray-700 {{ request()->routeIs('portal.mitra.lowongan.*') ? 'text-blue-600' : '' }}">Lowongan Anda</a>
                        @endif
                    @endif

                    <div class="border-t border-gray-200 pt-4">
                        @guest
                            <a href="{{ url('/Login') }}" class="block text-sm font-semibold text-gray-700">Login</a>
                            <a href="{{ url('/Register') }}" class="mt-2 inline-flex items-center px-3 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700">Register</a>
                        @else
                            <div class="flex items-center gap-3">
                                <img src="{{ asset('profiledefault.png') }}" alt="Avatar" class="h-10 w-10 rounded-full object-cover border border-gray-200">
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->nama }}</p>
                                    <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                                </div>
                            </div>
                        @endguest
                    </div>
                </div>
            </div>
        </header>

        <main>
            @yield('content')
        </main>

        @stack('scripts')
        @yield('script')
    </div>
</body>
</html>
