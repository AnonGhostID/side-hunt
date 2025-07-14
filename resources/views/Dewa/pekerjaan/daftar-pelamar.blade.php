@extends('Dewa.Base.Basic-page')

@section('css')
<!-- <link rel="stylesheet" href="{{ auto_asset('Dewa/css/?.css') }}"> -->
<style>
    body {
        background: white;
    }

    .table-bg {
        background: white;
        border-radius: 24px;
        box-shadow: 0 4px 32px #00000012;
        padding: 2rem;
    }

    .table th {
        background: #444 !important;
        border: none;
        font-weight: 500;
        font-size: 1rem;
        color: #ffffff;
        vertical-align: middle;
    }

    .table td {
        background: transparent !important;
        border: none;
        font-size: 1rem;
        vertical-align: middle;
    }

    .see-more-btn {
        background: #4242423c;
        color: #424242;
        border-radius: 999px;
        font-weight: 500;
        border: none;
        transition: background 0.15s;
    }

    .see-more-btn:hover {
        background: #42424271;
        color: #222;
    }

    .status-icon {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        margin-right: 0.25rem;
    }

    /* Pagination custom */
    .pagination .page-item .page-link {
        background: white;
        color: #575757;
        border: none;
        border-radius: 50% !important;
        margin: 0 0.15rem;
    }

    .pagination .active .page-link {
        background: #575757;
        color: white;
    }

    .pagination .page-link:focus {
        box-shadow: none;
    }

    /* Dropdown style tweak */
    .form-select {
        border-radius: 1rem;
        background: white;
    }

    .fs-10 {
        font-size: 10px !important;
    }

    .fs-12 {
        font-size: 12px !important;
    }

    .setengahFull {
        width: fit-content;
    }

    @media (max-width: 768px) {
        .setengahFull {
            width: 100% !important;
        }
    }
</style>
@endsection
@section('add-onn')
<form id="formDelete" action="/lamaran/delete/" method="POST" style="display:none;">
    @csrf
</form>
<form action="" class="form_picked d-flex" method="post">
    @csrf
    <input type="text" name="idLamaran" class="idLamaran d-none" id="">
    <input type="text" name="status" id="" class="status d-none">
    <input type="text" name="alasan" id="" class="alasan d-none">
</form>
<div class="offcanvas to_reject offcanvas-start" data-bs-backdrop="static" tabindex="-1" id="form_Reject"
    aria-labelledby="staticBackdropLabel">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title fw-semibold text-danger" id="staticBackdropLabel">
            Konfirmasi Penolakan Lamaran
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Tutup"></button>
    </div>
    <div class="offcanvas-body">
        <form id="rejectForm" onsubmit="event.preventDefault(); submitForm('tolak');">
            <div class="mb-3">
                <label for="alasan" class="form-label">
                    Alasan Tidak Lolos <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control" oninput="fill_alasan(this)" id="alasan" name="alasan"
                    placeholder="Tuliskan alasan penolakan" value="{{ old('alasan') }}" required>
                <div class="invalid-feedback">
                    Alasan penolakan wajib diisi.
                </div>
            </div>
            <button type="submit" onclick="submit_form()" class="btn btn-danger w-100 shadow-sm">
                <i class="bi bi-send"></i> Kirim Alasan Penolakan
            </button>
        </form>
        <div class="text-muted mt-3 small">
            Tuliskan alasan penolakan secara singkat dan sopan
            Pastikan Anda menyampaikan alasan dengan sopan dan membangun.
        </div>
    </div>
</div>

<div class="offcanvas ToInterview offcanvas-start" data-bs-backdrop="static" tabindex="-1" id="formBackdrop"
    aria-labelledby="staticBackdropLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="staticBackdropLabel">Data Interview</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <form action="/pelamar/interview" method="POST">
            @csrf

            <div class="mb-3">
                <label for="tanggal_interview" class="form-label">Tanggal Interview*</label>
                <input type="datetime-local" class="form-control" id="tanggalInterview" name="tanggal_interview"
                    value="{{ old('tanggal_interview') }}" required>
                @error('tanggal_interview')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="link_nterview" class="form-label">Link Interview*</label>
                <input type="url" class="form-control" id="linkInterview" name="link_interview"
                    value="{{ old('link_interview') }}" placeholder="https://example.com" required>
                @error('link_interview')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="Message_Interview" class="form-label">Pesan Untuk User (opsional)</label>
                <input type="text" class="form-control" id="linkInterview" name="pesan_interview"
                    value="{{ old('pesan_interview') }}" placeholder="Jaringan harus normal">
                @error('pesan_interview')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <input type="text" class="form-control id" id="id" value="{{ old('id') }}" name="id" placeholder=""
                    style="display: none;">
            </div>

            @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <button type="submit" class="btn btn-primary w-100">Set Interview</button>
        </form>
    </div>
