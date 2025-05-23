@extends('layouts.management')

@section('title', 'Manajemen Pekerjaan Berlangsung')
@section('page-title', 'Pekerjaan Sedang Berlangsung')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-xl font-semibold text-gray-700 mb-4">Daftar Pekerjaan yang Sedang Berlangsung</h2>
        <p class="text-gray-600 mb-6">
            Berikut adalah daftar pekerjaan yang saat ini sedang dalam progres. Anda dapat memantau status dan detail masing-masing pekerjaan.
        </p>

        {{-- Filter dan Tombol Aksi --}}
        <div class="mb-6 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="relative">
                <input type="text" placeholder="Cari pekerjaan..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
            </div>
            {{-- <button class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition-colors duration-300">
                <i class="fas fa-plus mr-2"></i> Tambah Pekerjaan Baru (Jika Perlu)
            </button> --}}
        </div>

        {{-- Tabel Data Pekerjaan --}}
        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr class="bg-gray-100 text-left text-gray-600 uppercase text-sm">
                        <th class="px-5 py-3 border-b-2 border-gray-200">Judul Pekerjaan</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200">Pemberi Kerja</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200">Pelamar Kerja</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200">Status</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200">Deadline</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    {{-- Contoh Data Row 1 --}}
                    <tr>
                        <td class="px-5 py-4 border-b border-gray-200 text-sm">
                            <p class="font-semibold">Desain UI/UX Aplikasi Mobile</p>
                            <p class="text-xs text-gray-500">Kategori: Desain Grafis</p>
                        </td>
                        <td class="px-5 py-4 border-b border-gray-200 text-sm">PT. Teknologi Maju</td>
                        <td class="px-5 py-4 border-b border-gray-200 text-sm">Andi Pratama</td>
                        <td class="px-5 py-4 border-b border-gray-200 text-sm">
                            <span class="relative inline-block px-3 py-1 font-semibold text-green-900 leading-tight">
                                <span aria-hidden class="absolute inset-0 bg-green-200 opacity-50 rounded-full"></span>
                                <span class="relative">Dalam Pengerjaan</span>
                            </span>
                        </td>
                        <td class="px-5 py-4 border-b border-gray-200 text-sm">30 Mei 2025</td>
                        <td class="px-5 py-4 border-b border-gray-200 text-sm">
                            <a href="#" class="text-blue-500 hover:text-blue-700 mr-2" title="Lihat Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="#" class="text-yellow-500 hover:text-yellow-700 mr-2" title="Edit Status (Admin)">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="#" class="text-red-500 hover:text-red-700" title="Batalkan (Admin)">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </td>
                    </tr>
                    {{-- Contoh Data Row 2 --}}
                    <tr>
                        <td class="px-5 py-4 border-b border-gray-200 text-sm">
                            <p class="font-semibold">Pengembangan Backend E-commerce</p>
                            <p class="text-xs text-gray-500">Kategori: Web Development</p>
                        </td>
                        <td class="px-5 py-4 border-b border-gray-200 text-sm">CV. Jaya Abadi</td>
                        <td class="px-5 py-4 border-b border-gray-200 text-sm">Siti Aminah</td>
                        <td class="px-5 py-4 border-b border-gray-200 text-sm">
                            <span class="relative inline-block px-3 py-1 font-semibold text-yellow-900 leading-tight">
                                <span aria-hidden class="absolute inset-0 bg-yellow-200 opacity-50 rounded-full"></span>
                                <span class="relative">Menunggu Review</span>
                            </span>
                        </td>
                        <td class="px-5 py-4 border-b border-gray-200 text-sm">15 Juni 2025</td>
                        <td class="px-5 py-4 border-b border-gray-200 text-sm">
                            <a href="#" class="text-blue-500 hover:text-blue-700 mr-2" title="Lihat Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    {{-- Tambahkan lebih banyak baris data di sini sesuai kebutuhan --}}
                    <tr>
                        <td colspan="6" class="px-5 py-10 border-b border-gray-200 text-sm text-center text-gray-500">
                            Tidak ada pekerjaan lain yang sedang berlangsung.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Pagination (jika diperlukan) --}}
        <div class="mt-6 flex justify-center">
            <nav aria-label="Page navigation">
                <ul class="inline-flex items-center -space-x-px">
                    <li>
                        <a href="#" class="px-3 py-2 ml-0 leading-tight text-gray-500 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-100 hover:text-gray-700">Previous</a>
                    </li>
                    <li>
                        <a href="#" aria-current="page" class="px-3 py-2 text-blue-600 border border-gray-300 bg-blue-50 hover:bg-blue-100 hover:text-blue-700">1</a>
                    </li>
                    <li>
                        <a href="#" class="px-3 py-2 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700">2</a>
                    </li>
                    <li>
                        <a href="#" class="px-3 py-2 leading-tight text-gray-500 bg-white border border-gray-300 rounded-r-lg hover:bg-gray-100 hover:text-gray-700">Next</a>
                    </li>
                </ul>
            </nav>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    // Script khusus untuk halaman ini jika ada
    console.log('Halaman Manajemen Pekerjaan Berlangsung dimuat.');
</script>
@endpush
