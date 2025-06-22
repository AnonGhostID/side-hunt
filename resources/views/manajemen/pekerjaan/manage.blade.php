@extends('layouts.management')

@section('title', 'Kelola Pekerjaan')
@section('page-title', 'Kelola Pekerjaan')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-xl font-semibold text-gray-700 mb-4">{{ $pekerjaan->nama }}</h2>
        <p class="text-gray-600 mb-6">Status Pekerjaan: <span class="font-semibold">{{ $pekerjaan->status }}</span></p>

        @if($laporans->count() > 0)
            <div class="space-y-6">
                @foreach($laporans as $laporan)
                    <div class="border rounded-lg p-4">
                        <h4 class="font-semibold text-gray-700 mb-2">{{ $laporan->user->nama ?? 'Pekerja' }}</h4>
                        <p class="text-gray-600 mb-2">{{ $laporan->deskripsi }}</p>
                        <div class="mb-3">
                            <span class="font-medium">Foto Selfie:</span><br>
                            <img src="{{ asset('storage/'.$laporan->foto_selfie) }}" alt="Selfie" class="w-32 h-32 object-cover rounded mt-1">
                        </div>
                        <div class="mb-3">
                            <span class="font-medium">Dokumentasi:</span>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-2 mt-1">
                                @foreach(json_decode($laporan->foto_dokumentasi, true) as $foto)
                                    <img src="{{ asset('storage/'.$foto) }}" alt="Dokumentasi" class="w-32 h-32 object-cover rounded">
                                @endforeach
                            </div>
                        </div>
                        <div class="text-sm text-gray-500">Diupload pada {{ $laporan->created_at->format('d M Y H:i') }}</div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500">Belum ada laporan yang diunggah.</p>
        @endif
    </div>
</div>
@endsection
