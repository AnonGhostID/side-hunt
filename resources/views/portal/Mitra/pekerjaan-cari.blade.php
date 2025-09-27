@extends('layouts.portal')

@section('title', 'Cari Lowongan')
@section('page-title', 'Cari Pekerjaan')
@section('page-subtitle')
    Jelajahi lowongan yang tersedia dan temukan pekerjaan yang paling relevan untuk Anda.
@endsection

@section('content')
<div class="space-y-10">
    <section class="rounded-2xl border border-blue-100 bg-gradient-to-r from-blue-50 via-white to-blue-50 p-8 shadow-sm">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h2 class="text-2xl font-semibold text-gray-900">Pencarian Pekerjaan</h2>
                <p class="mt-2 text-sm text-gray-600">Masukkan kata kunci atau lokasi untuk mempersempit hasil pencarian lowongan.</p>
            </div>
            <div class="w-full max-w-xl">
                <label for="search" class="sr-only">Cari pekerjaan</label>
                <div class="relative flex items-center">
                    <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-gray-400">
                        <i class="fas fa-search"></i>
                    </span>
                    <input id="search" type="text" placeholder="Nama pekerjaan, lokasi, atau kata kunci" class="w-full rounded-xl border border-gray-200 bg-white px-12 py-3 text-sm text-gray-700 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                    <button type="button" class="ml-3 inline-flex items-center rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2">
                        Telusuri
                    </button>
                </div>
            </div>
        </div>
    </section>

    <section class="space-y-5">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Pekerjaan Disarankan</h3>
                <p class="text-sm text-gray-500">Kami memilih lowongan yang sesuai dengan preferensi dan riwayat Anda.</p>
            </div>
            <span class="text-sm text-gray-400">{{ count($match) }} rekomendasi</span>
        </div>

        <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
            @php
if (!function_exists('portal_distance')) {
    function portal_distance($lon1, $lat1, $lon2 = null, $lat2 = null)
    {
        if (!$lon1 || !$lat1 || !$lon2 || !$lat2) {
            return 'Lokasi belum lengkap';
        }

        $earthRadius = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) ** 2 +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) ** 2;

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earthRadius * $c;

        return number_format($distance, 2) . ' km';
    }
}
@endphp
@forelse($match as $key => $score)
                <a href="{{ $all[$key]['id'] }}" class="group flex flex-col rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:border-blue-200 hover:shadow-md">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-900 group-hover:text-blue-600">{{ $all[$key]['nama'] }}</p>
                            <p class="mt-1 text-xs text-gray-500">{{ $all[$key]['alamat'] ?? 'Alamat belum tersedia' }}</p>
                        </div>
                        <span class="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-1 text-[11px] font-medium text-blue-600">Rekomendasi</span>
                    </div>
                    <div class="mt-4 flex items-center justify-between text-xs text-gray-500">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-location-dot text-blue-500"></i>
                            <span>{{ portal_distance($all[$key]['longitude'], $all[$key]['langitude'], $accountLongitude, $accountLatitude) }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span>Klik untuk detail</span>
                            <i class="fas fa-arrow-up-right-from-square text-blue-500"></i>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-full rounded-2xl border border-dashed border-gray-200 bg-white p-10 text-center text-sm text-gray-500">
                    Belum ada rekomendasi yang tersedia saat ini. Lengkapi preferensi kerja Anda untuk mendapatkan saran yang lebih akurat.
                </div>
            @endforelse
        </div>
    </section>
</div>
@endsection

@php
if (!function_exists('portal_distance')) {
    function portal_distance($lon1, $lat1, $lon2, $lat2)
{
    if (!$lon1 || !$lat1 || !$lon2 || !$lat2) {
        return 'Lokasi belum lengkap';
    }

        $earthRadius = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) ** 2 +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) ** 2;

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earthRadius * $c;

        return number_format($distance, 2) . ' km';
    }
}
@endphp
