@forelse($transaksi as $t)
<div class="bg-white rounded-lg shadow-md p-4 border border-gray-200">
    <div class="flex justify-between items-start mb-3">
        <div class="flex-1">
            <div class="text-sm text-gray-500 mb-1">{{ $t->updated_at->format('d M Y H:i') }}</div>
            <div class="font-medium text-gray-900 text-sm mb-2">
                @if($t->type == 'payment')
                    {{ $t->description ?? 'Top Up Saldo' }}
                @else
                    Penarikan Dana - {{ $t->account_name ?? '-' }}
                @endif
            </div>
        </div>
        <div class="ml-3">
            @if($t->type == 'payment')
                <span class="text-green-600 font-bold text-lg">+Rp {{ number_format($t->amount, 0, ',', '.') }}</span>
            @else
                <span class="text-red-600 font-bold text-lg">-Rp {{ number_format($t->amount, 0, ',', '.') }}</span>
            @endif
        </div>
    </div>
    
    <div class="grid grid-cols-2 gap-3 text-sm mb-3">
        <div>
            <span class="text-gray-500">ID Transaksi:</span>
            <div class="font-medium truncate">
                @if($t->type == 'payment')
                    {{ $t->external_id ?? '-' }}
                @else
                    {{ $t->xendit_reference_id ?? $t->id }}
                @endif
            </div>
        </div>
        <div>
            <span class="text-gray-500">Metode:</span>
            <div class="font-medium">
                @if($t->type == 'payment')
                    {{ strtoupper($t->method ?? '-') }}
                @else
                    {{ strtoupper($t->payment_type ?? 'BANK') }}
                @endif
            </div>
        </div>
    </div>

    <div class="flex justify-between items-center">
        <span class="px-3 py-1 text-xs rounded-full 
            @if($t->status == 'completed') bg-green-100 text-green-800
            @elseif($t->status == 'processing') bg-blue-100 text-blue-800
            @elseif($t->status == 'failed') bg-red-100 text-red-800
            @elseif($t->status == 'pending') bg-yellow-100 text-yellow-800
            @else bg-gray-100 text-gray-800
            @endif">
            {{ $t->status_label }}
        </span>
        
        <div>
            @if($t->type == 'payment' && $t->external_id && str_starts_with($t->external_id, 'fee_gaji_'))
                <a href="{{ route('manajemen.fee.gaji.show', $t->id) }}" class="bg-purple-500 hover:bg-purple-600 text-white px-3 py-1 rounded text-xs">
                    Fee Gaji
                </a>
            @elseif($t->type == 'payment' && $t->external_id)
                <a href="{{ route('manajemen.topup.payment', ['external_id' => $t->external_id]) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs">
                    Cek Status
                </a>
            @elseif($t->type == 'payout')
                <a href="{{ route('manajemen.payout.show', $t->id) }}" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs">
                    Detail
                </a>
            @else
                <span class="text-gray-400 text-xs">{{ $t->type_label }}</span>
            @endif
        </div>
    </div>
</div>
@empty
<div class="bg-white rounded-lg shadow-md p-8 text-center">
    <div class="text-gray-500 mb-2">
        <i class="fas fa-receipt text-4xl text-gray-300"></i>
    </div>
    <p class="text-gray-500">Tidak ada transaksi lainnya.</p>
</div>
@endforelse