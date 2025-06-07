@extends('layouts.management')

@section('title', 'Pembayaran Gagal')
@section('page-title', 'Pembayaran Gagal')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-xl mx-auto bg-white p-6 rounded-lg shadow-lg text-center">
        <!-- Error Icon -->
        <div class="mb-6">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100">
                <i class="fas fa-times text-red-600 text-2xl"></i>
            </div>
        </div>

        <!-- Error Message -->
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Pembayaran Gagal</h2>
        <p class="text-gray-600 mb-6">Maaf, pembayaran Anda tidak dapat diproses. Silakan coba lagi.</p>

        <!-- Payment Details -->
        <div class="bg-gray-50 p-4 rounded-lg mb-6 text-left">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <span class="text-sm text-gray-500">Jumlah Top Up:</span>
                    <p class="font-semibold">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Status:</span>
                    <p class="font-semibold text-red-600">{{ ucfirst($payment->status) }}</p>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-sm text-gray-500">ID Transaksi:</span>
                <p class="font-mono text-sm">{{ $payment->external_id }}</p>
            </div>
        </div>

        <!-- Possible Reasons -->
        <div class="bg-yellow-50 border border-yellow-200 p-4 rounded-lg mb-6 text-left">
            <h4 class="font-semibold text-yellow-800 mb-2">Kemungkinan Penyebab:</h4>
            <ul class="text-sm text-yellow-700 list-disc list-inside space-y-1">
                <li>Saldo tidak mencukupi</li>
                <li>Pembayaran dibatalkan</li>
                <li>Koneksi internet terputus</li>
                <li>Pembayaran kedaluwarsa (lebih dari 5 menit)</li>
            </ul>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('manajemen.topUp') }}" 
               class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition-colors duration-300">
                <i class="fas fa-redo mr-2"></i>
                Coba Lagi
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

        <!-- Help Section -->
        <div class="mt-8 pt-6 border-t border-gray-200">
            <p class="text-sm text-gray-500 mb-2">Butuh bantuan?</p>
            <a href="{{ route('manajemen.bantuan.panel') }}" class="text-blue-500 hover:text-blue-700 text-sm">
                <i class="fas fa-question-circle mr-1"></i>
                Hubungi Customer Service
            </a>
        </div>
    </div>
</div>
@endsection
