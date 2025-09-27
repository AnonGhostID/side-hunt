@extends('layouts.portal')

@section('title', $job->nama)
@section('page-title', $job->nama)
@section('page-subtitle')
    {{ $job->alamat }}
@endsection

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet.locatecontrol/dist/L.Control.Locate.min.css" />
    <link rel="stylesheet" href="https://unpkg.com/esri-leaflet-geocoder@3.1.4/dist/esri-leaflet-geocoder.css">
    <style>
        #map { height: 300px; width: 100%; border-radius: 0.75rem; }
    </style>
@endpush

@section('content')
@php
    $user = session('account');
    $userApplied = null;

    if ($user) {
        $userApplied = app('App\Models\Pelamar')
            ->where('job_id', $job->id)
            ->where('user_id', $user['id'])
            ->first();
    }
@endphp

<div class="mx-auto w-full max-w-4xl space-y-8">
    <section class="rounded-2xl border border-gray-200 bg-white shadow-sm">
        <div class="border-b border-gray-100 bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-6 text-white">
            <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
                <div>
                    <h2 class="text-2xl font-semibold">{{ $job->nama }}</h2>
                    <p class="mt-1 text-sm text-blue-100">{{ $job->alamat }}</p>
                </div>
                <div class="rounded-xl bg-white/10 px-4 py-2 text-sm font-medium">
                    Dibuat pada {{ $job->created_at->format('d M Y') }}
                </div>
            </div>
        </div>

        <div class="grid gap-6 px-6 py-8 md:grid-cols-2">
            <div class="space-y-5">
                <div>
                    <p class="text-sm font-medium text-gray-500">Rentang Gaji</p>
                    <p class="mt-1 text-xl font-semibold text-gray-900">
                        Rp {{ number_format($job->min_gaji, 0, ',', '.') }}
                        @if($job->min_gaji !== $job->max_gaji)
                            – Rp {{ number_format($job->max_gaji, 0, ',', '.') }}
                        @endif
                    </p>
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Mulai</p>
                        <p class="mt-2 text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($job->start_job)->format('d M Y H:i') }}</p>
                    </div>
                    <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Berakhir</p>
                        <p class="mt-2 text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($job->end_job)->format('d M Y H:i') }}</p>
                        @if($job->deadline_job)
                            <p class="mt-2 text-xs font-medium text-red-600">Lamaran ditutup {{ \Carbon\Carbon::parse($job->deadline_job)->diffForHumans() }}</p>
                        @endif
                    </div>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Jumlah Pekerja Dibutuhkan</p>
                    <p class="mt-1 text-sm font-semibold text-gray-900">{{ $job->max_pekerja }} orang</p>
                </div>
            </div>

            <div class="space-y-5">
                <div>
                    <p class="text-sm font-medium text-gray-500">Lokasi Pekerjaan</p>
                    <p class="mt-1 text-sm text-gray-700">{{ $job->alamat }}</p>
                    <p class="mt-1 text-sm font-medium text-gray-500">Petunjuk Lokasi</p>
                    <p class="mt-1 text-sm text-gray-700">{{ $job->petunjuk_alamat ?? 'Tidak ada keterangan tambahan.' }}</p>
                </div>
                @if($job->latitude && $job->longitude)
                    <div class="rounded-xl border border-gray-200 overflow-hidden">
                        <div id="map" class="w-full"></div>
                    </div>
                @else
                    <div class="rounded-xl border border-gray-200 bg-gray-50 p-6 text-center">
                        <p class="text-sm text-gray-500">Koordinat lokasi tidak tersedia pada pekerjaan ini.</p>
                    </div>
                @endif
                @if($job->foto_job)
                    <div class="overflow-hidden rounded-2xl border border-gray-200">
                        <img src="{{ asset('storage/' . $job->foto_job) }}" alt="Foto Pekerjaan" class="h-48 w-full object-cover">
                    </div>
                @endif
            </div>
        </div>

        <div class="border-t border-gray-100 px-6 py-6">
            <p class="text-sm font-medium text-gray-700">Deskripsi Pekerjaan</p>
            <div class="prose prose-sm mt-3 max-w-none text-gray-700">
                {!! nl2br(e($job->deskripsi)) !!}
            </div>
        </div>
    </section>

    <section class="rounded-2xl border border-gray-200 bg-white px-6 py-6 shadow-sm">
        @if(!$user)
            <div class="flex flex-col items-center gap-4 text-center">
                <p class="text-sm text-gray-600">Masuk untuk melamar pekerjaan ini.</p>
                <a href="/Login" class="inline-flex items-center rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2">
                    <i class="fas fa-sign-in-alt mr-2"></i> Login untuk Melamar
                </a>
            </div>
        @elseif($job->pembuat == $user['id'])
            <div class="rounded-xl border border-blue-100 bg-blue-50 px-4 py-3 text-sm text-blue-700">
                <i class="fas fa-circle-info mr-2"></i>Ini adalah pekerjaan yang Anda buat.
            </div>
        @elseif($userApplied)
            <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-4 text-sm text-amber-700">
                @switch($userApplied->status)
                    @case('pending')
                        <i class="fas fa-clock mr-2"></i>Lamaran Anda sedang ditinjau.
                        @break
                    @case('diterima')
                        <i class="fas fa-check-circle mr-2"></i>Selamat! Lamaran Anda telah <strong>diterima</strong>.
                        @break
                    @case('ditolak')
                        <i class="fas fa-xmark-circle mr-2"></i>Maaf, lamaran Anda <strong>ditolak</strong>.
                        @break
                    @default
                        <i class="fas fa-info-circle mr-2"></i>Status lamaran: {{ ucfirst($userApplied->status) }}.
                @endswitch
                <p class="mt-2 text-xs text-amber-600">Dilamar pada {{ $userApplied->created_at->format('d M Y H:i') }}</p>
            </div>
        @else
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <p class="text-sm text-gray-600">Tertarik? Ajukan lamaran dengan sekali klik.</p>
                <button type="button" onclick="lamarPekerjaan({{ $job->id }})" class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:ring-offset-2">
                    <i class="fas fa-paper-plane mr-2"></i>Lamar Pekerjaan
                </button>
            </div>
        @endif
    </section>

    <button type="button" onclick="window.history.back()" class="inline-flex items-center rounded-xl border border-gray-200 bg-white px-5 py-3 text-sm font-semibold text-gray-600 shadow-sm transition hover:text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-200">
        <i class="fas fa-arrow-left mr-2"></i> Kembali
    </button>
