@extends('layouts.portal')

@section('title', 'Profil Saya')
@section('page-title', 'Profil Saya')
@section('page-subtitle')
    Kelola data pribadi dan temukan rekomendasi pekerjaan terbaru.
@endsection

@section('content')
@php $account = session('account'); @endphp
<div class="grid gap-8 lg:grid-cols-3">
    <section class="lg:col-span-1 space-y-4">
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="flex h-14 w-14 items-center justify-center rounded-full bg-blue-100 text-blue-600 text-xl font-semibold">
                    {{ strtoupper(substr($account->nama ?? $account['nama'] ?? 'U', 0, 1)) }}
                </div>
                <div>
                    <p class="text-base font-semibold text-gray-900">{{ $account->nama ?? $account['nama'] }}</p>
                    <p class="text-xs text-gray-500">{{ $account->email ?? $account['email'] }}</p>
                </div>
            </div>
            <dl class="mt-6 space-y-3 text-sm text-gray-600">
                <div class="flex items-start justify-between">
                    <dt class="font-medium text-gray-700">Peran</dt>
                    <dd class="text-gray-500 capitalize">{{ $account->role ?? $account['role'] }}</dd>
                </div>
                <div class="flex items-start justify-between">
                    <dt class="font-medium text-gray-700">Lokasi</dt>
                    <dd class="text-right text-gray-500">{{ $account->alamat ?? $account['alamat'] ?? '-' }}</dd>
                </div>
                <div class="flex items-start justify-between">
                    <dt class="font-medium text-gray-700">No. Telepon</dt>
                    <dd class="text-right text-gray-500">{{ $account->telpon ?? $account['telpon'] ?? '-' }}</dd>
                </div>
            </dl>
        </div>
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <h3 class="text-sm font-semibold text-gray-900">Statistik Singkat</h3>
            <div class="mt-4 grid gap-3 text-sm">
                <div class="rounded-xl border border-blue-100 bg-blue-50 px-4 py-3">
                    <p class="text-xs text-blue-600">Total Lowongan Terbaru</p>
                    <p class="mt-1 text-lg font-semibold text-blue-800">{{ $jobs->count() }}</p>
                </div>
                <div class="rounded-xl border border-emerald-100 bg-emerald-50 px-4 py-3">
                    <p class="text-xs text-emerald-600">Rekomendasi Tersedia</p>
                    <p class="mt-1 text-lg font-semibold text-emerald-800">{{ $jobs->take(3)->count() }}</p>
                </div>
            </div>
        </div>
    </section>

    <section class="lg:col-span-2 space-y-8">
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <h3 class="text-base font-semibold text-gray-900">Perbarui Informasi</h3>
            <form action="/Profile/Edit" method="POST" class="mt-6 space-y-5">
                @csrf
                <div>
                    <label for="nama" class="block text-sm font-semibold text-gray-700">Nama Lengkap</label>
                    <input id="nama" type="text" name="nama" value="{{ old('nama', $account->nama ?? $account['nama']) }}" class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-3 text-sm text-gray-700 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
                </div>
                <div>
                    <label for="alamat" class="block text-sm font-semibold text-gray-700">Alamat</label>
                    <input id="alamat" type="text" name="alamat" value="{{ old('alamat', $account->alamat ?? $account['alamat']) }}" class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-3 text-sm text-gray-700 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200" placeholder="Contoh: Bandung, Jawa Barat">
                </div>
                <div>
                    <label for="telpon" class="block text-sm font-semibold text-gray-700">No. Telepon</label>
                    <input id="telpon" type="text" name="telpon" value="{{ old('telpon', $account->telpon ?? $account['telpon']) }}" class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-3 text-sm text-gray-700 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200" placeholder="Contoh: 081234567890">
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="inline-flex items-center rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2">
                        <i class="fas fa-save mr-2"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <h3 class="text-base font-semibold text-gray-900">Lowongan Terbaru</h3>
                <a href="/kerja" class="text-xs font-semibold text-blue-600">Lihat semua</a>
            </div>
            <div class="mt-5 grid gap-4 sm:grid-cols-2">
                @forelse($jobs->take(4) as $job)
                    <a href="/kerja/{{ $job->id }}" class="group flex flex-col rounded-2xl border border-gray-200 bg-gray-50 p-4 shadow-sm transition hover:border-blue-200 hover:bg-white">
                        <div class="flex items-start justify-between">
                            <p class="text-sm font-semibold text-gray-900 group-hover:text-blue-600">{{ $job->nama }}</p>
                            <span class="text-xs text-gray-400">{{ $job->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="mt-2 text-xs text-gray-500"><i class="fas fa-location-dot mr-2"></i>{{ $job->alamat }}</p>
                        <p class="mt-3 text-xs font-medium text-gray-600">
                            <i class="fas fa-coins mr-2 text-amber-500"></i>Rp {{ number_format($job->min_gaji, 0, ',', '.') }}
                            @if($job->min_gaji !== $job->max_gaji)
                                – Rp {{ number_format($job->max_gaji, 0, ',', '.') }}
                            @endif
                        </p>
                    </a>
                @empty
                    <div class="col-span-full rounded-xl border border-dashed border-gray-200 bg-white px-6 py-10 text-center text-sm text-gray-500">
                        Belum ada lowongan yang tersedia.
                    </div>
                @endforelse
            </div>
        </div>
    </section>
</div>
@endsection