</div>

@endsection
@section('content')
<div class="container py-5">
    <div class="table-bg">
        <div
            class="d-flex justify-content-md-between justify-content-start  flex-column-reverse flex-md-row gap-2 align-items-md-center align-items-start mb-4">
            <div class="col-3 justify-content-start align-items-start setengahFull">
                <select class="form-select" onclick="set_to_status(this)">
                    <option value="all" selected>Semua</option>
                    <option value="tunda">Lamaran Masuk</option>
                    <option value="interview">Dalam Interview</option>
                    <option value="Menunggu Pekerjaan">Menunggu Bekerja</option>
                    <option value="Sedang Bekerja">Sedang Bekerja</option>
                    <option value="Menuggu Pembayaran">Menuggu Pembayaran</option>
                    <option value="selesai">Lamaran Selesai</option>
                    <option value="ditolak">Lamaran Ditolak</option>
                    <option value="Gagal">Lamaran Gagal</option>
                </select>
            </div>
            <div class="text-center flex-column justify-content-center align-items-center w-100 flex-grow-1 mb-0 fw-bold"
                style="color: #2c2c2c;">
                <h3>Daftar Pelamar</h3>
                <h3>{{{$direction=='all'?'Semua Pekerjaan Yang Terdaftar':$pekerjaan->nama}}}</h3>

            </div>
            <div class="col-3"></div>
        </div>
        <div class="table-responsive" style="min-height: 45vh;">

            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th class="text-center">Tanggal Daftar</th class="text-center">
                        <th class="text-center">Pekerjaan yang dilamar</th class="text-center">
                        <th class="text-center">Nama Pelamar</th class="text-center">
                        <th class="text-center">Keahlian</th class="text-center">
                        <th class="text-center">Interview</th class="text-center">
                        <th class="text-center">Jadwal Interview</th class="text-center">
                        <th class="text-center">Message</th class="text-center">
                        <th class="text-center">Action</th class="text-center">
                    </tr>
                </thead>
                <tbody>
                    @if(!count($pelamars)==0)
                    @foreach($pelamars as $pelamar)
                    <tr class="the_job @if(in_array($pelamar->status_job, ['ditolak','Gagal'])) opacity-50 @endif"
                        data-status="{{{$pelamar->status_job}}}">
                        <td class="fs-12">{{{$pelamar->daftar}}}</td>
                        <td onclick="window.location.href='/Pelamar/Profile/{{{$pelamar->id}}}'">
                            {{{$pelamar->nama}}}

                        </td>
                        <td>
                            <div class="d-flex flex-row gap-2">
                                <p class="clear-p text-truncate">{{{$pelamar->nama_pelamar}}}</p>

                            </div>
                        </td>
                        <td style="font-size: 10px;">{{{$pelamar->preferensi_user}}}</td>
                        <td>
                            @if($pelamar->link!=null)
                            <a href="{{{$pelamar->link}}}">klik</a>
                            @endif
                        </td>
                        <td>
                            @if($pelamar->jadwal!=null)
                            <p class="clear-p" style="font-size: 10px;">
                                {{{\Carbon\Carbon::parse($pelamar->jadwal)->translatedFormat('l, d F Y H:i')}}}</p>
                            @endif
                        </td>
                        <td class="text-center">
                            @if(!(in_array($pelamar->status_job, ['ditolak','Gagal'])))
                            <button class="btn btn-toolbar" onclick="window.open('/chat/{{{$pelamar->id_pelamar}}}')">
                                <i class="bi bi-envelope text-center"></i>
                            </button>
                            @endif

                        </td>

                        <td>
                            <div class="d-flex flex-row gap-2 justify-content-center align-items-center">
                                @if($pelamar->status_job=='tunda')
                                <button class="btn btn-success rounded-5"
                                    onclick="lanjut_interview('{{{$pelamar->id_pelamars}}}')" data-bs-toggle="offcanvas"
                                    data-bs-target="#formBackdrop" aria-controls="staticBackdrop">Interview</button>
                                <button class="btn btn-danger rounded-5"
                                    onclick="tolak('{{{$pelamar->id_pelamars}}}')">Tolak</button>
                                @elseif($pelamar->status_job=='interview')
                                <button class="btn btn-success rounded-5"
                                    onclick="interview_sukses('{{{$pelamar->id_pelamars}}}')">Terima</button>
                                <button class="btn btn-danger rounded-5"
                                    onclick="interview_gagal('{{{$pelamar->id_pelamars}}}', alasan)">Gagal</button>
                                @elseif($pelamar->status_job=='ditolak')
                                <button class="btn btn-disabled">Ditolak {{{$pelamar->alasan==null?'':'('.$pelamar->alasan.')'}}}</button>
                                @elseif($pelamar->status_job=='Gagal')
                                <button class="btn btn-disabled">Tidak Lolos Interview {{{$pelamar->alasan==null?'':'('.$pelamar->alasan.')'}}}</button>

                                @else
                                <button class="btn btn-disabled">Diterima: {{{$pelamar->status_job}}}</button>
                                @endif

                                @if(in_array($pelamar->status_job, ['tunda', 'interview','ditolak','Gagal']))
                                <button class="btn btn-toolbar" onclick="delete_lamaran('{{{$pelamar->id_pelamars}}}')">
                                    <i class="bi bi-trash text-black"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    @else

                    @endif
                </tbody>
            </table>
            @if(count($pelamars)==0)
            <div class="d-flex w-100 justify-content-center mt-4 align-items-center">
                <p class="clear-p">Belum ada pelamar</p>
            </div>
            @endif
        </div>

        <!-- Pagination -->
        <!-- <div class="d-flex justify-content-center mt-4">
            <nav>
                <ul class="pagination mb-0">
                    <li class="page-item disabled">
                        <span class="page-link"><i class="bi bi-chevron-left"></i></span>
                    </li>
                    <li class="page-item active"><span class="page-link">1</span></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><span class="page-link">...</span></li>
                    <li class="page-item"><a class="page-link" href="#">18</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#"><i class="bi bi-chevron-right"></i></a>
                    </li>
                </ul>
            </nav>
        </div> -->
    </div>
