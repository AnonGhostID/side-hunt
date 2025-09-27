@extends('Dewa.Base.Basic-page')

@section('css')
<style>
.job {
    background-color: #CDCDCD !important;
}
</style>
@endsection

@section('content')
<div class="container w-100 h-auto gap-5 d-flex my-5 flex-column justify-content-center align-items-center">
    <div class="w-100 d-flex mt-5 mb-2 flex-column justify-content-center align-items-center">
        <p class="fw-bolder" style="font-size: 30px;">History Pekerjaan yang Pernah Anda Daftarkan</p>
        <div class="form-control w-75 ps-3 rounded-5 shadow d-flex flex-row gap-0 justify-content-center align-items-center">
            <i class="bi bi-search w-auto"></i>
            <input type="text" class="searchBox form-control rounded-5 flex-1 px-2 no-outline border-0" maxlength="150" name="searchBox" oninput="SearchBox(this)" value="{{ old('nama') }}" placeholder="">
        </div>
    </div>
    
    <div class="result d-flex w-100 d-flex mt-0 flex-column row">
        Semua Pekerjaan,
        <div class="w-100 p-3 mt-2 border-3 border-black shadow rounded-3">
            <div class="w-100 justify-content-center align-items-center d-flex">
                <div class="g-3 border-3 border-black mt-0 rounded-4 p-3 pt-0 row d-flex gy-4 flex-wrap justify-content-start flex-grow-1 align-items-start" style="min-height: 43vh; width: fit-content; max-width: fit-content; min-width: 100%;">
                    @if(isset($pekerjaans) && count($pekerjaans) > 0)
                        @foreach($pekerjaans as $pekerjaan)
                        <div class="col-12 col-md-6 col-lg-6 the_job">
                            <a href="/Pekerjaan/{{{$pekerjaan['id'] ?? '#'}}}" class="w-100 h-100 job text-decoration-none d-flex flex-row p-0 gap-0 rounded-2 align-items-center justify-content-between">
                                <div class="rounded-start-2 justify-content-start align-items-center" style="width: 40%; min-width: 40%; height: 100%;">
                                    <img class="rounded-start-2" src="{{ isset($pekerjaan['foto_job']) && $pekerjaan['foto_job'] ? auto_asset($pekerjaan['foto_job']) : auto_asset('Dewa/img/default_jobs.jpg') }}" style="width: 100%; height: 100%; max-height: 100px; object-fit: cover;" alt="">
                                </div>
                                <div class="d-flex flex-column justify-content-center text-truncate align-items-start gap-0 flex-grow-1 h-100 p-2" style="max-width: 100%;">
                                    <p class="clear-p fw-bolder text-truncate w-100" style="font-size: 12px; color: #2E2D2C;">
                                        {{{$pekerjaan['nama'] ?? 'Nama Pekerjaan'}}}
                                    </p>
                                    <p class="clear-p opacity-50" style="font-size: 10px; color: #2E2D2C;">5 Jam yang lalu</p>
                                    <p class="clear-p opacity-50" style="font-size: 10px; color: #2E2D2C;">Jarak</p>
                                </div>
                            </a>
                        </div>
                        @endforeach
                        <div class="notFound w-100 justify-content-center align-items-center" style="display: none;">
                            <p>Tidak ada pekerjaan yang ditemukan</p>
                        </div>
                    @else
                        <p>Belum ada pekerjaan yang terdaftar</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
function SearchBox(elemen) {
    let all_jobs = document.querySelectorAll('.the_job')
    let count = 0;
    all_jobs.forEach(element => {
        if (element.innerText.toLowerCase().includes(elemen.value.toLowerCase())) {
            element.style.display = "flex"
            count += 1;
        } else {
            element.style.display = "none"
        }
    });

    if (count == 0) {
        document.querySelector('.notFound').style.display = 'flex'
    } else {
        document.querySelector('.notFound').style.display = 'none'
    }
}
</script>
@endsection