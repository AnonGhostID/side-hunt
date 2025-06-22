@extends('layouts.management')

@section('title', 'Kelola Pekerjaan')
@section('page-title', 'Kelola Pekerjaan')

@section('content')
<main class="container mx-auto px-4 py-8">
    <section class="bg-white p-6 rounded-lg shadow-lg">
        {{-- Header Section --}}
        <header class="border-b pb-4 mb-6">
            <h2 class="text-2xl font-semibold text-gray-800 mb-2">{{ $pekerjaan->nama }}</h2>
            
            <div class="flex flex-wrap items-center gap-4">
                <p class="text-gray-600">
                    Status: 
                    <span class="font-medium px-3 py-1 rounded-full text-sm
                        @if($pekerjaan->status == 'Selesai') 
                            bg-green-100 text-green-800
                        @elseif($pekerjaan->status == 'Berlangsung') 
                            bg-blue-100 text-blue-800
                        @elseif($pekerjaan->status == 'Ditolak') 
                            bg-red-100 text-red-800
                        @else 
                            bg-gray-100 text-gray-800
                        @endif">
                        {{ $pekerjaan->status }}
                    </span>
                </p>
            </div>
        </header>

        {{-- Laporan Section --}}
        <div>
            <h3 class="text-lg font-medium text-gray-700 mb-4">Laporan Pekerjaan</h3>
            
            @if($laporans->count() > 0)
                <div class="space-y-6">
                    @foreach($laporans as $laporan)
                        @include('manajemen.pekerjaan.partials.laporan-item', ['laporan' => $laporan])
                    @endforeach
                </div>
            @else
                <div class="bg-gray-50 rounded-lg p-8 text-center">
                    <p class="text-gray-500">Belum ada laporan yang diunggah.</p>
                    <p class="text-sm text-gray-400 mt-2">Laporan akan muncul setelah pekerja mengunggahnya.</p>
                </div>
            @endif
        </div>
    </section>
    
    {{-- Include the image modal --}}
    @include('manajemen.pekerjaan.partials.image-modal')
</main>
@endsection
