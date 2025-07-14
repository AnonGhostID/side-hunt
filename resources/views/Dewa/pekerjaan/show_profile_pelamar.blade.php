@extends('Dewa.Base.Basic-page')

@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<style>
    @media (max-width: 576px) {
        .lssetengah {
            width: 90% !important;
            min-width: 90% !important;
            max-width: 90% !important;
        }
    }

    @media (max-width: 768px) {
        .lssetengah {
            width: 80% !important;
        }
    }



    .styleButton {
        background-color: #D9D9D9 !important;
    }

    .main-content {
        position: relative;
    }

    .bgBlackLowOpacity {
        background-color: rgba(0, 0, 0, 0.208);
    }

    .contform {
        input {
            border: black 1px solid;
            outline: none;
        }
    }

    .lssetengah {
        width: 50% !important;
    }

    .bg-blue-dark {
        background-color: #0B00D8;
    }

    cl-blue-dark {
        color: #0B00D8;
    }
</style>
@endsection
@section('peta')

#map{
height: 400px;
width: 100%;
margin: 0;
padding: 0;
}

@endsection


@section('add-onn')
@endsection

@section('content')
<div class="container justify-content-center p-5 align-items-center text-white widthHeightFull">
    <div class="d-flex justify-content-between pe-3 align-items-start flex-row w-100 h-100">
        <h1 class="text-black fw-bolder">Profil Pelamar</h1>
        <div>
            <button class="btn btn-toolbar">Diterima</button>
        </div>
    </div>
    <div class="d-flex flex-column w-100 h-100">
        <div class="rounded-3 d-flex flex-row bg-prim-me p-5 justify-content-between align-items-center w-100"
            style="height: 30vh;">
            <div class="d-flex flex-column contText gap-1">
                <h2 class="ps-5 clear-p">{{{session('account')->nama}}}</h2>
                <div class="d-flex gap-3 flex-row">
                    <i class="bi bi-geo-fill text-white"></i>
                    <p class="text-white clear-p">
                        {{ session('account')->alamat == null ? 'Belum Diisi' : session('account')->alamat }}
                    </p>
                </div>
                <div class="d-flex gap-3 flex-row">
                    <i class="bi bi-envelope text-white"></i>
                    <p class="text-white clear-p">
                        {{ session('account')->email == null ? 'Belum Diisi' : session('account')->email }}
                    </p>
                </div>
                <div class="d-flex gap-3 flex-row">
                    <i class="bi bi-telephone text-white"></i>
                    <p class="text-white clear-p">
                        {{ session('account')->telpon == null ? 'Belum Diisi' : session('account')->telpon }}
                    </p>
                </div>
            </div>
            <div class="w-25 h-100  d-none d-md-flex justify-content-center align-items-center">
                <a class="navbar-brand w-50 h-auto" href="{{ url('/') }}">
                    <div class="container-logo w-100 h-auto d-flex">
                        <img src="{{ auto_asset('Dewa/img/logo.svg')}}" alt="">
                    </div>

                </a>
            </div>
        </div>
    </div>
</div>
<div class="container justify-content-center p-5 align-items-center text-white widthHeightFull">
    <div class="card w-100 shadow-lg" style="max-width: 100%; width: 100%;">
        <div class="card-body w-100">
            <div class="d-flex flex-row justify-content-between align-items-center">
                <h4 class="clear-p">Preferensi User</h4>
            </div>
            <hr>
            <div class="mb-4">
                <h5 class="mb-2">Deskripsi</h5>
                <p class="card-text">
                    {{{json_decode(session('account')->preferensi_user)->deskripsi}}}
                </p>
            </div>
            <div>
                <h5 class="mb-3">Keahlian</h5>
                <div class="d-flex flex-wrap gap-2">
                    @foreach(json_decode(session('account')->preferensi_user)->kriteria as $kriteria)
                    <span class="badge w-auto text-wrap text-start fs-5 bg-prim-me">{{{ucwords($kriteria)}}}</span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
</script>
@endsection