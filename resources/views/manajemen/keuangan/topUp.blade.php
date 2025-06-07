@extends('layouts.management')

@section('title', 'Top Up Saldo')
@section('page-title', 'Top Up Saldo')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-xl mx-auto bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Isi Saldo Dompet</h2>
        <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded">
            <span class="text-gray-700">Saldo Dompet Saat Ini:</span>
            <span class="font-semibold">Rp {{ number_format($user->dompet, 0, ',', '.') }}</span>
        </div>
        <form action="#" method="POST">
            @csrf
            <div class="mb-4">
                <label for="nominal" class="block text-sm font-medium text-gray-700 mb-1">Pilih Nominal Top Up</label>
                <select id="nominal" name="nominal" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    @for ($i = 50000; $i <= 300000; $i += 50000)
                        <option value="{{ $i }}">Rp {{ number_format($i, 0, ',', '.') }}</option>
                    @endfor
                    <option value="500000">Rp {{ number_format(500000, 0, ',', '.') }}</option>
                    <option value="1000000">Rp {{ number_format(1000000, 0, ',', '.') }}</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Metode Pembayaran</label>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    <label class="flex items-center space-x-2">
                        <input type="radio" name="metode" value="QRIS" class="form-radio text-blue-600" required>
                        <span>QRIS</span>
                    </label>
                    <label class="flex items-center space-x-2">
                        <input type="radio" name="metode" value="DANA" class="form-radio text-blue-600" required>
                        <span>DANA</span>
                    </label>
                    <label class="flex items-center space-x-2">
                        <input type="radio" name="metode" value="GoPay" class="form-radio text-blue-600" required>
                        <span>GoPay</span>
                    </label>
                    <label class="flex items-center space-x-2">
                        <input type="radio" name="metode" value="BNI" class="form-radio text-blue-600" required>
                        <span>BNI</span>
                    </label>
                    <label class="flex items-center space-x-2">
                        <input type="radio" name="metode" value="BCA" class="form-radio text-blue-600" required>
                        <span>BCA</span>
                    </label>
                </div>
            </div>
            <div class="flex justify-end pt-4">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition-colors duration-300">
                    <i class="fas fa-wallet mr-2"></i> Top Up Sekarang
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    console.log('Halaman Top Up dimuat.');
</script>
@endpush
