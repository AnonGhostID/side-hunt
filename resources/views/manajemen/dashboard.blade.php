@extends('layouts.management')

@section('title', 'Dashboard Manajemen')
@section('page-title', 'Dashboard Utama')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-500 text-white mr-4">
                    <i class="fas fa-briefcase fa-2x"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Pekerjaan Terdaftar</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $totalSideJobs }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-500 text-white mr-4">
                    <i class="fas fa-users fa-2x"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Pekerja Terdaftar</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $totalPekerja }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-500 text-white mr-4">
                    <i class="fas fa-dollar-sign fa-2x"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Transaksi (Bulan Ini)</p>
                    <p class="text-3xl font-bold text-gray-800">Rp 5.000.000</p> {{-- Ganti dengan data dinamis --}}
                </div>
            </div>
        </div>
    </div>

    <div class="mt-8 bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-xl font-semibold text-gray-700 mb-4">Selamat Datang di Panel Manajemen!</h2>
        <p class="text-gray-600">
            Ini adalah halaman dashboard utama Anda. Dari sini, Anda dapat mengakses berbagai fitur manajemen yang tersedia di sidebar.
            Silakan pilih menu yang sesuai dengan kebutuhan Anda.
        </p>
        <div class="mt-6">
            <a href="{{ route('manajemen.pekerjaan.berlangsung') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition-colors duration-300">
                <i class="fas fa-arrow-right mr-2"></i>Lihat Pekerjaan Berlangsung
            </a>
        </div>
    </div>

    {{-- Anda bisa menambahkan chart atau tabel ringkasan di sini --}}
    <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h3 class="text-lg font-semibold text-gray-700 mb-3">Aktivitas Terbaru</h3>
            <ul class="space-y-3">
                <li class="flex items-center text-sm text-gray-600">
                    <i class="fas fa-user-plus text-green-500 mr-3"></i> Pengguna baru 'John Doe' mendaftar. <span class="ml-auto text-xs text-gray-400">2 jam lalu</span>
                </li>
                <li class="flex items-center text-sm text-gray-600">
                    <i class="fas fa-file-alt text-blue-500 mr-3"></i> Laporan pekerjaan 'Desain Logo' diunggah. <span class="ml-auto text-xs text-gray-400">5 jam lalu</span>
                </li>
                <li class="flex items-center text-sm text-gray-600">
                    <i class="fas fa-credit-card text-yellow-500 mr-3"></i> Pembayaran untuk 'Proyek Web' berhasil. <span class="ml-auto text-xs text-gray-400">1 hari lalu</span>
                </li>
            </ul>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h3 class="text-lg font-semibold text-gray-700 mb-3">Statistik Cepat</h3>
            {{-- Placeholder untuk chart, bisa menggunakan Chart.js atau ApexCharts --}}
            <div class="text-center text-gray-500 py-10">
                <i class="fas fa-chart-pie fa-3x mb-2"></i>
                <p>Chart akan ditampilkan di sini</p>
            </div>
        </div>
    </div>

</div>
@endsection
