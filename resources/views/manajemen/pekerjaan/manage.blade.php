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
                @if($pekerjaan->status == 'Open')
                    <form action="{{ route('manajemen.pekerjaan.updateStatus', $pekerjaan->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Set On Progress
                        </button>
                    </form>
                @endif
                @if($laporans->count() > 0 && $pekerjaan->status == 'Berlangsung')
                    <form id="terimaHasilForm" action="{{ route('manajemen.pekerjaan.terimaHasil', $pekerjaan->id) }}" method="POST">
                        @csrf
                        <button type="button" onclick="openConfirmationModal()" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Terima Hasil Pekerjaan
                        </button>
                    </form>
                @endif
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
    
    {{-- Confirmation Modal for Terima Hasil Pekerjaan --}}
    <div id="confirmationModal" class="fixed inset-0 z-50 hidden overflow-auto bg-black bg-opacity-75 flex items-center justify-center p-4">
        <div class="relative max-w-md w-full bg-white rounded-lg shadow-xl">
            {{-- Modal Header --}}
            <div class="flex justify-between items-center p-6 border-b">
                <h3 class="text-lg font-medium text-gray-900">Konfirmasi Terima Hasil Pekerjaan</h3>
                <button type="button" class="text-gray-400 hover:text-gray-500" onclick="closeConfirmationModal()">
                    <span class="sr-only">Tutup</span>
                    <i class="fa fa-times text-xl"></i>
                </button>
            </div>
            
            {{-- Modal Body --}}
            <div class="p-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="fa fa-exclamation-triangle text-yellow-400 text-2xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-gray-700">
                            Apakah anda yakin akan menerima hasil pekerjaan ini? Dana yang anda buat untuk membuat pekerjaan akan langsung dikirimkan ke Pekerja!
                        </p>
                    </div>
                </div>
            </div>
            
            {{-- Modal Footer --}}
            <div class="flex justify-end space-x-3 p-6 border-t bg-gray-50 rounded-b-lg">
                <button type="button" onclick="closeConfirmationModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Batal
                </button>
                <button type="button" onclick="confirmTerimaHasil()" class="px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    Ya, Terima Hasil
                </button>
            </div>
        </div>
    </div>

    <script>
        // Function to open the confirmation modal
        function openConfirmationModal() {
            document.getElementById('confirmationModal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }
        
        // Function to close the confirmation modal
        function closeConfirmationModal() {
            document.getElementById('confirmationModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
        
        // Function to confirm and submit the form
        function confirmTerimaHasil() {
            document.getElementById('terimaHasilForm').submit();
        }
        
        // Close modal when clicking outside the content
        document.getElementById('confirmationModal').addEventListener('click', function(event) {
            if (event.target === this) {
                closeConfirmationModal();
            }
        });
        
        // Add keyboard navigation for ESC key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && !document.getElementById('confirmationModal').classList.contains('hidden')) {
                closeConfirmationModal();
            }
        });
    </script>
</main>
@endsection
