@extends('layouts.portal')

@section('title', 'Lowongan Terdaftar')
@section('page-title', 'Lowongan Pekerjaan Anda')
@section('page-subtitle')
    Pantau status lowongan, kelola pelamar, dan tindak lanjuti proses rekrutmen langsung dari portal ini.
@endsection

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="space-y-6">
    @if($jobs->isEmpty())
        <div class="flex flex-col items-center justify-center rounded-2xl border border-dashed border-gray-300 bg-white px-8 py-16 text-center">
            <div class="flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 text-gray-400">
                <i class="fas fa-briefcase text-2xl"></i>
            </div>
            <h3 class="mt-6 text-lg font-semibold text-gray-900">Belum ada lowongan terdaftar</h3>
            <p class="mt-2 text-sm text-gray-500">Mulai buat lowongan baru untuk menemukan kandidat terbaik.</p>
            <a href="/kerja/create" class="mt-6 inline-flex items-center rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2">
                <i class="fas fa-plus mr-2"></i>Buat Lowongan Baru
            </a>
        </div>
    @else
        @foreach($jobs as $job)
            <article class="rounded-2xl border border-gray-200 bg-white shadow-sm transition hover:border-blue-200">
                <div class="border-b border-gray-100 bg-gradient-to-r from-slate-900 to-slate-700 px-6 py-6 text-white">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <h2 class="text-xl font-semibold">{{ $job->nama }}</h2>
                            <p class="mt-1 text-sm text-slate-200">{{ $job->alamat }}</p>
                        </div>
                        <div class="flex flex-wrap items-center gap-3">
                            <span class="inline-flex items-center rounded-full bg-white/10 px-3 py-1 text-xs font-medium text-white">
                                <i class="fas fa-users mr-2"></i>{{ $job->pelamar->count() }} Pelamar
                            </span>
                            <span class="inline-flex items-center rounded-full bg-white/10 px-3 py-1 text-xs font-medium text-white">
                                <i class="fas fa-calendar-alt mr-2"></i>{{ $job->created_at->format('d M Y') }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="grid gap-6 px-6 py-6 lg:grid-cols-2">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                            <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Rentang Gaji</p>
                            <p class="mt-2 text-sm font-medium text-gray-900">Rp {{ number_format($job->min_gaji, 0, ',', '.') }}
                                @if($job->min_gaji !== $job->max_gaji)
                                    – Rp {{ number_format($job->max_gaji, 0, ',', '.') }}
                                @endif
                            </p>
                        </div>
                        <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                            <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Maks Pekerja</p>
                            <p class="mt-2 text-sm font-medium text-gray-900">{{ $job->max_pekerja }} orang</p>
                        </div>
                        <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                            <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Mulai</p>
                            <p class="mt-2 text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($job->start_job)->format('d M Y H:i') }}</p>
                        </div>
                        <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                            <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Selesai</p>
                            <p class="mt-2 text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($job->end_job)->format('d M Y H:i') }}</p>
                        </div>
                    </div>

                    <div class="flex flex-col justify-between gap-4">
                        <p class="text-sm text-gray-600"><span class="font-semibold text-gray-800">Petunjuk lokasi:</span> {{ $job->petunjuk_alamat ?? 'Tidak ada keterangan tambahan.' }}</p>
                        <div class="flex flex-wrap items-center gap-3">
                            <a href="/kerja/{{ $job->id }}" class="inline-flex items-center rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition hover:border-blue-200 hover:text-blue-600">
                                <i class="fas fa-eye mr-2"></i>Lihat Detail
                            </a>
                            @if(isset($userRole) && $userRole === 'user')
                                @php $statusApp = $appliedJobs[$job->id] ?? null; @endphp
                                @if($statusApp)
                                    <span class="inline-flex items-center rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600">Status lamaran: {{ ucfirst($statusApp) }}</span>
                                @else
                                    <form action="{{ route('pekerjaan.lamar', $job->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">
                                            <i class="fas fa-paper-plane mr-2"></i>Lamar Sekarang
                                        </button>
                                    </form>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>

                @if(isset($userRole) && ($userRole === 'mitra' || $userRole === 'admin'))
                    <div class="border-t border-gray-100 px-6 py-6">
                        <details class="group">
                            <summary class="flex cursor-pointer items-center justify-between rounded-xl bg-gray-50 px-4 py-3 text-sm font-semibold text-gray-700 transition group-open:bg-gray-100">
                                <span><i class="fas fa-people-group mr-2 text-gray-500"></i>Daftar Pelamar ({{ $job->pelamar->count() }})</span>
                                <i class="fas fa-chevron-down text-xs transition group-open:rotate-180"></i>
                            </summary>
                            <div class="mt-4 space-y-5">
                                @if($job->pelamar->isEmpty())
                                    <div class="rounded-xl border border-dashed border-gray-200 bg-white px-5 py-8 text-center text-sm text-gray-500">
                                        Belum ada pelamar untuk lowongan ini.
                                    </div>
                                @else
                                    @php
                                        $grouped = $job->pelamar->sortByDesc('created_at')->groupBy('status');
                                        $statusOrder = ['pending' => 'Menunggu Review', 'diterima' => 'Diterima', 'ditolak' => 'Ditolak'];
                                    @endphp

                                    @foreach($statusOrder as $statusKey => $label)
                                        @if(isset($grouped[$statusKey]) && $grouped[$statusKey]->count() > 0)
                                            <div class="space-y-3">
                                                <div class="flex items-center gap-2">
                                                    <span class="text-sm font-semibold text-gray-700">{{ $label }}</span>
                                                    <span class="text-xs font-medium text-gray-500 bg-gray-100 rounded-full px-2 py-1">{{ $grouped[$statusKey]->count() }}</span>
                                                </div>
                                                <div class="grid gap-3">
                                                    @foreach($grouped[$statusKey] as $pelamar)
                                                        @include('portal.Mitra.partials.applicant-card', compact('pelamar', 'job'))
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                        </details>
                    </div>
                @endif
            </article>
        @endforeach
    @endif
    </div>
</div>
@endsection

@section('script')
<script>
    function updateStatus(pelamarId, status) {
        if (confirm(`Apakah Anda yakin ingin ${status === 'diterima' ? 'menerima' : 'menolak'} pelamar ini?`)) {
            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('_method', 'PATCH');
            
            fetch(`/portal/mitra/pelamar/${pelamarId}/${status}`, {
                method: 'POST',
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Terjadi kesalahan. Silakan coba lagi.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan. Silakan coba lagi.');
            });
        }
    }
</script>
@endsection 
