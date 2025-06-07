@extends('layouts.management')

@section('title', 'Pembayaran Berhasil')
@section('page-title', 'Pembayaran Berhasil')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-xl mx-auto bg-white p-6 rounded-lg shadow-lg text-center">
        <!-- Success Icon -->
        <div class="mb-6">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100">
                <i class="fas fa-check text-green-600 text-2xl"></i>
            </div>
        </div>

        <!-- Success Message -->
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Pembayaran Berhasil!</h2>
        <p class="text-gray-600 mb-6">Top up saldo Anda telah berhasil diproses.</p>

        <!-- Payment Details -->
        <div class="bg-gray-50 p-4 rounded-lg mb-6 text-left">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <span class="text-sm text-gray-500">Jumlah Top Up:</span>
                    <p class="font-semibold">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Status:</span>
                    <p class="font-semibold text-green-600">{{ ucfirst($payment->status) }}</p>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-sm text-gray-500">Tanggal:</span>
                <p class="font-semibold">{{ $payment->updated_at->format('d/m/Y H:i:s') }}</p>
            </div>
        </div>

        <!-- Current Balance -->
        <div class="bg-blue-50 border border-blue-200 p-4 rounded-lg mb-6">
            <span class="text-sm text-blue-600">Saldo Dompet Saat Ini:</span>
            <p class="text-xl font-bold text-blue-800">Rp {{ number_format($payment->user->dompet, 0, ',', '.') }}</p>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('manajemen.topUp') }}" 
               class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition-colors duration-300">
                <i class="fas fa-plus mr-2"></i>
                Top Up Lagi
            </a>
            
            <a href="{{ route('manajemen.transaksi.riwayat') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition-colors duration-300">
                <i class="fas fa-history mr-2"></i>
                Lihat Riwayat
            </a>
            
            <a href="{{ route('manajemen.dashboard') }}" 
               class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition-colors duration-300">
                <i class="fas fa-home mr-2"></i>
                Kembali ke Dashboard
            </a>
        </div>
    </div>
</div>
@endsection
