<div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-user"></i>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-900">{{ $pelamar->user->nama }}</p>
                    <p class="text-xs text-gray-500">
                        <i class="fas fa-envelope mr-2"></i>{{ $pelamar->user->email }}
                    </p>
                    @if($pelamar->user->telpon)
                        <p class="text-xs text-gray-500">
                            <i class="fas fa-phone mr-2"></i>{{ $pelamar->user->telpon }}
                        </p>
                    @endif
                </div>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold
                @if($pelamar->status === 'pending') bg-amber-100 text-amber-700
                @elseif($pelamar->status === 'diterima') bg-emerald-100 text-emerald-700
                @else bg-rose-100 text-rose-700
                @endif">
                @if($pelamar->status === 'pending')
                    <i class="fas fa-clock mr-2"></i>Pending
                @elseif($pelamar->status === 'diterima')
                    <i class="fas fa-check mr-2"></i>Diterima
                @else
                    <i class="fas fa-xmark mr-2"></i>Ditolak
                @endif
            </span>
            @if($pelamar->status === 'pending')
                <div class="flex items-center gap-2">
                    <button type="button" onclick="updateStatus({{ $pelamar->id }}, 'diterima')" class="inline-flex items-center rounded-lg bg-emerald-100 px-3 py-1.5 text-xs font-semibold text-emerald-700 transition hover:bg-emerald-200">
                        <i class="fas fa-check mr-1"></i>Terima
                    </button>
                    <button type="button" onclick="updateStatus({{ $pelamar->id }}, 'ditolak')" class="inline-flex items-center rounded-lg bg-rose-100 px-3 py-1.5 text-xs font-semibold text-rose-700 transition hover:bg-rose-200">
                        <i class="fas fa-xmark mr-1"></i>Tolak
                    </button>
                </div>
            @endif
        </div>
    </div>
    <p class="mt-3 text-xs text-gray-500">
        <i class="fas fa-calendar mr-2"></i>Melamar pada {{ $pelamar->created_at->format('d M Y H:i') }}
    </p>
</div>
