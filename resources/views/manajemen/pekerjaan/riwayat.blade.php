@extends('layouts.management')

@section('title', 'Riwayat Pekerjaan')
@section('page-title', 'Riwayat Pekerjaan Pengguna')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-xl font-semibold text-gray-700 mb-4">Daftar Riwayat Pekerjaan</h2>
        <p class="text-gray-600 mb-6">
            Berikut adalah daftar semua pekerjaan yang telah selesai atau memiliki riwayat sebelumnya di platform ini.
            Anda dapat melihat detail, status akhir, dan informasi terkait lainnya.
        </p>

        {{-- Filter dan Tombol Aksi --}}
        <div class="mb-6 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="relative w-full sm:w-1/2 md:w-1/3">
                <input type="text" placeholder="Cari berdasarkan judul, pemberi/pelamar kerja..." class="pl-10 pr-4 py-2 border border-gray-300 bg-white text-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent w-full">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
            </div>
            <div class="flex space-x-2">
                <select class="py-2 px-3 border border-gray-300 bg-white text-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Semua Status</option>
                    <option value="selesai">Selesai</option>
                    <option value="dibatalkan">Dibatalkan</option>
                    <option value="kadaluarsa">Kadaluarsa</option>
                </select>
                {{-- <button class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition-colors duration-300">
                    <i class="fas fa-filter mr-2"></i> Terapkan Filter
                </button> --}}
            </div>
        </div>

        {{-- Tabel Data Riwayat Pekerjaan --}}
        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr class="bg-gray-100 text-left text-gray-600 uppercase text-sm">
                        <th class="px-5 py-3 border-b-2 border-gray-200">Judul Pekerjaan</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200">Pemberi Kerja</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200">Pelamar Kerja</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200">Status Akhir</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200">Tanggal Selesai/Update</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200">Rating</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    {{-- Contoh Data Row 1 --}}
                    <tr>
                        <td class="px-5 py-4 border-b border-gray-200 text-sm">
                            <p class="font-semibold">Penulisan Artikel SEO (10 Artikel)</p>
                            <p class="text-xs text-gray-500">Kategori: Penulisan Konten</p>
                        </td>
                        <td class="px-5 py-4 border-b border-gray-200 text-sm">PT. Digital Kreatif</td>
                        <td class="px-5 py-4 border-b border-gray-200 text-sm">Budi Santoso</td>
                        <td class="px-5 py-4 border-b border-gray-200 text-sm">
                            <span class="relative inline-block px-3 py-1 font-semibold text-green-900 leading-tight">
                                <span aria-hidden class="absolute inset-0 bg-green-200 opacity-50 rounded-full"></span>
                                <span class="relative">Selesai</span>
                            </span>
                        </td>
                        <td class="px-5 py-4 border-b border-gray-200 text-sm">15 April 2024</td>
                        <td class="px-5 py-4 border-b border-gray-200 text-sm">
                            <i class="fas fa-star text-yellow-400"></i>
                            <i class="fas fa-star text-yellow-400"></i>
                            <i class="fas fa-star text-yellow-400"></i>
                            <i class="fas fa-star text-yellow-400"></i>
                            <i class="far fa-star text-yellow-400"></i>
                            (4.0)
                        </td>
                        <td class="px-5 py-4 border-b border-gray-200 text-sm">
                            <a href="#" class="text-blue-500 hover:text-blue-700" title="Lihat Detail Riwayat">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    {{-- Contoh Data Row 2 --}}
                    <tr>
                        <td class="px-5 py-4 border-b border-gray-200 text-sm">
                            <p class="font-semibold">Input Data Produk ke Excel</p>
                            <p class="text-xs text-gray-500">Kategori: Entri Data</p>
                        </td>
                        <td class="px-5 py-4 border-b border-gray-200 text-sm">Toko Online CepatLaku</td>
                        <td class="px-5 py-4 border-b border-gray-200 text-sm">Rina Wati</td>
                        <td class="px-5 py-4 border-b border-gray-200 text-sm">
                            <span class="relative inline-block px-3 py-1 font-semibold text-red-900 leading-tight">
                                <span aria-hidden class="absolute inset-0 bg-red-200 opacity-50 rounded-full"></span>
                                <span class="relative">Dibatalkan</span>
                            </span>
                        </td>
                        <td class="px-5 py-4 border-b border-gray-200 text-sm">10 Maret 2024</td>
                        <td class="px-5 py-4 border-b border-gray-200 text-sm">N/A</td>
                        <td class="px-5 py-4 border-b border-gray-200 text-sm">
                            <a href="#" class="text-blue-500 hover:text-blue-700" title="Lihat Detail Riwayat">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    {{-- Tambahkan lebih banyak baris data di sini sesuai kebutuhan --}}
                    {{-- Contoh jika tidak ada data --}}
                    {{-- <tr>
                        <td colspan="7" class="px-5 py-10 border-b border-gray-200 text-sm text-center text-gray-500">
                            Tidak ada riwayat pekerjaan yang ditemukan.
                        </td>
                    </tr> --}}
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
    // Script khusus untuk halaman riwayat pekerjaan jika ada
    console.log('Halaman Riwayat Pekerjaan dimuat.');
</script>
@endpush
