<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Manajemen Panel - ' . config('app.name', 'Laravel'))</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" xintegrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />


    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            border-radius: 0.375rem;
            transition: background-color 0.2s ease-in-out, color 0.2s ease-in-out;
            color: #6b7280; /* gray-500 */
        }
        .sidebar-link:hover {
            background-color: #e5e7eb; /* gray-200 */
            color: #1f2937; /* gray-800 */
        }
        .sidebar-link.active {
            background-color: #3b82f6; /* blue-500 */
            color: white;
            font-weight: 500;
        }
        .sidebar-link.active i {
            color: white;
        }
        .sidebar-link i {
            margin-right: 0.75rem;
            width: 1.25rem; /* w-5 */
            text-align: center;
            color: #9ca3af; /* gray-400 */
        }
        .sidebar-link.active i {
            color: white;
        }
        /* Custom scrollbar for webkit browsers */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
</head>
<body class="bg-gray-100 antialiased">
    <div x-data="{ sidebarOpen: false }" class="flex h-screen bg-gray-100">
        <aside
            class="fixed inset-y-0 left-0 z-30 flex flex-col h-full w-64 transform transition-transform duration-300 ease-in-out bg-white border-r border-gray-200 shadow-lg lg:translate-x-0 lg:static lg:inset-0"
            :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}">
            <div class="flex items-center justify-between p-4 h-16 border-b border-gray-200">
                <a href="{{ route('manajemen.dashboard') }}" class="text-2xl font-bold text-blue-600">
                    {{ config('app.name', 'SideHunt') }}
                </a>
                <button @click="sidebarOpen = false" class="text-gray-500 lg:hidden">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
                <a href="{{ route('manajemen.dashboard') }}" class="sidebar-link {{ request()->routeIs('manajemen.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>

                <h3 class="mt-4 mb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Pekerjaan</h3>
                <a href="{{ route('manajemen.pekerjaan.berlangsung') }}" class="sidebar-link {{ request()->routeIs('manajemen.pekerjaan.berlangsung') ? 'active' : '' }}">
                    <i class="fas fa-briefcase"></i>
                    <span>Pekerjaan Berlangsung</span>
                </a>
                <a href="{{ route('manajemen.laporan.upload') }}" class="sidebar-link {{ request()->routeIs('manajemen.laporan.upload') ? 'active' : '' }}">
                    <i class="fas fa-file-upload"></i>
                    <span>Upload Laporan Hasil</span>
                </a>
                 <a href="{{ route('manajemen.pekerjaan.riwayat') }}" class="sidebar-link {{ request()->routeIs('manajemen.pekerjaan.riwayat') ? 'active' : '' }}">
                    <i class="fas fa-history"></i>
                    <span>Riwayat Pekerjaan</span>
                </a>


                <h3 class="mt-4 mb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Keuangan</h3>
                <a href="{{ route('manajemen.topUp') }}" class="sidebar-link {{ request()->routeIs('manajemen.topUp') ? 'active' : '' }}">
                    <i class="fas fa-credit-card"></i>
                    <span>Top Up Saldo</span>
                </a>
                 <a href="{{ route('manajemen.tarik_saldo') }}" class="sidebar-link {{ request()->routeIs('manajemen.tarik_saldo') ? 'active' : '' }}">
                    <i class="fas fa-money-bill-transfer"></i>
                    <span>Tarik Saldo</span>
                </a>
                <a href="{{ route('manajemen.transaksi.riwayat') }}" class="sidebar-link {{ request()->routeIs('manajemen.transaksi.riwayat') ? 'active' : '' }}">
                    <i class="fas fa-receipt"></i>
                    <span>Riwayat Transaksi</span>
                </a>
                <!-- <a href="{{ route('manajemen.dana.refund') }}" class="sidebar-link {{ request()->routeIs('manajemen.dana.refund') ? 'active' : '' }}">
                    <i class="fas fa-undo-alt"></i>
                    <span>Refund Dana</span>
                </a> -->
                <a href="{{ route('manajemen.keuangan.laporan') }}" class="sidebar-link {{ request()->routeIs('manajemen.keuangan.laporan') ? 'active' : '' }}">
                    <i class="fas fa-chart-line"></i>
                    <span>Laporan Keuangan</span>
                </a>

                <h3 class="mt-4 mb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Pelaporan & Bantuan</h3>
                 <a href="{{ route('manajemen.pelaporan.penipuan.form') }}" class="sidebar-link {{ request()->routeIs('manajemen.pelaporan.penipuan.form') ? 'active' : '' }}">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>Lapor Indikasi Penipuan</span>
                </a>
                <a href="{{ route('manajemen.bantuan.panel') }}" class="sidebar-link {{ request()->routeIs('manajemen.bantuan.panel') ? 'active' : '' }}">
                    <i class="fas fa-question-circle"></i>
                    <span>Panel Bantuan</span>
                </a>

                {{-- Admin Section Example - Ideally show based on user role --}}
                @auth
                    @if(auth()->user()->isAdmin()) {{-- Anda perlu menambahkan method isAdmin() di model User --}}
                        <h3 class="mt-4 mb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Administrasi Sistem</h3>
                        <a href="{{ route('manajemen.admin.laporan.pemantauan') }}" class="sidebar-link {{ request()->routeIs('manajemen.admin.laporan.pemantauan') ? 'active' : '' }}">
                            <i class="fas fa-binoculars"></i>
                            <span>Pemantauan Laporan</span>
                        </a>
                        <div x-data="{ open: {{ request()->is('manajemen/admin/users*') ? 'true' : 'false' }} }">
                            <button @click="open = !open" class="sidebar-link w-full text-left">
                                <i class="fas fa-users-cog"></i>
                                <span>Manajemen Pengguna</span>
                                <i class="fas" :class="open ? 'fa-chevron-down ml-auto' : 'fa-chevron-right ml-auto'"></i>
                            </button>
                            <div x-show="open" x-transition class="ml-4 mt-1 space-y-1">
                                <a href="{{ route('manajemen.admin.users.list') }}" class="sidebar-link {{ request()->routeIs('manajemen.admin.users.list') ? 'active' : '' }}">
                                    <i class="fas fa-list-ul"></i> Daftar User
                                </a>
                                <a href="{{ route('manajemen.admin.users.tambah') }}" class="sidebar-link {{ request()->routeIs('manajemen.admin.users.tambah') ? 'active' : '' }}">
                                    <i class="fas fa-user-plus"></i> Tambah User
                                </a>
                                {{-- Tambahkan link untuk Nonaktifkan, Aktifkan, Ubah User di sini jika halaman terpisah --}}
                            </div>
                        </div>
                    @endif
                @endauth


                <h3 class="mt-4 mb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Lainnya</h3>
                 <a href="{{ route('manajemen.notifikasi.pekerjaan') }}" class="sidebar-link {{ request()->routeIs('manajemen.notifikasi.pekerjaan') ? 'active' : '' }}">
                    <i class="fas fa-bell"></i>
                    <span>Notifikasi Status Pekerjaan</span>
                </a>
                <a href="{{ route('manajemen.notifikasi.pelamaran') }}" class="sidebar-link {{ request()->routeIs('manajemen.notifikasi.pelamaran') ? 'active' : '' }}">
                    <i class="fas fa-bell"></i>
                    <span>Notifikasi Status Pelamaran</span>
                </a>
                 <a href="{{ route('manajemen.chat') }}" class="sidebar-link {{ request()->routeIs('manajemen.chat') ? 'active' : '' }}">
                    <i class="fas fa-comments"></i>
                    <span>Chat Antar Pengguna</span>
                </a>
                <a href="{{ route('manajemen.rating.user') }}" class="sidebar-link {{ request()->routeIs('manajemen.rating.user') ? 'active' : '' }}">
                    <i class="fas fa-star"></i>
                    <span>Beri Rating Pengguna</span>
                </a>
                <a href="{{ route('manajemen.pelamar.track-record') }}" class="sidebar-link {{ request()->routeIs('manajemen.pelamar.track-record') ? 'active' : '' }}">
                    <i class="fas fa-address-book"></i>
                    <span>Track Record Pelamar</span>
                </a>


            </nav>

            <div class="p-4 border-t border-gray-200">
                @auth
                <div class="flex items-center mb-3">
                    <img src="{{ asset('img/progress.png') }}"class="w-10 h-10 rounded-full mr-3 object-cover">
                    <div>
                        <p class="text-sm font-medium text-gray-700">{{ Auth::user()->nama }}</p>
                        <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                    </div>
                </div>
                <a href="/Logout"
                   class="sidebar-link bg-red-50 hover:bg-red-100 text-red-600">
                    <i class="fas fa-sign-out-alt text-red-500"></i>
                    <span>Logout</span>
                </a>
                <!-- <form id="logout-form" action="" method="POST" class="d-none">
                    @csrf
                </form> -->
                @endauth
            </div>
        </aside>

        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="flex items-center justify-between p-4 h-16 bg-white border-b border-gray-200 shadow-sm">
                <button @click="sidebarOpen = true" class="text-gray-500 focus:outline-none lg:hidden">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="text-xl font-semibold text-gray-700">@yield('page-title', 'Dashboard')</div>
                <div>
                    {{-- Additional navbar items can go here --}}
                    <a href="{{ url('/') }}" class="text-blue-500 hover:text-blue-700">
                        <i class="fas fa-home"></i> Kembali ke Beranda
                    </a>
                </div>
            </header>

            <main class="flex-1 p-6 overflow-x-hidden overflow-y-auto bg-gray-100">
                @if (session('success'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-md" role="alert">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-md" role="alert">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script src="{{ asset('js/balance-update.js') }}"></script>
    @stack('scripts')
</body>
</html>
