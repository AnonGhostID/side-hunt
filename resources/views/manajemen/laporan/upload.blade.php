@extends('layouts.management')

@section('title', 'Upload Laporan Hasil Pekerjaan')
@section('page-title', 'Upload Laporan Hasil Pekerjaan')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-lg">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6">Formulir Upload Laporan</h2>
        <p class="text-gray-600 mb-6">
            Silakan lengkapi formulir di bawah ini untuk mengunggah laporan hasil pekerjaan Anda. Pastikan untuk menyertakan foto selfie dan dokumentasi pekerjaan sebagai bukti.
        </p>

        <form action="#" method="POST" enctype="multipart/form-data"> {{-- Ganti action dengan URL yang sesuai --}}
            @csrf
            <div class="space-y-6">
                {{-- Pilih Pekerjaan --}}
                <div>
                    <label for="pekerjaan_id" class="block text-sm font-medium text-gray-700 mb-1">Pilih Pekerjaan</label>
                    <select id="pekerjaan_id" name="pekerjaan_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                        <option value="">-- Pilih Pekerjaan yang Dilaporkan --</option>
                        <option value="1">Desain UI/UX Aplikasi Mobile (Andi Pratama)</option> {{-- Data dinamis --}}
                        <option value="2">Pengembangan Backend E-commerce (Siti Aminah)</option> {{-- Data dinamis --}}
                    </select>
                </div>

                {{-- Deskripsi Laporan --}}
                <div>
                    <label for="deskripsi_laporan" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Laporan</label>
                    <textarea id="deskripsi_laporan" name="deskripsi_laporan" rows="4" class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Jelaskan progres dan hasil pekerjaan Anda..." required></textarea>
                </div>

                {{-- Upload Foto Selfie --}}
                <div>
                    <label for="foto_selfie" class="block text-sm font-medium text-gray-700 mb-1">Upload Foto Selfie (Bukti Pengerjaan)</label>
                    <input type="file" id="foto_selfie" name="foto_selfie" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" required accept="image/*">
                    <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG. Maks: 2MB.</p>
                </div>

                {{-- Upload Dokumentasi Pekerjaan --}}
                <div>
                    <label for="dokumentasi_pekerjaan" class="block text-sm font-medium text-gray-700 mb-1">Upload Dokumentasi Pekerjaan (File/Screenshot)</label>
                    <input type="file" id="dokumentasi_pekerjaan" name="dokumentasi_pekerjaan[]" multiple class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" accept=".pdf,.doc,.docx,image/*">
                    <p class="mt-1 text-xs text-gray-500">Bisa lebih dari satu file. Format: PDF, DOC, DOCX, JPG, PNG. Maks per file: 5MB.</p>
                </div>

                {{-- Tombol Aksi --}}
                <div class="flex justify-end pt-4">
                    <button type="button" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded-lg mr-3 transition-colors duration-300">
                        Batal
                    </button>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition-colors duration-300">
                        <i class="fas fa-upload mr-2"></i>Upload Laporan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Script untuk halaman upload laporan
    console.log('Halaman Upload Laporan dimuat.');
    // Anda bisa menambahkan validasi sisi klien atau preview gambar di sini
</script>
@endpush
