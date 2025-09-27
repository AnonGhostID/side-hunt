@extends('layouts.portal')

@section('title', 'Preferensi Kerja')
@section('page-title', 'Preferensi & Kemampuan Kerja')
@section('page-subtitle')
    Lengkapi beberapa preferensi agar kami bisa menampilkan lowongan yang selaras dengan kebutuhan Anda.
@endsection

@php
    $questions = [
        'job_distance' => [
            'label' => 'Jarak kerja yang Anda bersedia tempuh',
            'options' => [
                '0' => 'hanya remote',
                '1' => 'kurang dari atau sama dengan 1 km',
                '2' => '1–5 km',
                '3' => '6–10 km',
                '4' => '11–20 km',
                '5' => 'lebih dari 20 km',
            ],
        ],
        'expected_rate' => [
            'label' => 'Minimal upah yang Anda harapkan',
            'options' => [
                '0' => 'kurang dari atau sama dengan 20rb',
                '1' => 'kurang dari atau sama dengan 30rb',
                '2' => 'kurang dari atau sama dengan 40rb',
                '3' => 'kurang dari atau sama dengan 50rb',
                '4' => 'kurang dari atau sama dengan 75rb',
                '5' => 'lebih dari 75rb',
            ],
        ],
        'daily_hours' => [
            'label' => 'Waktu kerja per hari yang bisa diluangkan',
            'options' => [
                '0' => 'kurang dari 1 jam',
                '1' => '1–2 jam',
                '2' => '3–4 jam',
                '3' => '5–6 jam',
                '4' => '7–8 jam',
                '5' => 'lebih dari 8 jam',
            ],
        ],
        'project_duration' => [
            'label' => 'Durasi proyek yang Anda inginkan',
            'options' => [
                '0' => '1 hari',
                '1' => 'kurang dari atau sama dengan 3 hari',
                '2' => 'kurang dari atau sama dengan 1 minggu',
                '3' => 'kurang dari atau sama dengan 2 minggu',
                '4' => 'kurang dari atau sama dengan 1 bulan',
                '5' => 'lebih dari 1 bulan',
            ],
        ],
        'work_method' => [
            'label' => 'Preferensi metode kerja',
            'options' => [
                '0' => 'hanya on-site',
                '1' => 'lebih suka on-site',
                '2' => 'fleksibel cenderung on-site',
                '3' => 'fleksibel netral',
                '4' => 'fleksibel cenderung remote',
                '5' => 'hanya remote',
            ],
        ],
        'experience_length' => [
            'label' => 'Lama pengalaman kerja Anda',
            'options' => [
                '0' => 'tidak ada',
                '1' => 'kurang dari 3 bulan',
                '2' => '3–6 bulan',
                '3' => '6–12 bulan',
                '4' => '1–2 tahun',
                '5' => 'lebih dari 2 tahun',
            ],
        ],
        'available_start' => [
            'label' => 'Kapan Anda bisa mulai bekerja',
            'options' => [
                '0' => 'lebih dari 2 minggu',
                '1' => 'dalam 2 minggu',
                '2' => 'dalam 1 minggu',
                '3' => 'dalam 3 hari',
                '4' => 'besok',
                '5' => 'hari ini',
            ],
        ],
    ];
@endphp

@section('content')
<div class="mx-auto w-full max-w-3xl">
    <div class="rounded-2xl border border-gray-200 bg-white shadow-sm">
        <div class="border-b border-gray-100 px-6 py-5">
            <h2 class="text-xl font-semibold text-gray-900">📝 Formulir Preferensi Kerja</h2>
            <p class="mt-1 text-sm text-gray-500">Informasi ini membantu kami menghadirkan rekomendasi lowongan yang terasa relevan untuk Anda.</p>
        </div>

        <form method="POST" action="/user/preferensi/save" class="space-y-8 px-6 py-8">
            @csrf

            @if(session('status'))
                <div class="rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-700">
                    {{ session('status') }}
                </div>
            @endif

            <div>
                <label for="deskripsi" class="block text-sm font-semibold text-gray-700">Deskripsikan Dirimu</label>
                <p class="mt-1 text-xs text-gray-500">Ceritakan kebiasaan, pengalaman, atau hal yang Anda sukai saat bekerja.</p>
                <textarea id="deskripsi" name="deskripsi" rows="4" class="mt-3 w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-700 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200" placeholder="Contoh: Terbiasa bekerja lapangan, senang aktivitas sosial, berpengalaman sebagai barista"></textarea>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                @foreach ($questions as $name => $question)
                    <div class="space-y-2">
                        <label for="{{ $name }}" class="block text-sm font-semibold text-gray-700">{{ $question['label'] }}</label>
                        <select id="{{ $name }}" name="{{ $name }}" class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-700 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 @error($name) border-red-300 focus:border-red-400 focus:ring-red-200 @enderror">
                            <option value="-" selected>Pilih jawaban...</option>
                            @foreach ($question['options'] as $key => $desc)
                                <option value="{{ $key.' '.$desc }}" {{ old($name) === (string) $key ? 'selected' : '' }}>
                                    {{ $desc }}
                                </option>
                            @endforeach
                        </select>
                        @error($name)
                            <p class="text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                @endforeach
            </div>

            <div x-data="{ term: '' }" class="space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700">Kriteria Pekerjaan <span class="font-normal text-gray-500">(pilih minimal 3)</span></h3>
                        <p class="text-xs text-gray-500">Centang kriteria yang paling menggambarkan pekerjaan ideal Anda.</p>
                    </div>
                </div>
                <div class="rounded-2xl border border-gray-200 bg-gray-50 p-4 shadow-inner">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <input type="text" placeholder="Tambahkan kriteria lain, format: teks, teks" class="w-full rounded-lg border border-dashed border-gray-300 bg-white px-3 py-2 text-sm text-gray-600 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
                        <div class="sm:w-64">
                            <input type="search" x-model="term" placeholder="Cari kriteria..." class="mt-3 w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-600 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 sm:mt-0">
                        </div>
                    </div>
                    <ul class="mt-4 grid max-h-64 gap-3 overflow-y-auto rounded-xl bg-white p-4 text-sm text-gray-700 shadow-sm sm:grid-cols-2">
                        @foreach($kriteria as $item)
                            <li x-show="term === '' || '{{ strtolower($item->nama) }}'.includes(term.toLowerCase())" class="flex items-start gap-3">
                                <input type="checkbox" id="kriteria{{ $item->id }}" name="kriteria{{ $item->id }}" value="{{ $item->id.' '.$item->nama }}" {{ old('kriteria'.$item->id) ? 'checked' : '' }} class="mt-1 h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <label for="kriteria{{ $item->id }}" class="text-sm text-gray-700">{{ $item->nama }}</label>
                            </li>
                        @endforeach
                    </ul>
         
