@extends('layouts.management')

@section('title', 'Laporan Keuangan')
@section('page-title', 'Laporan Keuangan')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-xl font-semibold text-gray-700 mb-4">Ringkasan Keuangan Bulan Ini</h2>
        <p class="text-gray-600 mb-6">
            Berikut adalah ringkasan pemasukan dan pengeluaran selama periode ini. Data berikut hanyalah contoh statis.
        </p>

        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr class="bg-gray-100 text-left text-gray-600 uppercase text-sm">
                        <th class="px-5 py-3 border-b-2 border-gray-200">Tanggal</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200">Keterangan</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200">Pemasukan</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200">Pengeluaran</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    <tr>
                        <td class="px-5 py-4 border-b border-gray-200 text-sm">1 Mei 2025</td>
                        <td class="px-5 py-4 border-b border-gray-200 text-sm">Pendapatan Proyek A</td>
                        <td class="px-5 py-4 border-b border-gray-200 text-sm">Rp 2.000.000</td>
                        <td class="px-5 py-4 border-b border-gray-200 text-sm">-</td>
                    </tr>
                    <tr>
                        <td class="px-5 py-4 border-b border-gray-200 text-sm">5 Mei 2025</td>
                        <td class="px-5 py-4 border-b border-gray-200 text-sm">Biaya Operasional</td>
                        <td class="px-5 py-4 border-b border-gray-200 text-sm">-</td>
                        <td class="px-5 py-4 border-b border-gray-200 text-sm">Rp 500.000</td>
                    </tr>
                    <tr>
                        <td class="px-5 py-4 border-b border-gray-200 text-sm">20 Mei 2025</td>
                        <td class="px-5 py-4 border-b border-gray-200 text-sm">Pendapatan Proyek B</td>
                        <td class="px-5 py-4 border-b border-gray-200 text-sm">Rp 1.500.000</td>
                        <td class="px-5 py-4 border-b border-gray-200 text-sm">-</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mt-6 text-right">
            <span class="text-gray-700 font-semibold">Total Pemasukan: Rp 3.500.000</span><br>
            <span class="text-gray-700 font-semibold">Total Pengeluaran: Rp 500.000</span>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    console.log('Halaman Laporan Keuangan dimuat.');
</script>
@endpush
