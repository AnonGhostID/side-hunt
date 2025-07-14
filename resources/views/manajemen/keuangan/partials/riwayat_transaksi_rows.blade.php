@forelse($transaksi as $t)
<tr class="bg-white">
    <td class="px-5 py-4 border-b border-gray-200 text-sm">{{ $t->updated_at->format('Y-m-d H:i') }}</td>
    <td class="px-5 py-4 border-b border-gray-200 text-sm">{{ $t->external_id }}</td>
    <td class="px-5 py-4 border-b border-gray-200 text-sm">{{ $t->description ?? '-' }}</td>
    <td class="px-5 py-4 border-b border-gray-200 text-sm">Rp {{ number_format($t->amount, 0, ',', '.') }}</td>
    <td class="px-5 py-4 border-b border-gray-200 text-sm">{{ strtoupper($t->method) }}</td>
    <td class="px-5 py-4 border-b border-gray-200 text-sm">
        @if($t->isPaid())
            <span class="text-green-600 font-semibold">Lunas</span>
        @else
            <span class="text-yellow-600 font-semibold">{{ ucfirst($t->status) }}</span>
        @endif
    </td>
    <td class="px-5 py-4 border-b border-gray-200 text-sm">
        @if($t->isPaid())
            <a href="{{ route('manajemen.topup.payment', ['external_id' => $t->external_id]) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded">Cek Status</a>
        @else
            <span class="text-gray-500">-</span>
        @endif
    </td>
</tr>
@empty
<tr>
    <td colspan="7" class="px-5 py-10 border-b border-gray-200 text-sm text-center text-gray-500">
        Tidak ada transaksi lainnya.
    </td>
</tr>
@endforelse
