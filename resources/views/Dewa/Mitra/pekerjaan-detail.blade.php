@extends('Dewa.Base.Basic-page')

@section('css')
    <style>
        .job-detail-card {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border: none;
            border-radius: 8px;
        }
        .job-header {
            background-color: #1B4841;
            color: white;
            padding: 2rem;
            border-radius: 8px 8px 0 0;
        }
        .job-content {
            padding: 2rem;
            background-color: white;
        }
        .info-item {
            margin-bottom: 1.2rem;
        }
        .info-label {
            font-weight: bold;
            color: #1B4841;
            font-size: 0.95rem;
        }
        .info-value {
            color: #545454;
            margin-top: 0.3rem;
        }
        .salary-range {
            font-size: 1.3rem;
            font-weight: bold;
            color: #1B4841;
        }
        .btn-primary-custom {
            background-color: #1B4841;
            border-color: #1B4841;
            padding: 10px 25px;
            border-radius: 8px;
            font-weight: 500;
        }
        .btn-primary-custom:hover {
            background-color: #143a35;
            border-color: #143a35;
        }
        .btn-secondary-custom {
            background-color: #545454;
            border-color: #545454;
            color: white;
            padding: 10px 25px;
            border-radius: 8px;
        }
        .btn-secondary-custom:hover {
            background-color: #3d3d3d;
            border-color: #3d3d3d;
            color: white;
        }
        .btn-warning-custom {
            background-color: #F2C255;
            border-color: #F2C255;
            color: #1B4841;
            padding: 10px 25px;
            border-radius: 8px;
            font-weight: 500;
        }
        .btn-warning-custom:hover {
            background-color: #e6b147;
            border-color: #e6b147;
            color: #1B4841;
        }
        .deadline-warning {
            color: #dc3545;
            font-weight: 600;
        }
        .job-image {
            border-radius: 8px;
            border: 2px solid #f1f1f1;
        }
    </style>
@endsection

@section('content')
<div class="container mt-4 mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-8">
            <div class="card job-detail-card">
                <div class="job-header">
                    <h1 class="mb-2" style="font-weight: 600;">{{ $job->nama }}</h1>
                    <p class="mb-0 opacity-75"><i class="bi bi-geo-alt-fill me-2"></i>{{ $job->alamat }}</p>
                </div>
                
                <div class="job-content">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-label">💰 Rentang Gaji</div>
                                <div class="salary-range">
                                    Rp {{ number_format($job->min_gaji, 0, ',', '.') }} - 
                                    Rp {{ number_format($job->max_gaji, 0, ',', '.') }}
                                </div>
                            </div>
                            
                            <div class="info-item">
                                <div class="info-label">👥 Maksimal Pekerja</div>
                                <div class="info-value">{{ $job->max_pekerja }} orang</div>
                            </div>
                            
                            <div class="info-item">
                                <div class="info-label">📅 Tanggal Mulai</div>
                                <div class="info-value">{{ \Carbon\Carbon::parse($job->start_job)->format('d F Y') }}</div>
                            </div>
                            
                            <div class="info-item">
                                <div class="info-label">🏁 Tanggal Selesai</div>
                                <div class="info-value">{{ \Carbon\Carbon::parse($job->end_job)->format('d F Y') }}</div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            @if($job->deadline_job)
                            <div class="info-item">
                                <div class="info-label">⏰ Deadline Lamaran</div>
                                <div class="deadline-warning">{{ \Carbon\Carbon::parse($job->deadline_job)->format('d F Y') }}</div>
                            </div>
                            @endif
                            
                            <div class="info-item">
                                <div class="info-label">🗺️ Petunjuk Alamat</div>
                                <div class="info-value">{{ $job->petunjuk_alamat }}</div>
                            </div>
                            
                            @if($job->foto_job)
                            <div class="info-item">
                                <div class="info-label">📸 Foto Pekerjaan</div>
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $job->foto_job) }}" 
                                         alt="Foto Pekerjaan" 
                                         class="img-fluid job-image" 
                                         style="max-height: 200px; width: 100%; object-fit: cover;">
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <div class="info-item">
                        <div class="info-label">📝 Deskripsi Pekerjaan</div>
                        <div class="info-value mt-2" style="line-height: 1.6;">
                            {{ $job->deskripsi }}
                        </div>
                    </div>
                    
                    <div class="text-center mt-4 pt-3">
                        <a href="javascript:history.back()" class="btn btn-secondary-custom me-3">
                            <i class="bi bi-arrow-left me-1"></i> Kembali
                        </a>
                        @auth
                            <button class="btn btn-warning-custom" onclick="lamarPekerjaan({{ $job->id }})">
                                <i class="bi bi-send me-1"></i> Lamar Pekerjaan
                            </button>
                        @else
                            <a href="/Login" class="btn btn-primary-custom">
                                <i class="bi bi-box-arrow-in-right me-1"></i> Login untuk Melamar
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    function lamarPekerjaan(jobId) {
        // Add your job application logic here
        Swal.fire({
            title: 'Konfirmasi',
            text: 'Apakah Anda yakin ingin melamar pekerjaan ini?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Lamar!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirect to application form or submit application
                // You can customize this based on your application flow
                window.location.href = '/kerja/lamar/' + jobId;
            }
        });
    }
</script>
@endsection
