@extends('layouts.management')

@section('title', 'Detail Pembayaran Fee Gaji')
@section('page-title', 'Detail Pembayaran Fee Gaji')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white p-6 rounded-lg shadow-lg">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 gap-4">
            <h2 class="text-xl font-semibold text-gray-700">Detail Pembayaran Fee Gaji</h2>
            <a href="{{ route('manajemen.transaksi.riwayat') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded w-full sm:w-auto text-center">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Transaction Information -->
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="font-semibold text-gray-700 mb-4">Informasi Transaksi</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">ID Transaksi:</span>
                        <span class="font-medium">{{ $feeGaji->external_id }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tanggal Transfer:</span>
                        <span class="font-medium">{{ $feeGaji->processed_at->format('d M Y H:i') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Gaji Diterima Pekerja:</span>
                        <span class="font-bold text-green-600">+Rp {{ number_format($feeGaji->amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Biaya Admin:</span>
                        <span class="font-medium text-red-600">Rp 2.500</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Gaji Kotor:</span>
                        <span class="font-bold text-blue-600">Rp {{ number_format($feeGaji->amount + 2500, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Status:</span>
                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                            {{ $feeGaji->status_label }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Payment Information -->
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="font-semibold text-gray-700 mb-4">Informasi Pembayaran</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Deskripsi:</span>
                        <span class="font-medium">{{ $feeGaji->description }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Metode Transfer:</span>
                        <span class="font-medium">{{ strtoupper($feeGaji->method ?? 'ADMIN_TRANSFER') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tipe Transaksi:</span>
                        <span class="font-medium">Pembayaran Gaji Pekerjaan</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Dibuat Pada:</span>
                        <span class="font-medium">{{ $feeGaji->created_at->format('d M Y H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Fee Breakdown -->
        <div class="mt-6 bg-blue-50 p-4 rounded-lg">
            <h3 class="font-semibold text-blue-800 mb-4">Rincian Biaya</h3>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-blue-700">Gaji Kotor Pekerjaan:</span>
                    <span class="font-medium text-blue-900">Rp {{ number_format($feeGaji->amount + 2500, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-blue-700">Biaya Admin Platform:</span>
                    <span class="font-medium text-red-600">- Rp 2.500</span>
                </div>
                <hr class="border-blue-200 my-2">
                <div class="flex justify-between font-bold">
                    <span class="text-blue-800">Total Diterima Pekerja:</span>
                    <span class="text-green-600">Rp {{ number_format($feeGaji->amount, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Status Information -->
        <div class="mt-6 bg-green-50 p-4 rounded-lg">
            <h3 class="font-semibold text-green-800 mb-2">Status Pembayaran</h3>
            <p class="text-sm text-green-700">
                <i class="fas fa-check-circle mr-2"></i>
                Pembayaran gaji berhasil diproses. Dana telah ditransfer ke saldo pekerja setelah dipotong biaya admin sebesar Rp 2.500.
            </p>
        </div>

        <!-- Additional Information -->
        <div class="mt-6 bg-yellow-50 p-4 rounded-lg">
            <h3 class="font-semibold text-yellow-800 mb-2">Informasi Tambahan</h3>
            <div class="text-sm text-yellow-700 space-y-1">
                <p><i class="fas fa-info-circle mr-2"></i>Biaya admin sebesar Rp 2.500 dikenakan untuk setiap pembayaran gaji pekerjaan.</p>
                <p><i class="fas fa-shield-alt mr-2"></i>Biaya ini digunakan untuk operasional platform dan keamanan transaksi.</p>
                <p><i class="fas fa-receipt mr-2"></i>Transaksi ini tercatat secara otomatis di sistem dan dapat dilihat di riwayat transaksi.</p>
            </div>
        </div>
    </div>
</div>
@endsection