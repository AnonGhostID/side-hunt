@extends('layouts.management')

@section('title', 'Menunggu Pembayaran')
@section('page-title', 'Menunggu Pembayaran')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-lg">
        <!-- Header -->
        <div class="text-center mb-6">
            <div class="animate-spin rounded-full h-16 w-16 border-b-2 border-blue-500 mx-auto mb-4"></div>
            <h2 class="text-2xl font-semibold text-gray-800 mb-2">Invoice telah dibuat!</h2>
            <p class="text-gray-600">Silakan klik "Bayar Sekarang" untuk melakukan pembayaran</p>
        </div>

        <!-- Payment Details -->
        <div class="bg-gray-50 p-4 rounded-lg mb-6">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <span class="text-sm text-gray-500">Jumlah Top Up:</span>
                    <p class="font-semibold text-lg">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Status:</span>
                    <p id="payment-status" class="font-semibold text-lg text-yellow-600">Menunggu Pembayaran</p>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-sm text-gray-500">ID Transaksi:</span>
                <p class="font-mono text-sm">{{ $payment->external_id }}</p>
            </div>
        </div>        <!-- Countdown Timer -->
        <div class="bg-yellow-50 border border-yellow-200 p-4 rounded-lg mb-6">
            <div class="flex items-center">
                <i class="fas fa-clock text-yellow-600 mr-2"></i>
                <span class="text-sm">Pembayaran akan kedaluwarsa dalam: </span>
                <span id="countdown" class="font-semibold text-yellow-800 ml-2">24:00:00</span>
            </div>
        </div>

        <!-- Payment Link -->
        <div class="text-center mb-6">
            <a href="{{ $payment->checkout_link }}" target="_blank" 
               class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 px-6 rounded-lg shadow-md transition-colors duration-300 inline-block">
                <i class="fas fa-external-link-alt mr-2"></i>
                Bayar Sekarang
            </a>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <button onclick="checkPaymentStatus()" 
                    class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition-colors duration-300">
                <i class="fas fa-refresh mr-2"></i>
                Cek Status Sekarang
            </button>
              <a href="{{ route('manajemen.topup.cancel', $payment->external_id) }}" 
               onclick="return confirm('Apakah Anda yakin ingin membatalkan pembayaran ini?')"
               class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition-colors duration-300 text-center">
                <i class="fas fa-times mr-2"></i>
                Batalkan
            </a>
        </div>

        <!-- Success/Error Messages -->
        <div id="message-container" class="mt-6 hidden">
            <div id="success-message" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded hidden">
                <i class="fas fa-check-circle mr-2"></i>
                <span id="success-text"></span>
            </div>
            <div id="error-message" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded hidden">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <span id="error-text"></span>
            </div>
        </div>
    </div>
</div>

<script>
let countdownInterval;
let autoCheckInterval;
let paymentCompleted = false;

document.addEventListener('DOMContentLoaded', function() {
    startCountdown();
    startAutoCheck();
});

function startCountdown() {
    // Convert Laravel timestamp to proper timezone-aware JavaScript date
    const createdAtUTC = new Date('{{ $payment->created_at->toISOString() }}').getTime();
    const expiryTime = createdAtUTC + (24 * 60 * 60 * 1000); // 24 hours
    
    countdownInterval = setInterval(function() {
        const now = new Date().getTime();
        const distance = expiryTime - now;
        
        if (distance < 0) {
            clearInterval(countdownInterval);
            document.getElementById('countdown').innerHTML = "EXPIRED";
            document.getElementById('payment-status').innerHTML = "Kedaluwarsa";
            document.getElementById('payment-status').className = "font-semibold text-lg text-red-600";
            showMessage('error', 'Pembayaran telah kedaluwarsa. Silakan buat transaksi baru.');
            paymentCompleted = true;
            clearInterval(autoCheckInterval);
            return;
        }
        
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);
        
        document.getElementById('countdown').innerHTML = 
            (hours < 10 ? "0" : "") + hours + ":" + 
            (minutes < 10 ? "0" : "") + minutes + ":" + 
            (seconds < 10 ? "0" : "") + seconds;
    }, 1000);
}

function startAutoCheck() {
    // Check every 3 seconds
    autoCheckInterval = setInterval(function() {
        if (!paymentCompleted) {
            checkPaymentStatus(false);
        }
    }, 3000);
}

function checkPaymentStatus(manual = true) {
    if (paymentCompleted) return;
    
    fetch('{{ route("manajemen.topup.check-status") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            external_id: '{{ $payment->external_id }}'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            paymentCompleted = true;
            clearInterval(countdownInterval);
            clearInterval(autoCheckInterval);
            
            document.getElementById('payment-status').innerHTML = "Berhasil";
            document.getElementById('payment-status').className = "font-semibold text-lg text-green-600";
            
            showMessage('success', data.message + ' Saldo baru: Rp ' + new Intl.NumberFormat('id-ID').format(data.new_balance));
              // Redirect to success page after 3 seconds
            setTimeout(() => {
                window.location.href = '{{ route("manajemen.topup.success", $payment->external_id) }}';
            }, 3000);
        } else if (data.status === 'paid' || data.status === 'settled') {
            paymentCompleted = true;
            clearInterval(countdownInterval);
            clearInterval(autoCheckInterval);
            
            document.getElementById('payment-status').innerHTML = "Berhasil";
            document.getElementById('payment-status').className = "font-semibold text-lg text-green-600";
            
            showMessage('success', 'Pembayaran berhasil! Saldo Anda telah ditambahkan.');
              setTimeout(() => {
                window.location.href = '{{ route("manajemen.topup.success", $payment->external_id) }}';
            }, 3000);
        } else if (data.status === 'expired' || data.status === 'cancelled') {
            paymentCompleted = true;
            clearInterval(countdownInterval);
            clearInterval(autoCheckInterval);
            
            document.getElementById('payment-status').innerHTML = data.status === 'expired' ? "Kedaluwarsa" : "Dibatalkan";
            document.getElementById('payment-status').className = "font-semibold text-lg text-red-600";
            
            showMessage('error', data.message);
        } else if (manual) {
            showMessage('info', data.message);
        }
    })
    .catch(error => {
        if (manual) {
            showMessage('error', 'Terjadi kesalahan saat mengecek status pembayaran');
        }
        console.error('Error:', error);
    });
}

function showMessage(type, text) {
    const container = document.getElementById('message-container');
    const successMsg = document.getElementById('success-message');
    const errorMsg = document.getElementById('error-message');
    
    // Hide all messages first
    successMsg.classList.add('hidden');
    errorMsg.classList.add('hidden');
    
    if (type === 'success') {
        document.getElementById('success-text').textContent = text;
        successMsg.classList.remove('hidden');
    } else if (type === 'error') {
        document.getElementById('error-text').textContent = text;
        errorMsg.classList.remove('hidden');
    } else {
        // For info messages, use success styling
        document.getElementById('success-text').textContent = text;
        successMsg.classList.remove('hidden');
    }
    
    container.classList.remove('hidden');
    
    // Auto hide after 5 seconds for non-critical messages
    if (type === 'info') {
        setTimeout(() => {
            container.classList.add('hidden');
        }, 5000);
    }
}

// Cleanup intervals when page is unloaded
window.addEventListener('beforeunload', function() {
    if (countdownInterval) clearInterval(countdownInterval);
    if (autoCheckInterval) clearInterval(autoCheckInterval);
});
</script>
@endsection
