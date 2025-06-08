@extends('layouts.management')

@section('title', 'Top Up Saldo')
@section('page-title', 'Top Up Saldo')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-xl mx-auto bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Isi Saldo Dompet</h2>
        
        <!-- @if (session('error'))
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-md">
                {{ session('error') }}
            </div>
        @endif
        
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-md">
                {{ session('success') }}
            </div>
        @endif -->
        
        <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded">
            <span class="text-gray-700">Saldo Dompet Saat Ini:</span>
            <span class="font-semibold">Rp {{ number_format($user->dompet, 0, ',', '.') }}</span>
        </div>
        <form action="{{ route('manajemen.topup.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="nominal" class="block text-sm font-medium text-gray-700 mb-1">Pilih Nominal Top Up</label>
                <select id="nominal" name="nominal" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="">-- Pilih Nominal --</option>
                    @for ($i = 50000; $i <= 300000; $i += 50000)
                        <option value="{{ $i }}">Rp {{ number_format($i, 0, ',', '.') }}</option>
                    @endfor
                    <option value="500000">Rp {{ number_format(500000, 0, ',', '.') }}</option>
                    <option value="1000000">Rp {{ number_format(1000000, 0, ',', '.') }}</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="custom_amount" class="block text-sm font-medium text-gray-700 mb-1">Atau Masukkan Jumlah Custom</label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">Rp</span>
                    <input 
                        type="text" 
                        id="custom_amount" 
                        name="custom_amount" 
                        data-min="20000" 
                        data-step="5000"
                        class="mt-1 block w-full pl-9 py-2 px-3 border border-gray-300 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                        placeholder="Minimal Rp 20.000"
                    >
                    <input type="hidden" id="custom_amount_raw" name="custom_amount_raw">
                </div>
                <p class="text-xs text-gray-500 mt-1">Minimum top up adalah Rp 20.000</p>
            </div>
            <div class="mb-4">
                <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg text-center">
                    <img src="{{ asset('xenditblue.png') }}" alt="Xendit" class="h-12 w-auto mx-auto mb-2">
                    <div>
                        <span class="text-sm font-medium text-gray-700">Payment Gateway</span>
                        <p class="text-xs text-gray-500">Anda akan dialihkan menuju Payment Gateway</p>
                    </div>
                </div>
            </div>
            <div class="flex justify-end pt-4">
                <button type="submit" id="topup-button" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition-colors duration-300 disabled:bg-gray-400 disabled:cursor-not-allowed disabled:hover:bg-gray-400">
                    <i class="fas fa-wallet mr-2"></i> Top Up Sekarang
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const nominalSelect = document.getElementById('nominal');
        const customAmountInput = document.getElementById('custom_amount');
        const customAmountRaw = document.getElementById('custom_amount_raw');
        const topupButton = document.getElementById('topup-button');
        const form = document.querySelector('form');
        
        // Function to format number with thousands separator
        function formatNumber(num) {
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }
        
        // Function to remove formatting and get raw number
        function getRawNumber(formattedNum) {
            return formattedNum.replace(/\./g, '');
        }
        
        // Function to check button state
        function checkButtonState() {
            const selectedNominal = nominalSelect.value;
            const customAmount = parseInt(customAmountRaw.value) || 0;
            
            if (selectedNominal || (customAmount >= 20000)) {
                topupButton.disabled = false;
            } else {
                topupButton.disabled = true;
            }
        }
        
        // Clear custom amount when selecting from dropdown
        nominalSelect.addEventListener('change', function() {
            if (this.value) {
                customAmountInput.value = '';
                customAmountRaw.value = '';
            }
            checkButtonState();
        });
        
        // Clear dropdown when typing custom amount
        customAmountInput.addEventListener('input', function() {
            if (this.value) {
                nominalSelect.value = '';
            }
            
            // Remove all non-numeric characters
            let rawValue = this.value.replace(/[^0-9]/g, '');
            
            // Format with thousands separator
            if (rawValue) {
                this.value = formatNumber(rawValue);
                customAmountRaw.value = rawValue;
            } else {
                this.value = '';
                customAmountRaw.value = '';
            }
            
            checkButtonState();
        });
        
        // Handle keypress to only allow numbers
        customAmountInput.addEventListener('keypress', function(e) {
            // Allow backspace, delete, tab, escape, enter
            if ([8, 9, 27, 13, 46].indexOf(e.keyCode) !== -1 ||
                // Allow Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
                (e.keyCode === 65 && e.ctrlKey === true) ||
                (e.keyCode === 67 && e.ctrlKey === true) ||
                (e.keyCode === 86 && e.ctrlKey === true) ||
                (e.keyCode === 88 && e.ctrlKey === true)) {
                return;
            }
            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        });
        
        // Form validation
        form.addEventListener('submit', function(e) {
            const selectedNominal = nominalSelect.value;
            const customAmount = customAmountRaw.value;
            
            if (!selectedNominal && !customAmount) {
                e.preventDefault();
                alert('Silakan pilih nominal atau masukkan jumlah custom untuk top up.');
                return;
            }
            
            if (customAmount && parseInt(customAmount) < 20000) {
                e.preventDefault();
                alert('Jumlah minimum top up adalah Rp 20.000');
                return;
            }
            
            // Set the raw value for form submission
            if (customAmount) {
                customAmountInput.name = '';
                customAmountRaw.name = 'custom_amount';
            }
        });
        
        // Initial button state check
        checkButtonState();
    });
</script>
@endpush
