@extends('layouts.management')

@section('title', 'Riwayat Transaksi')
@section('page-title', 'Riwayat Transaksi Keuangan')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-xl font-semibold text-gray-700 mb-4">Daftar Riwayat Transaksi</h2>
        <p class="text-gray-600 mb-6">
            Berikut adalah daftar transaksi yang pernah dilakukan. Anda dapat memeriksa detail status pembayaran di sini.
        </p>
        <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded">
            <span class="text-gray-700">Saldo Anda Saat Ini:</span>
            <span class="font-semibold">Rp {{ number_format($user->dompet, 0, ',', '.') }}</span>
        </div>

        <div class="mb-6 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="relative">
                <input type="text" placeholder="Cari transaksi..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr class="bg-gray-100 text-left text-gray-600 uppercase text-sm">
                        <th class="px-5 py-3 border-b-2 border-gray-200">Tanggal</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200">ID Transaksi</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200">Deskripsi</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200">Jumlah</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200">Metode</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200">Status</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @forelse($transaksi as $t)
                    <tr>
                        <td class="px-5 py-4 border-b border-gray-200 text-sm">{{ $t->kode }}</td>
                        <td class="px-5 py-4 border-b border-gray-200 text-sm">{{ $t->dibuat }}</td>
                        <td class="px-5 py-4 border-b border-gray-200 text-sm">{{ $t->deskripsi ?? '-' }}</td>
                        <td class="px-5 py-4 border-b border-gray-200 text-sm">Rp {{ number_format($t->jumlah, 0, ',', '.') }}</td>
                        <td class="px-5 py-4 border-b border-gray-200 text-sm">
                            @php
                                $color = match($t->status) {
                                    'sukses' => 'green',
                                    'tertunda', 'tunda' => 'yellow',
                                    default => 'red'
                                };
                            @endphp
                            <span class="relative inline-block px-3 py-1 font-semibold text-{{ $color }}-900 leading-tight">
                                <span aria-hidden class="absolute inset-0 bg-{{ $color }}-200 opacity-50 rounded-full"></span>
                                <span class="relative">{{ ucfirst($t->status) }}</span>
                            </span>
                        </td>
                        <td class="px-5 py-4 border-b border-gray-200 text-sm">
                            <a href="#" class="text-blue-500 hover:text-blue-700 mr-2" title="Lihat Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-5 py-10 border-b border-gray-200 text-sm text-center text-gray-500">
                            Tidak ada transaksi lainnya.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6 flex justify-center">
            {{ $transaksi->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    console.log('Halaman Riwayat Transaksi dimuat.');
</script>
@endpush
