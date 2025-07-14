@extends('layouts.management')

@section('title')
    Form Laporan Penipuan
@endsection

@section('page-title')
    Form Laporan Penipuan
@endsection

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6">Laporan Penipuan</h2>

        {{-- Display success or error messages --}}
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Berhasil!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Gagal!</strong>
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Terdapat kesalahan:</strong>
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('manajemen.pelaporan.penipuan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label for="judul_laporan" class="block text-sm font-medium text-gray-700 mb-1">Judul Laporan</label>
                <input type="text" id="judul_laporan" name="judul_laporan" value="{{ old('judul_laporan') }}"
                       class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                       placeholder="Contoh: Penipuan oleh pengguna XXX" required>
            </div>

            <div class="mb-4">
                <label for="pihak_terlapor" class="block text-sm font-medium text-gray-700 mb-1">Pihak Terlapor (Username atau Nama)</label>
                <input type="text" id="pihak_terlapor" name="pihak_terlapor" value="{{ old('pihak_terlapor') }}"
                       class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                       placeholder="Masukkan username atau nama pihak yang dilaporkan" required>
            </div>

            <div class="mb-4">
                <label for="deskripsi_kejadian" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Kejadian</label>
                <textarea id="deskripsi_kejadian" name="deskripsi_kejadian" rows="5"
                          class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                          placeholder="Jelaskan kronologi kejadian secara detail" required>{{ old('deskripsi_kejadian') }}</textarea>
            </div>

            <div class="mb-4">
                <label for="tanggal_kejadian" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Perkiraan Kejadian</label>
                <input type="date" id="tanggal_kejadian" name="tanggal_kejadian" value="{{ old('tanggal_kejadian') }}"
                       class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
            </div>

            <div class="mb-6">
                <label for="bukti_pendukung" class="block text-sm font-medium text-gray-700 mb-1">Bukti Pendukung (Opsional)</label>
                <input type="file" id="bukti_pendukung" name="bukti_pendukung[]" multiple
                       class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                <p class="text-xs text-gray-500 mt-1">Anda dapat mengunggah beberapa file (gambar, PDF, dll.). Maksimum ukuran per file: 2MB.</p>
            </div>

            <div class="flex justify-end pt-4 border-t border-gray-200">
                <a href="{{ route('manajemen.dashboard') }}" class="mr-3 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-lg shadow-md transition-colors duration-300">
                    Batal
                </a>
                <button type="submit"
                        class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition-colors duration-300">
                    <i class="fas fa-paper-plane mr-2"></i> Kirim Laporan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Optional: Add any specific JavaScript for this form here
        // For example, file input validation or dynamic interactions
        const fileInput = document.getElementById('bukti_pendukung');
        if (fileInput) {
            fileInput.addEventListener('change', function() {
                const files = this.files;
                let totalSize = 0;
                const maxSizePerFile = 2 * 1024 * 1024; // 2MB

                for (let i = 0; i < files.length; i++) {
                    if (files[i].size > maxSizePerFile) {
                        alert('Ukuran file ' + files[i].name + ' melebihi batas maksimum 2MB.');
                        this.value = ''; // Clear the input
                        return;
                    }
                    totalSize += files[i].size;
                }

                // You can add a total size check if needed, e.g.,
                // const maxTotalSize = 10 * 1024 * 1024; // 10MB
                // if (totalSize > maxTotalSize) {
                //     alert('Ukuran total semua file melebihi batas maksimum.');
                //     this.value = ''; // Clear the input
                // }
            });
        }
    });
</script>
@endpush
