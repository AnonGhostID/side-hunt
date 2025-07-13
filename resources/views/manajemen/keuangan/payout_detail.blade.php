@extends('layouts.management')

@section('title', 'Detail Penarikan')
@section('page-title', 'Detail Transaksi Penarikan')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white p-6 rounded-lg shadow-lg">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 gap-4">
            <h2 class="text-xl font-semibold text-gray-700">Detail Penarikan Dana</h2>
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
                        <span class="font-medium">{{ $payout->xendit_reference_id ?? $payout->id }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tanggal Permintaan:</span>
                        <span class="font-medium">{{ $payout->created_at->format('d M Y H:i') }}</span>
                    </div>
                    @if($payout->processed_at)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tanggal Diproses:</span>
                        <span class="font-medium">{{ $payout->processed_at->format('d M Y H:i') }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between">
                        <span class="text-gray-600">Jumlah:</span>
                        <span class="font-bold text-red-600">-Rp {{ number_format($payout->amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Status:</span>
                        <span class="px-2 py-1 text-xs rounded-full 
                            @if($payout->status == 'completed') bg-green-100 text-green-800
                            @elseif($payout->status == 'processing') bg-blue-100 text-blue-800
                            @elseif($payout->status == 'failed') bg-red-100 text-red-800
                            @elseif($payout->status == 'pending') bg-yellow-100 text-yellow-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ $payout->status_label }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Account Information -->
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="font-semibold text-gray-700 mb-4">Informasi Rekening</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Jenis:</span>
                        <span class="font-medium">{{ strtoupper($payout->payment_type ?? 'BANK') }}</span>
                    </div>
                    @if($payout->bank_code)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Kode Bank:</span>
                        <span class="font-medium">{{ strtoupper($payout->bank_code) }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between">
                        <span class="text-gray-600">Nomor Rekening:</span>
                        <span class="font-medium">{{ $payout->account_number }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Nama Pemilik:</span>
                        <span class="font-medium">{{ $payout->account_name }}</span>
                    </div>
                </div>
            </div>
        </div>

        @if($payout->xendit_disbursement_id)
        <div class="mt-6 bg-blue-50 p-4 rounded-lg">
            <h3 class="font-semibold text-blue-800 mb-2">Informasi Pembayaran</h3>
            <div class="text-sm text-blue-700">
                <p><strong>Xendit ID:</strong> {{ $payout->xendit_disbursement_id }}</p>
            </div>
        </div>
        @endif

        @if($payout->failure_reason)
        <div class="mt-6 bg-red-50 p-4 rounded-lg">
            <h3 class="font-semibold text-red-800 mb-2">Alasan Gagal</h3>
            <p class="text-sm text-red-700">{{ $payout->failure_reason }}</p>
        </div>
        @endif

        @if($payout->status == 'completed')
        <div class="mt-6 bg-green-50 p-4 rounded-lg">
            <h3 class="font-semibold text-green-800 mb-2">Status Penarikan</h3>
            <p class="text-sm text-green-700">
                <i class="fas fa-check-circle mr-2"></i>
                Penarikan berhasil diproses. Dana telah dikirim ke rekening Anda.
            </p>
        </div>
        @elseif($payout->status == 'processing')
        <div class="mt-6 bg-blue-50 p-4 rounded-lg">
            <h3 class="font-semibold text-blue-800 mb-2">Status Penarikan</h3>
            <p class="text-sm text-blue-700">
                <i class="fas fa-clock mr-2"></i>
                Penarikan sedang diproses. Dana akan dikirim dalam 1-2 hari kerja.
            </p>
        </div>
        @elseif($payout->status == 'pending')
        <div class="mt-6 bg-yellow-50 p-4 rounded-lg">
            <h3 class="font-semibold text-yellow-800 mb-2">Status Penarikan</h3>
            <p class="text-sm text-yellow-700">
                <i class="fas fa-hourglass-half mr-2"></i>
                Penarikan sedang menunggu untuk diproses.
            </p>
        </div>
        @endif
    </div>
</div>
@endsection