</div>
@endsection

@section('script')
<script>
    @if ($errors->any())
        show_form_interview()
    @endif

    function delete_lamaran(id_lamaran){
        let form = document.querySelector('#formDelete')
        form.setAttribute('action', `/lamaran/delete/${id_lamaran}`);
        form.submit()
    }

    function set_to_status(elemen) {
        let value = elemen.value;
        let all_the_jobs = document.querySelectorAll('.the_job')
        all_the_jobs.forEach(job => {
            if (value == 'all') {
                job.style.display = "";
            }
            else {
                // job.style.display="none";
                if (job.getAttribute('data-status') == value) {
                    job.style.display = "";
                }
                else {
                    job.style.display = "none";
                }
            }
            // console.log('out: ',job.getAttribute('data-status'))

        })
    }

    function show_form_interview() {
        var offcanvasElement = document.getElementById('formBackdrop');
        var offcanvas = new bootstrap.Offcanvas(offcanvasElement);
        offcanvas.show();
    }
    function lanjut_interview(idLamaran) {
        let id = document.querySelector('.ToInterview .id')
        id.value = idLamaran;
    }
    function tolak(idLamaran) {
        form('/pelamar/tolak', 'ditolak', idLamaran, null);
    }
    function interview_sukses(idLamaran) {
        form('/pelamar/terima', 'Menunggu Pekerjaan', idLamaran, null);
    }
    function interview_gagal(idLamaran) {
        form('/pelamar/tolak', 'Gagal', idLamaran, null);
    }


    function form(link, status, idLamaran, alasan) {
        let form = document.querySelector('.form_picked')
        form.setAttribute('action', link);
        form.querySelector('.idLamaran').value = idLamaran
        form.querySelector('.status').value = status
        form.querySelector('.alasan').value = alasan
        if (status != 'ditolak' || status != 'Gagal') {
            form.submit();
        }
        else {
            var offcanvasElement = document.getElementById('form_Reject');
            var offcanvas = new bootstrap.Offcanvas(offcanvasElement);
            offcanvas.show();
        }
    }

    function fill_alasan(elemen) {
        document.querySelector('.form_picked .alasan').value = elemen.value;
    }
    function submit_form() {
        document.querySelector('.form_picked').submit();
    }
</script>
@endsection