</div>
@endsection

@section('script')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
    <script src="https://unpkg.com/esri-leaflet@3.0.12/dist/esri-leaflet.js"></script>
    <script src="https://unpkg.com/esri-leaflet-vector@4.2.3/dist/esri-leaflet-vector.js"></script>
    <script src="https://unpkg.com/esri-leaflet-geocoder@3.1.4/dist/esri-leaflet-geocoder.js"></script>
    <script>
        function lamarPekerjaan(jobId) {
            if (!confirm('Ajukan lamaran untuk pekerjaan ini?')) {
                return;
            }

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/kerja/lamar/${jobId}`;

            const token = document.createElement('input');
            token.type = 'hidden';
            token.name = '_token';
            token.value = '{{ csrf_token() }}';

            form.appendChild(token);
            document.body.appendChild(form);
            form.submit();
        }

        @if($job->latitude && $job->longitude)
        // Initialize map for job location
        const apiKey = 'AAPK3e52398025234807add84f416a03c213CPb7ak6zNzwQYIBhQ9PIx-oBY_1mtsbVR1klbU-RrJ6TWtK5mP28C-lfmNqfndnS';
        const map = L.map('map', { minZoom: 2 }).setView([{{ $job->latitude }}, {{ $job->longitude }}], 15);

        L.esri.Vector.vectorBasemapLayer('arcgis/navigation', { apiKey }).addTo(map);

        // Add marker at job location
        const marker = L.marker([{{ $job->latitude }}, {{ $job->longitude }}]).addTo(map);
        marker.bindPopup(`
            <div class="space-y-2">
                <p class="text-sm font-semibold text-gray-900">${ "{{ $job->nama }}" }</p>
                <p class="text-xs text-gray-500">${ "{{ $job->alamat }}" }</p>
            </div>
        `);
        marker.openPopup();

        // Ensure proper map sizing
        setTimeout(() => {
            map.invalidateSize();
        }, 100);
        @endif
    </script>
@endsection
