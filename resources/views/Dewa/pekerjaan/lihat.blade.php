@extends('Dewa.Base.Basic-page')

@section('css')
<style>
    .hfull {
        min-height: 100% !important;
        height: 100% !important;
        max-height: fit-content;
    }
    .hcont {
        min-height: 80vh;
    }
    .Deskripsi_area {
        width: 60% !important;
    }
    @media (max-width: 768px) {
        .Deskripsi_area {
            width: 93% !important;
        }
    }
</style>
@endsection

@section('content')
<div class="container w-100 hcont gap-1 d-flex my-5 p-3 flex-column justify-content-start align-items-start">
    <div class="w-100 d-flex justify-content-start">
        <p class="clear-p fw-bold">Detil Pekerjaan</p>
    </div>
    <div class="w-100 flex-grow-1 shadow p-2 rounded-4 flex gap-1 justify-content-start align-items-start d-flex flex-md-row flex-column" style="min-height: 100%;">
        <div class="d-flex m-3 h-100 gap-5 flex-column justify-content-start align-items-center" style="min-height: 100%;">
            <h4 class="clear-p fw-bolder text-truncate w-100 text-center text-wrap" style="color: #2E2D2C;">
                {{{isset($data_pekerjaan[0]->nama) ? $data_pekerjaan[0]->nama : 'Nama Pekerjaan'}}}
            </h4>
            <div class="w-100 flex-grow-1 rounded-2 justify-content-start align-items-center">
                <img class="rounded-2" src="{{ isset($data_pekerjaan[0]->foto_job) && $data_pekerjaan[0]->foto_job ? auto_asset($data_pekerjaan[0]->foto_job) : auto_asset('Dewa/img/default_jobs.jpg') }}" style="width: 100%; height: 100%; object-fit: cover;" alt="">
            </div>
            <div class="h-25 w-100 gap-2 d-flex flex-column justify-content-start align-items-start">
                <div class="d-flex flex-row gap-2 justify-content-center align-items-center">
                    <i class="bi bi-calendar2-plus"></i>
                    <p class="clear-p" style="font-size: 10px; color: #2E2D2C;">
                        {{ isset($data_pekerjaan[0]['created_at']) ? $data_pekerjaan[0]['created_at'] : 'Waktu tidak tersedia' }}
                    </p>
                </div>
                <div class="d-flex flex-row gap-2 justify-content-center align-items-center">
                    <i class="bi bi-pin-map clear-p"></i>
                    <p class="clear-p" style="font-size: 12px; color: #2E2D2C;">
                        {{ isset($data_pekerjaan[0]['alamat']) ? $data_pekerjaan[0]['alamat'] : 'Alamat tidak tersedia' }}
                    </p>
                </div>
                <div class="d-flex flex-row gap-2 justify-content-center align-items-center">
                    <i class="bi bi-coin clear-p"></i>
                    <p class="clear-p" style="font-size: 12px; color: #2E2D2C;">
                        {{ isset($data_pekerjaan[0]->min_gaji) && isset($data_pekerjaan[0]->max_gaji) ? $data_pekerjaan[0]->min_gaji.' - '.$data_pekerjaan[0]->max_gaji : 'Gaji tidak tersedia' }}
                    </p>
                </div>
                
                @if(session()->has('account'))
                    @if(session('account')['role'] != 'mitra')
                        <button class="btn btn-warning mt-2 w-100" onclick="lamarSekarang()">Lamar Sekarang</button>
                    @else
                        <button class="btn btn-warning mt-5 w-100" onclick="lihatPelamar()">Daftar Pelamar</button>
                    @endif
                @else
                    <button class="btn btn-info mt-2 w-100" onclick="window.location.href='/Login'">Login Untuk Mendaftar</button>
                @endif
            </div>
        </div>
        <div class="vr d-none d-md-flex"></div>
        <div class="m-3 p-3 h-100 d-flex justify-content-between Deskripsi_area d-flex flex-column gap-5">
            <div class="h-75">
                <h3 class="fw-bold">DESKRIPSI</h3>
                <hr>
                {!! isset($data_pekerjaan[0]->deskripsi) ? $data_pekerjaan[0]->deskripsi : 'Deskripsi tidak tersedia' !!}
            </div>
            
            <div class="">
                <h6 class="fw-bold">Tags</h6>
                <div class="d-flex flex-wrap gap-2">
                    @if(isset($data_pekerjaan[0]['kriteria']))
                        @foreach($data_pekerjaan[0]['kriteria'] as $kriteria)
                        <span class="badge w-auto text-wrap text-start fs-6 bg-primary">
                            {{{$kriteria}}}
                        </span>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
function lamarSekarang() {
    alert('Fitur melamar belum diimplementasi');
}

function lihatPelamar() {
    alert('Fitur daftar pelamar belum diimplementasi');
}
</script>
@endsection