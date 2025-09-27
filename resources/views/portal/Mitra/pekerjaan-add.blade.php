@extends('layouts.portal')

@section('title', 'Buat Lowongan Baru')
@section('page-title', 'Buat Lowongan Baru')
@section('page-subtitle')
    Lengkapi detail pekerjaan agar kandidat dapat memahami kebutuhan Anda dengan jelas.
@endsection

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet.locatecontrol/dist/L.Control.Locate.min.css" />
    <link rel="stylesheet" href="https://unpkg.com/esri-leaflet-geocoder@3.1.4/dist/esri-leaflet-geocoder.css">
@endpush

@section('content')
<form action="/kerja/add" method="POST" enctype="multipart/form-data" class="space-y-10">
    @csrf

    <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-semibold text-gray-900">Informasi Utama</h2>
        <p class="mt-1 text-sm text-gray-500">Berikan gambaran singkat mengenai pekerjaan yang Anda tawarkan.</p>
        <div class="mt-6 grid gap-6 md:grid-cols-2">
            <div class="md:col-span-2">
                <label for="nama" class="block text-sm font-semibold text-gray-700">Nama Pekerjaan<span class="text-red-500">*</span></label>
                <input id="nama" type="text" name="nama" value="{{ old('nama') }}" maxlength="60" required class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-3 text-sm text-gray-700 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 @error('nama') border-red-300 focus:border-red-400 focus:ring-red-200 @enderror" placeholder="Contoh: Barista shift pagi">
                @error('nama')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="min_gaji" class="block text-sm font-semibold text-gray-700">Gaji Minimal<span class="text-red-500">*</span></label>
                <input id="min_gaji" type="number" name="min_gaji" value="{{ old('min_gaji') }}" maxlength="20" required class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-3 text-sm text-gray-700 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 @error('min_gaji') border-red-300 focus:border-red-400 focus:ring-red-200 @enderror" placeholder="Contoh: 150000">
                @error('min_gaji')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="max_gaji" class="block text-sm font-semibold text-gray-700">Gaji Maksimal<span class="text-red-500">*</span></label>
                <input id="max_gaji" type="number" name="max_gaji" value="{{ old('max_gaji') }}" maxlength="20" required class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-3 text-sm text-gray-700 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 @error('max_gaji') border-red-300 focus:border-red-400 focus:ring-red-200 @enderror" placeholder="Contoh: 200000">
                @error('max_gaji')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="start_job" class="block text-sm font-semibold text-gray-700">Tanggal Mulai<span class="text-red-500">*</span></label>
                <input id="start_job" type="datetime-local" name="start_job" value="{{ old('start_job') }}" required class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-3 text-sm text-gray-700 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 @error('start_job') border-red-300 focus:border-red-400 focus:ring-red-200 @enderror">
                @error('start_job')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="end_job" class="block text-sm font-semibold text-gray-700">Tanggal Selesai<span class="text-red-500">*</span></label>
                <input id="end_job" type="datetime-local" name="end_job" value="{{ old('end_job') }}" required class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-3 text-sm text-gray-700 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 @error('end_job') border-red-300 focus:border-red-400 focus:ring-red-200 @enderror">
                @error('end_job')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="deadline_job" class="block text-sm font-semibold text-gray-700">Deadline Lamaran</label>
                <input id="deadline_job" type="datetime-local" name="deadline_job" value="{{ old('deadline_job') }}" class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-3 text-sm text-gray-700 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 @error('deadline_job') border-red-300 focus:border-red-400 focus:ring-red-200 @enderror">
                @error('deadline_job')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="max_pekerja" class="block text-sm font-semibold text-gray-700">Jumlah Pekerja Diterima<span class="text-red-500">*</span></label>
                <input id="max_pekerja" type="number" name="max_pekerja" value="{{ old('max_pekerja') }}" maxlength="10" required class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-3 text-sm text-gray-700 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 @error('max_pekerja') border-red-300 focus:border-red-400 focus:ring-red-200 @enderror" placeholder="Contoh: 5">
                @error('max_pekerja')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </section>

    <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-semibold text-gray-900">Lokasi Pekerjaan</h2>
        <p class="mt-1 text-sm text-gray-500">Tentukan alamat dan tandai lokasi pada peta agar kandidat mudah menemukan lokasi kerja.</p>
        <div class="mt-6 space-y-5">
            <div>
                <label for="alamat" class="block text-sm font-semibold text-gray-700">Alamat Lengkap<span class="text-red-500">*</span></label>
                <input id="alamat" type="text" name="alamat" value="{{ old('alamat') }}" maxlength="100" required class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-3 text-sm text-gray-700 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 @error('alamat') border-red-300 focus:border-red-400 focus:ring-red-200 @enderror" placeholder="Tuliskan alamat lokasi pekerjaan">
                @error('alamat')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="petunjuk_alamat" class="block text-sm font-semibold text-gray-700">Petunjuk Lokasi<span class="text-red-500">*</span></label>
                <p class="mt-1 text-xs text-gray-500">Contoh: dekat masjid utama, berada di samping minimarket.</p>
                <input id="petunjuk_alamat" type="text" name="petunjuk_alamat" value="{{ old('petunjuk_alamat') }}" maxlength="100" required class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-3 text-sm text-gray-700 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 @error('petunjuk_alamat') border-red-300 focus:border-red-400 focus:ring-red-200 @enderror">
                @error('petunjuk_alamat')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
            <div class="space-y-3">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <p class="text-sm font-semibold text-gray-700">Tandai Lokasi pada Peta<span class="text-red-500">*</span></p>
                        <p class="text-xs text-gray-500">Klik pada peta atau gunakan tombol di bawah untuk menggunakan lokasi Anda.</p>
                    </div>
                    <div class="flex gap-2">
                        <
