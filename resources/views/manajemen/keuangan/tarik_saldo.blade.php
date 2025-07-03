@extends('layouts.management')

@section('title', 'Tarik Saldo')
@section('page-title', 'Tarik Saldo')

@section('content')
<div class="max-w-6xl mx-auto p-6 space-y-6">

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Balance Card -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-medium opacity-90">Saldo Tersedia</h3>
                <p class="text-3xl font-bold" id="current-balance">Rp {{ number_format($userModel->dompet, 0, ',', '.') }}</p>
                <p class="text-sm opacity-75 mt-1">Maksimal penarikan: Rp {{ number_format($userModel->dompet, 0, ',', '.') }}</p>
            </div>
            <div class="text-6xl opacity-20">
                <i class="fas fa-wallet"></i>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Withdrawal Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-money-bill-transfer text-blue-600 mr-3"></i>
                    Form Penarikan Saldo
                </h2>

                <form action="{{ route('manajemen.payout.store') }}" method="POST" id="withdrawal-form">
                    @csrf
                    
                    <!-- Amount Input -->
                    <div class="mb-6">
                        <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">
                            Jumlah Penarikan
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-3 text-gray-500">Rp</span>
                            <input type="number" 
                                   id="amount" 
                                   name="amount" 
                                   class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('amount') border-red-500 @enderror" 
                                   placeholder="50.000"
                                   min="50000"
                                   max="{{ $userModel->dompet }}"
                                   value="{{ old('amount') }}"
                                   required>
                        </div>
                        <div class="flex justify-between text-xs text-gray-500 mt-1">
                            <span>Minimum: Rp 50.000</span>
                            <span>Maksimum: Rp {{ number_format($userModel->dompet, 0, ',', '.') }}</span>
                        </div>
                        @error('amount')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <div id="amount-error" class="text-red-500 text-xs mt-1 hidden"></div>
                    </div>

                    <!-- Quick Amount Buttons -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Cepat</label>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                            @php
                                $quickAmounts = [50000, 100000, 500000, 1000000];
                                $maxAmount = $userModel->dompet;
                            @endphp
                            @foreach($quickAmounts as $quickAmount)
                                @if($quickAmount <= $maxAmount)
                                    <button type="button" 
                                            class="quick-amount-btn px-4 py-2 border border-gray-300 rounded-lg text-sm hover:bg-blue-50 hover:border-blue-300 transition-colors"
                                            data-amount="{{ $quickAmount }}">
                                        Rp {{ number_format($quickAmount / 1000, 0) }}K
                                    </button>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <!-- Bank Selection -->
                    <div class="mb-6">
                        <label for="bank_code" class="block text-sm font-medium text-gray-700 mb-2">
                            Bank Tujuan
                        </label>
                        <select id="bank_code" 
                                name="bank_code" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('bank_code') border-red-500 @enderror"
                                required>
                            <option value="">Pilih Bank</option>
                            @foreach($supportedBanks as $code => $name)
                                <option value="{{ $code }}" {{ old('bank_code') == $code ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                        @error('bank_code')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Account Number -->
                    <div class="mb-6">
                        <label for="account_number" class="block text-sm font-medium text-gray-700 mb-2">
                            Nomor Rekening
                        </label>
                        <input type="text" 
                               id="account_number" 
                               name="account_number" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('account_number') border-red-500 @enderror" 
                               placeholder="Pilih bank terlebih dahulu"
                               value="{{ old('account_number') }}"
                               pattern="[0-9]*"
                               inputmode="numeric"
                               disabled
                               required>
                        <div class="flex justify-between text-xs text-gray-500 mt-1">
                            <span id="account-length-info">Pilih bank untuk melihat format rekening</span>
                            <span id="account-counter" class="hidden">0/0</span>
                        </div>
                        @error('account_number')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <div id="account-error" class="text-red-500 text-xs mt-1 hidden"></div>
                    </div>

                    <!-- Account Name -->
                    <div class="mb-6">
                        <label for="account_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Pemilik Rekening
                        </label>
                        <input type="text" 
                               id="account_name" 
                               name="account_name" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('account_name') border-red-500 @enderror" 
                               placeholder="Nama sesuai rekening bank"
                               value="{{ old('account_name') }}"
                               pattern="[A-Za-z\s'.-]+"
                               title="Hanya huruf, spasi, apostrof, titik, dan tanda hubung diperbolehkan"
                               required>
                        @error('account_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <div id="account-name-error" class="text-red-500 text-xs mt-1 hidden"></div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            id="submit-btn"
                            class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors disabled:bg-gray-400 disabled:cursor-not-allowed">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Proses Penarikan
                    </button>

                    <p class="text-xs text-gray-500 mt-3 text-center">
                        Dana akan dikirim ke rekening Anda dalam 1-2 hari kerja
                    </p>
                </form>
            </div>
        </div>

        <!-- Recent Withdrawals -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-history text-gray-600 mr-2"></i>
                    Riwayat Penarikan
                </h3>

                @if($recentPayouts->count() > 0)
                    <div class="space-y-3">
                        @foreach($recentPayouts as $payout)
                            <div class="p-3 border border-gray-200 rounded-lg">
                                <div class="flex justify-between items-start mb-2">
                                    <span class="text-sm font-medium">{{ $payout->formatted_amount }}</span>
                                    <span class="px-2 py-1 text-xs rounded-full 
                                        @if($payout->status == 'completed') bg-green-100 text-green-800
                                        @elseif($payout->status == 'processing') bg-blue-100 text-blue-800
                                        @elseif($payout->status == 'failed') bg-red-100 text-red-800
                                        @else bg-yellow-100 text-yellow-800
                                        @endif">
                                        {{ $payout->status_label }}
                                    </span>
                                </div>
                                <div class="text-xs text-gray-500">
                                    <p>{{ $payout->bank_code }} - {{ $payout->account_number }}</p>
                                    <p>{{ $payout->created_at->format('d M Y, H:i') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-4">
                        <a href="{{ route('manajemen.transaksi.riwayat') }}" 
                           class="text-blue-600 text-sm hover:text-blue-800 font-medium">
                            Lihat Semua Riwayat →
                        </a>
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-inbox text-gray-300 text-3xl mb-3"></i>
                        <p class="text-gray-500 text-sm">Belum ada riwayat penarikan</p>
                    </div>
                @endif
            </div>

            <!-- Info Card -->
            <div class="bg-blue-50 rounded-xl p-4 mt-6">
                <h4 class="font-medium text-blue-900 mb-2 flex items-center">
                    <i class="fas fa-info-circle mr-2"></i>
                    Informasi Penarikan
                </h4>
                <ul class="text-sm text-blue-800 space-y-1">
                    <li>• Minimum penarikan Rp 50.000</li>
                    <li>• Proses 1-2 hari kerja</li>
                    <li>• Tanpa biaya admin</li>
                    <li>• 11 bank utama didukung</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('withdrawal-form');
    const amountInput = document.getElementById('amount');
    const bankSelect = document.getElementById('bank_code');
    const accountInput = document.getElementById('account_number');
    const submitBtn = document.getElementById('submit-btn');
    const amountError = document.getElementById('amount-error');
    const accountError = document.getElementById('account-error');
    const accountLengthInfo = document.getElementById('account-length-info');
    const accountCounter = document.getElementById('account-counter');
    const quickAmountBtns = document.querySelectorAll('.quick-amount-btn');
    const maxAmount = {{ $userModel->dompet }};
    const minAmount = 50000;

    // Bank account number limits and examples
    const bankLimits = {
        'BCA': { max: 10, min: 10, example: '2150324346' },
        'BNI': { max: 10, min: 10, example: '1234567890' },
        'BRI': { max: 18, min: 15, example: '123456789012345678' },
        'MANDIRI': { max: 13, min: 13, example: '1410024972143' },
        'CIMB': { max: 13, min: 13, example: '1234567890123' },
        'DANAMON': { max: 10, min: 10, example: '1234567890' },
        'PERMATA': { max: 10, min: 10, example: '1234567890' },
        'MAYBANK': { max: 12, min: 12, example: '123456789012' },
        'PANIN': { max: 10, min: 10, example: '1234567890' },
        'BSI': { max: 10, min: 10, example: '1234567890' },
        'MUAMALAT': { max: 10, min: 10, example: '1234567890' }
    };

    // Bank selection change handler
    bankSelect.addEventListener('change', function() {
        const selectedBank = this.value;
        
        if (selectedBank && bankLimits[selectedBank]) {
            const bankLimit = bankLimits[selectedBank];
            
            // Enable account input
            accountInput.disabled = false;
            accountInput.maxLength = bankLimit.max;
            
            // Update placeholder and info
            accountInput.placeholder = `Contoh: ${bankLimit.example}`;
            
            if (bankLimit.min === bankLimit.max) {
                accountLengthInfo.textContent = `${selectedBank} - ${bankLimit.max} digit`;
            } else {
                accountLengthInfo.textContent = `${selectedBank} - ${bankLimit.min}-${bankLimit.max} digit`;
            }
            
            // Show counter
            accountCounter.classList.remove('hidden');
            updateAccountCounter();
            
            // Clear account input and validate
            accountInput.value = '';
            validateAccountNumber();
        } else {
            // Disable account input
            accountInput.disabled = true;
            accountInput.value = '';
            accountInput.placeholder = 'Pilih bank terlebih dahulu';
            accountLengthInfo.textContent = 'Pilih bank untuk melihat format rekening';
            accountCounter.classList.add('hidden');
            accountError.classList.add('hidden');
        }
    });

    // Account number input handler
    accountInput.addEventListener('input', function() {
        // Only allow numbers
        this.value = this.value.replace(/[^0-9]/g, '');
        updateAccountCounter();
        validateAccountNumber();
    });

    // Update account counter
    function updateAccountCounter() {
        const selectedBank = bankSelect.value;
        if (selectedBank && bankLimits[selectedBank]) {
            const current = accountInput.value.length;
            const max = bankLimits[selectedBank].max;
            accountCounter.textContent = `${current}/${max}`;
            
            // Color coding
            if (current === 0) {
                accountCounter.className = 'text-gray-500';
            } else if (current < bankLimits[selectedBank].min) {
                accountCounter.className = 'text-yellow-600';
            } else if (current <= max) {
                accountCounter.className = 'text-green-600';
            } else {
                accountCounter.className = 'text-red-600';
            }
        }
    }

    // Account number validation
    function validateAccountNumber() {
        const selectedBank = bankSelect.value;
        const accountNumber = accountInput.value;
        
        if (!selectedBank) {
            return false;
        }
        
        if (!accountNumber) {
            accountError.textContent = 'Nomor rekening wajib diisi';
            accountError.classList.remove('hidden');
            return false;
        }
        
        if (bankLimits[selectedBank]) {
            const bankLimit = bankLimits[selectedBank];
            const length = accountNumber.length;
            
            if (length < bankLimit.min) {
                accountError.textContent = `Nomor rekening ${selectedBank} minimal ${bankLimit.min} digit`;
                accountError.classList.remove('hidden');
                return false;
            }
            
            if (length > bankLimit.max) {
                accountError.textContent = `Nomor rekening ${selectedBank} maksimal ${bankLimit.max} digit`;
                accountError.classList.remove('hidden');
                return false;
            }
        }
        
        accountError.classList.add('hidden');
        return true;
    }

    // Quick amount buttons
    quickAmountBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const amount = this.dataset.amount;
            amountInput.value = amount;
            validateAmount();
            
            // Update button states
            quickAmountBtns.forEach(b => b.classList.remove('bg-blue-100', 'border-blue-400'));
            this.classList.add('bg-blue-100', 'border-blue-400');
        });
    });

    // Amount validation
    function validateAmount() {
        const amount = parseInt(amountInput.value) || 0;
        
        if (amount < minAmount) {
            amountError.textContent = 'Minimum penarikan adalah Rp 50.000';
            amountError.classList.remove('hidden');
            return false;
        }
        
        if (amount > maxAmount) {
            amountError.textContent = 'Saldo tidak mencukupi';
            amountError.classList.remove('hidden');
            return false;
        }
        
        amountError.classList.add('hidden');
        return true;
    }

    // Account name validation
    function validateAccountName() {
        const accountNameInput = document.getElementById('account_name');
        const accountNameError = document.getElementById('account-name-error');
        const accountName = accountNameInput.value.trim();
        
        if (!accountName) {
            accountNameError.textContent = 'Nama pemilik rekening wajib diisi';
            accountNameError.classList.remove('hidden');
            return false;
        }
        
        // Only allow letters, spaces, apostrophes, periods, and hyphens
        const nameRegex = /^[A-Za-z\s'.-]+$/;
        if (!nameRegex.test(accountName)) {
            accountNameError.textContent = 'Nama hanya boleh berisi huruf (tanpa angka atau simbol khusus)';
            accountNameError.classList.remove('hidden');
            return false;
        }
        
        accountNameError.classList.add('hidden');
        return true;
    }
    
    // Overall form validation
    function validateForm() {
        const isAmountValid = validateAmount();
        const isAccountValid = validateAccountNumber();
        const isBankSelected = bankSelect.value !== '';
        const isAccountNameValid = validateAccountName();
        
        const isFormValid = isAmountValid && isAccountValid && isBankSelected && isAccountNameValid;
        submitBtn.disabled = !isFormValid;
        
        return isFormValid;
    }

    // Real-time validation
    amountInput.addEventListener('input', validateForm);
    bankSelect.addEventListener('change', validateForm);
    accountInput.addEventListener('input', validateForm);
    
    // Account name input handler
    const accountNameInput = document.getElementById('account_name');
    accountNameInput.addEventListener('input', function() {
        // Remove any non-allowed characters as the user types
        this.value = this.value.replace(/[^A-Za-z\s'.-]/g, '');
        validateAccountName();
        validateForm();
    });

    // Form submission
    form.addEventListener('submit', function(e) {
        if (!validateForm()) {
            e.preventDefault();
            return false;
        }
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
    });

    // Initialize validation on page load
    if (bankSelect.value) {
        bankSelect.dispatchEvent(new Event('change'));
    }
    validateForm();
});
</script>
@endsection
