@extends('layouts.management')

@section('title', 'Dashboard Manajemen')
@section('page-title', 'Dashboard Utama')

@section('content')
<div class="container mx-auto px-4 py-8">
    @if($user->role == 'admin')
        {{-- Admin Dashboard --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="bg-white p-4 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center">
                    <div class="p-2 rounded-full bg-red-500 text-white mr-3">
                        <i class="fas fa-exclamation-triangle fa-lg"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Laporan Penipuan</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $totalReports ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-4 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center">
                    <div class="p-2 rounded-full bg-blue-500 text-white mr-3">
                        <i class="fas fa-headset fa-lg"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Permintaan Bantuan</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $totalHelpRequests ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-4 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center">
                    <div class="p-2 rounded-full bg-green-500 text-white mr-3">
                        <i class="fas fa-check-circle fa-lg"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Kasus Selesai</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $totalResolved ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Selamat Datang di Panel Administrator!</h2>
            <p class="text-gray-600">
                Sebagai administrator, Anda memiliki akses ke panel bantuan dan laporan penipuan untuk mengelola 
                keamanan dan mendukung pengguna platform SideHunt.
            </p>
            <div class="mt-6">
                <a href="{{ route('manajemen.bantuan.panel') }}" class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition-colors duration-300">
                    <i class="fas fa-shield-alt mr-2"></i>Panel Bantuan dan Laporan Penipuan
                </a>
            </div>
        </div>

        <div class="mt-8 bg-white p-6 rounded-lg shadow-lg">
            <h3 class="text-lg font-semibold text-gray-700 mb-3">Laporan Terbaru</h3>
            @if($recentTickets && $recentTickets->count() > 0)
                <ul class="space-y-3">
                    @foreach($recentTickets as $ticket)
                        <li class="flex items-center text-sm text-gray-600">
                            @if($ticket->type == 'penipuan')
                                <i class="fas fa-exclamation-triangle text-red-500 mr-3"></i>
                                Laporan penipuan: {{ Str::limit($ticket->subject, 40) }}
                            @elseif($ticket->type == 'bantuan')
                                <i class="fas fa-question-circle text-yellow-500 mr-3"></i>
                                Permintaan bantuan: {{ Str::limit($ticket->subject, 40) }}
                            @endif
                            @if($ticket->status == 'closed')
                                <i class="fas fa-check-circle text-green-500 ml-2"></i>
                            @endif
                            <span class="ml-auto text-xs text-gray-400">
                                {{ $ticket->created_at->diffForHumans() }}
                            </span>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="text-center text-gray-500 py-4">
                    <i class="fas fa-inbox fa-2x mb-2"></i>
                    <p>Belum ada laporan terbaru</p>
                </div>
            @endif
        </div>
    @elseif($user->role == 'mitra')
        {{-- MITRA Dashboard - Job Creator --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white p-4 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center">
                    <div class="p-2 rounded-full bg-blue-500 text-white mr-3">
                        <i class="fas fa-briefcase fa-lg"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Pekerjaan Aktif</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $totalPekerjaans ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-4 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center">
                    <div class="p-2 rounded-full bg-orange-500 text-white mr-3">
                        <i class="fas fa-user-clock fa-lg"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Lamaran Menunggu</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $pendingApplications ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-4 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center">
                    <div class="p-2 rounded-full bg-green-500 text-white mr-3">
                        <i class="fas fa-check-circle fa-lg"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Proyek Selesai</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $completedJobs ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-4 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center">
                    <div class="p-2 rounded-full bg-emerald-500 text-white mr-3">
                        <i class="fas fa-wallet fa-lg"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Saldo Dompet</p>
                        <p class="text-xl font-bold text-gray-800" data-balance>Rp {{ number_format($user->dompet, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Dashboard Mitra</h2>
            <p class="text-gray-600 mb-4">
                Kelola pekerjaan dan pantau aplikasi dari para pekerja. Anda dapat melihat status pekerjaan, 
                meninjau lamaran masuk, dan mengelola pembayaran dengan mudah.
            </p>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('manajemen.pekerjaan.terdaftar') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition-colors duration-300">
                    <i class="fas fa-list mr-2"></i>Kelola Pekerjaan
                </a>
                <a href="{{ url('/kerja/create') }}" class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition-colors duration-300">
                    <i class="fas fa-plus mr-2"></i>Buat Pekerjaan Baru
                </a>
            </div>
        </div>

        <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Lamaran Terbaru</h3>
                @if(isset($recentApplications) && $recentApplications->count() > 0)
                    <div class="space-y-3">
                        @foreach($recentApplications as $application)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-semibold">
                                        {{ substr($application->user->nama ?? 'U', 0, 1) }}
                                    </div>
                                    <div class="ml-3">
                                        <p class="font-medium text-gray-800">{{ $application->user->nama ?? 'User not found' }}</p>
                                        <p class="text-sm text-gray-600">{{ Str::limit($application->sidejob->nama ?? 'Job not found', 30) }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">
                                        {{ ucfirst($application->status) }}
                                    </span>
                                    <p class="text-xs text-gray-400 mt-1">{{ $application->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-gray-500 py-8">
                        <i class="fas fa-inbox fa-2x mb-3"></i>
                        <p>Belum ada lamaran masuk</p>
                        <p class="text-sm">Buat pekerjaan baru untuk menerima lamaran</p>
                    </div>
                @endif
            </div>

            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Performa Pekerjaan</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center p-3 bg-blue-50 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-star text-yellow-500 mr-3"></i>
                            <span class="text-gray-700">Rating Rata-rata</span>
                        </div>
                        <span class="font-semibold text-blue-600">{{ number_format($averageRating ?? 0, 1) }}/5.0</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-green-50 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-percentage text-green-500 mr-3"></i>
                            <span class="text-gray-700">Tingkat Penyelesaian</span>
                        </div>
                        <span class="font-semibold text-green-600">{{ $completionRate ?? 0 }}%</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-purple-50 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-clock text-purple-500 mr-3"></i>
                            <span class="text-gray-700">Rata-rata Waktu Rekrut</span>
                        </div>
                        <span class="font-semibold text-purple-600">{{ $averageHiringTime ?? '-' }} hari</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-orange-50 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-users text-orange-500 mr-3"></i>
                            <span class="text-gray-700">Total Pekerja Dipekerjakan</span>
                        </div>
                        <span class="font-semibold text-orange-600">{{ $totalHiredWorkers ?? 0 }} orang</span>
                    </div>
                </div>
            </div>
        </div>
    @else
        {{-- USER Dashboard - Job Seeker --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white p-4 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center">
                    <div class="p-2 rounded-full bg-blue-500 text-white mr-3">
                        <i class="fas fa-paper-plane fa-lg"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Lamaran Dikirim</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $totalApplications ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-4 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center">
                    <div class="p-2 rounded-full bg-green-500 text-white mr-3">
                        <i class="fas fa-handshake fa-lg"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Pekerjaan Diterima</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $acceptedJobs ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-4 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center">
                    <div class="p-2 rounded-full bg-purple-500 text-white mr-3">
                        <i class="fas fa-check-circle fa-lg"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Proyek Selesai</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $completedWork ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-4 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center">
                    <div class="p-2 rounded-full bg-emerald-500 text-white mr-3">
                        <i class="fas fa-coins fa-lg"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Total Penghasilan</p>
                        <p class="text-xl font-bold text-gray-800" data-balance>Rp {{ number_format($user->dompet, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Dashboard Pekerja</h2>
            <p class="text-gray-600 mb-4">
                Temukan pekerjaan yang sesuai dengan keahlian Anda dan pantau progress aplikasi. 
                Bangun reputasi dengan menyelesaikan pekerjaan berkualitas.
            </p>
            <div class="flex flex-wrap gap-3">
                <a href="{{ url('/kerja') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition-colors duration-300">
                    <i class="fas fa-search mr-2"></i>Cari Pekerjaan
                </a>
                <a href="{{ route('manajemen.pekerjaan.berlangsung') }}" class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition-colors duration-300">
                    <i class="fas fa-tasks mr-2"></i>Pekerjaan Berlangsung
                </a>
            </div>
        </div>

        <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Pekerjaan Terbaru Untukmu</h3>
                @if(isset($recommendedJobs) && $recommendedJobs->count() > 0)
                    <div class="space-y-3">
                        @foreach($recommendedJobs as $job)
                            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow duration-300">
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="font-semibold text-gray-800">{{ Str::limit($job->nama, 40) }}</h4>
                                    <span class="text-green-600 font-semibold text-sm">
                                        Rp {{ number_format($job->min_gaji, 0, ',', '.') }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 mb-2">{{ Str::limit($job->deskripsi, 80) }}</p>
                                <div class="flex justify-between items-center">
                                    <span class="text-xs text-gray-500">
                                        <i class="fas fa-map-marker-alt mr-1"></i>{{ $job->lokasi }}
                                    </span>
                                    <a href="{{ url('/kerja/' . $job->id) }}" class="text-blue-500 hover:text-blue-700 text-sm font-medium">
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-gray-500 py-8">
                        <i class="fas fa-search fa-2x mb-3"></i>
                        <p>Belum ada rekomendasi pekerjaan</p>
                        <p class="text-sm">Lengkapi profil untuk mendapat rekomendasi yang lebih baik</p>
                    </div>
                @endif
            </div>

            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Statistik Performa</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center p-3 bg-blue-50 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-star text-yellow-500 mr-3"></i>
                            <span class="text-gray-700">Rating Anda</span>
                        </div>
                        <span class="font-semibold text-blue-600">{{ number_format($userRating ?? 0, 1) }}/5.0</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-green-50 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-percentage text-green-500 mr-3"></i>
                            <span class="text-gray-700">Tingkat Penerimaan</span>
                        </div>
                        <span class="font-semibold text-green-600">{{ $acceptanceRate ?? 0 }}%</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-purple-50 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-clock text-purple-500 mr-3"></i>
                            <span class="text-gray-700">Rata-rata Respon</span>
                        </div>
                        <span class="font-semibold text-purple-600">{{ $averageResponseTime ?? '-' }} jam</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-orange-50 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-trophy text-orange-500 mr-3"></i>
                            <span class="text-gray-700">Keahlian Terbaik</span>
                        </div>
                        <span class="font-semibold text-orange-600">{{ $topSkill ?? 'Belum ada' }}</span>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
@endsection
