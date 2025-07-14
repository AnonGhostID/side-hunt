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

@section('add-onn')
<div class="offcanvas offcanvas-start" data-bs-backdrop="static" tabindex="-1" id="form_is_alone"
    aria-labelledby="formIsAloneLabel">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title fw-semibold text-black" id="formIsAloneLabel">
            Lengkapi Data Tim
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Tutup"></button>
    </div>

    <div class="offcanvas-body">
        <div class="alert alert-warning small mt-2" role="alert"> <strong>Perhatian:</strong>
            <ul>
                <li>Setiap individu yang Anda daftarkan melalui formulir ini <strong>sepenuhnya menjadi tanggung jawab
                        Anda sebagai pelamar.</strong></li>
                <li>Segala hal yang berkaitan dengan gaji, pekerjaan, komunikasi, serta bentuk pertanggungjawaban
                    lainnya merupakan <strong>urusan pribadi antara Anda dan pihak pemberi kerja.</strong> Kami
                    <strong>tidak berperan sebagai perantara</strong> maupun terlibat dalam hubungan kerja tersebut.
                </li>
            </ul>
        </div>

        <form id="team_data"
            onsubmit="event.preventDefault(); ValidasiPendaftaran('{{{$data_pekerjaan[0]->id}}}',true);">
            <div id="contactEntries" class="contactEntries">
                <h6 class="fw-bold">Anggota 1 <span class="text-danger">*</span></h6>
                <div class="contact-entry mb-4 p-4 border rounded bg-light">
                    <div class="mb-3">
                        <label class="form-label" for="nama-1">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" id="nama-1" name="nama" class="nama form-control"
                            placeholder="Masukkan nama lengkap" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="telepon-1">Nomor Telepon <span
                                class="text-danger">*</span></label>
                        <input type="tel" id="telepon-1" name="telepon" class="telepon form-control"
                            placeholder="Masukkan nomor telepon" required>
                    </div>
                    <!-- Tombol Hapus -->
                    <button type="button" class="btn btn-danger" onclick="hapusDataKontak(this)">
                        <i class="bi bi-trash"></i> Hapus
                    </button>
                </div>
            </div>

            <button type="button" onclick="tambahDataKontak()" class="btn btn-outline-secondary w-100 mb-3">
                <i class="bi bi-person-plus"></i> Tambah Orang
            </button>

            <button type="button" class="Sendiri btn btn-warning w-100 mb-3">
                <i class="bi bi-person-plus"></i> Sendiri
            </button>

            <button type="submit" class="btn btn-info w-100 shadow-sm">
                <i class="bi bi-send"></i> Submit
                Lamaran
            </button>
        </form>

    </div>
</div>

@endsection

@section('content')
<div class="container w-100 hcont gap-1 d-flex my-5 p-3 flex-column justify-content-start align-items-start">
    <div class="w-100 d-flex justify-content-start">
        <p class="clear-p fw-bold">Detil Pekerjaan</p>
    </div>
    <div class="w-100 flex-grow-1 shadow p-2 rounded-4 flex gap-1 justify-content-start align-items-start d-flex flex-md-row flex-column"
        style="min-height: 100%;">
        <div class="d-flex m-3 h-100 gap-5 flex-column justify-content-start align-items-center"
            style="min-height: 100%;">
            <h4 class="clear-p fw-bolder text-truncate w-100 text-center text-wrap" style="color: #2E2D2C;">
                {{{$data_pekerjaan[0]->nama}}}</h4>
            <div class="w-100 flex-grow-1 rounded-2 justify-content-start align-items-center" style="">
                <img class="rounded-2"
                    src="{{ $data_pekerjaan[0]->foto_job==null?auto_asset('Dewa/img/default_jobs.jpg'):auto_asset($data_pekerjaan[0]->foto_job) }}"
                    style="width: 100%; height: 100%; object-fit: cover;" alt="">
            </div>
            <div class="h-25 w-100 gap-2 d-flex flex-column justify-content-start align-items-start">
                <div class="d-flex flex-row gap-2 justify-content-center align-items-center">
                    <i class="bi bi-calendar2-plus"></i>
                    <p class="clear-p" style="font-size: 10px; color: #2E2D2C;">
                        {{{timeAgo($data_pekerjaan[0]['created_at'])}}}</p>
                </div>
                <div class="d-flex flex-row gap-2 justify-content-center align-items-center">
                    <i class="bi bi-pin-map clear-p"></i>
                    @if(session('account'))
                    <p class="clear-p" style="font-size: 12px; color: #2E2D2C;">
                        {{{$data_pekerjaan[0]['alamat']." (".hitungJarak($data_pekerjaan[0]['longitude'],
                        $data_pekerjaan[0]['latitude'],
                        session('account')['longitude'], session('account')['latitude']).")"}}}</p>

                    @else
                    <p class="clear-p" style="font-size: 12px; color: #2E2D2C;">
                        {{{$data_pekerjaan[0]['alamat']}}}
                    </p>
                    @endif
                </div>
                <div class="d-flex flex-row gap-2 justify-content-center align-items-center">
                    <i class="bi bi-coin clear-p"></i>
                    <p class="clear-p" style="font-size: 12px; color: #2E2D2C;">{{{$data_pekerjaan[0]->min_gaji." -
                        ".$data_pekerjaan[0]->max_gaji}}}</p>
                </div>
                <div class="d-flex flex-row gap-2 justify-content-center align-items-center">
                    <i class="bi bi-calendar4-range clear-p"></i>
                    <p class="clear-p" style="font-size: 12px; color: #2E2D2C;">{{{date('d-m-Y',
                        strtotime($data_pekerjaan[0]->start_job))." hingga ".date('d-m-Y',
                        strtotime($data_pekerjaan[0]->end_job))}}}</p>
                </div>
                @if(session()->has('account'))
                @if(session('account')['role']!='mitra')
                @if(now()<$data_pekerjaan[0]->deadline_job && ($history->isEmpty()))
                    <div class="d-flex flex-row gap-2 justify-content-center align-items-center">
                        <p class="clear-p" style="font-size: 12px; color: #2E2D2C;">Pendaftaran pekerjaan ini akan
                            ditutup
                            pada {{{date('d-m-Y H:i:s', strtotime($data_pekerjaan[0]->deadline_job))}}}</p>
                    </div>
                    <button class="btn btn-warning mt-2 w-100" onclick="is_Alone('{{{$data_pekerjaan[0]->id}}}')">Lamar
                        Sekarang</button>
                    @elseif(!$history->isEmpty())
                    <div class="w-100 gap-2 d-flex flex-row justify-content-evenly align-items-center">
                        <button class="btn btn-dark mt-2 w-100" disabled>Anda Sudah Mendaftar</button>
                        <button class="btn btn-info mt-2 w-100"
                            onclick="window.location.href='/Chat/{{{$data_pekerjaan[0]->pembuat}}}'">Chat Pemberi
                            Kerja</button>
                    </div>
                    @else
                    <div class="d-flex flex-row gap-2 justify-content-center align-items-center">
                        <p class="clear-p opacity-50" style="font-size: 12px; color: #2E2D2C;">Pendaftaran pekerjaan ini
                            sudah ditutup
                            pada {{{date('d-m-Y H:i:s', strtotime($data_pekerjaan[0]->deadline_job))}}}</p>
                    </div>
                    @endif


                    @if(!$history->isEmpty())
                    <div class="container my-5">
                        <h4 class="mb-4">History Lamaran Kerja</h4>
                        <div class="row">
                            <div class="col-12 col-md-8 mx-auto">
                                <div class="d-flex flex-column">
                                    <!-- Step 1 -->
                                    @if($history[0]->status=='tunda')
                                    <div class="d-flex align-items-start position-relative pb-4">
                                        <span class="position-relative me-1">
                                            <span class="bg-primary rounded-circle d-inline-block"
                                                style="width:16px;height:16px;"></span>
                                            <!-- Vertical Line -->
                                            <span
                                                class="position-absolute top-100 start-50 translate-middle-x bg-primary"
                                                style="width:2px;height:40px;z-index:0;"></span>
                                        </span>
                                        <div>
                                            <div class="fw-bold">Lamaran Disubmit</div>
                                            <small class="text-muted">Lamaran kamu sudah dikirim ke perusahaan.</small>
                                        </div>
                                    </div>
                                    <!-- Step 2 -->
                                    @elseif($history[0]->status=='ditolak')
                                    <div class="d-flex align-items-start position-relative pb-4">
                                        <span class="position-relative me-1">
                                            <span class="bg-info rounded-circle d-inline-block"
                                                style="width:16px;height:16px;"></span>
                                            <span class="position-absolute top-100 start-50 translate-middle-x bg-info"
                                                style="width:2px;height:40px;z-index:0;"></span>
                                        </span>
                                        <div>
                                            <div class="fw-bold">Diterima</div>
                                            <small class="text-muted">Lamaran Anda Diterima</small>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-start position-relative pb-4">
                                        <span class="position-relative me-1">
                                            <span class="bg-danger rounded-circle d-inline-block"
                                                style="width:16px;height:16px;"></span>
                                        </span>
                                        <div>
                                            <div class="fw-bold">Ditolak</div>
                                            <small class="text-muted">Lamaran Anda Ditolak</small>
                                        </div>
                                    </div>
                                    @elseif($history[0]->status=='interview')
                                    <div class="d-flex align-items-start position-relative pb-4">
                                        <span class="position-relative me-1">
                                            <span class="bg-primary rounded-circle d-inline-block"
                                                style="width:16px;height:16px;"></span>
                                            <!-- Vertical Line -->
                                            <span
                                                class="position-absolute top-100 start-50 translate-middle-x bg-primary"
                                                style="width:2px;height:40px;z-index:0;"></span>
                                        </span>
                                        <div>
                                            <div class="fw-bold">Lamaran Disubmit</div>
                                            <small class="text-muted">Lamaran kamu sudah dikirim ke perusahaan.</small>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-start position-relative pb-4">
                                        <span class="position-relative me-1">
                                            <span class="bg-warning rounded-circle d-inline-block"
                                                style="width:16px;height:16px;"></span>
                                            <span
                                                class="position-absolute top-100 start-50 translate-middle-x bg-warning"
                                                style="width:2px;height:40px;z-index:0;"></span>
                                        </span>
                                        <div>
                                            <div class="fw-bold">Dalam Tahap interview</div>
                                            <small class="text-muted">Lihat Lamaran Anda</small>
                                        </div>
                                    </div>
                                    @elseif($history[0]->status=='Gagal')
                                    <div class="d-flex align-items-start position-relative pb-4">
                                        <span class="position-relative me-1">
                                            <span class="bg-primary rounded-circle d-inline-block"
                                                style="width:16px;height:16px;"></span>
                                            <!-- Vertical Line -->
                                            <span
                                                class="position-absolute top-100 start-50 translate-middle-x bg-primary"
                                                style="width:2px;height:40px;z-index:0;"></span>
                                        </span>
                                        <div>
                                            <div class="fw-bold">Lamaran Disubmit</div>
                                            <small class="text-muted">Lamaran kamu sudah dikirim ke perusahaan.</small>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-start position-relative pb-4">
                                        <span class="position-relative me-1">
                                            <span class="bg-warning rounded-circle d-inline-block"
                                                style="width:16px;height:16px;"></span>
                                            <span
                                                class="position-absolute top-100 start-50 translate-middle-x bg-warning"
                                                style="width:2px;height:40px;z-index:0;"></span>
                                        </span>
                                        <div>
                                            <div class="fw-bold">Dalam Tahap interview</div>
                                            <small class="text-muted">Lihat Lamaran Anda</small>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-start position-relative pb-4">
                                        <span class="position-relative me-1">
                                            <span class="bg-info rounded-circle d-inline-block"
                                                style="width:16px;height:16px;"></span>
                                        </span>
                                        <div>
                                            <div class="fw-bold">Interview Gagal</div>
                                            <small class="text-muted">Lamaran Berhenti Sampai Disini</small>
                                        </div>
                                    </div>
                                    <!-- Step 3 -->
                                    @elseif($history[0]->status=='Menunggu Pekerjaan')
                                    <div class="d-flex align-items-start position-relative pb-4">
                                        <span class="position-relative me-1">
                                            <span class="bg-primary rounded-circle d-inline-block"
                                                style="width:16px;height:16px;"></span>
                                            <!-- Vertical Line -->
                                            <span
                                                class="position-absolute top-100 start-50 translate-middle-x bg-primary"
                                                style="width:2px;height:40px;z-index:0;"></span>
                                        </span>
                                        <div>
                                            <div class="fw-bold">Lamaran Disubmit</div>
                                            <small class="text-muted">Lamaran kamu sudah dikirim ke perusahaan.</small>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-start position-relative pb-4">
                                        <span class="position-relative me-1">
                                            <span class="bg-warning rounded-circle d-inline-block"
                                                style="width:16px;height:16px;"></span>
                                            <span
                                                class="position-absolute top-100 start-50 translate-middle-x bg-warning"
                                                style="width:2px;height:40px;z-index:0;"></span>
                                        </span>
                                        <div>
                                            <div class="fw-bold">Dalam Tahap interview</div>
                                            <small class="text-muted">Lihat Lamaran Anda</small>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-start position-relative pb-4">
                                        <span class="position-relative me-1">
                                            <span class="bg-info rounded-circle d-inline-block"
                                                style="width:16px;height:16px;"></span>
                                            <span class="position-absolute top-100 start-50 translate-middle-x bg-info"
                                                style="width:2px;height:40px;z-index:0;"></span>
                                        </span>
                                        <div>
                                            <div class="fw-bold">Diterima</div>
                                            <small class="text-muted">Lamaran Anda Diterima</small>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-start position-relative pb-4">
                                        <span class="position-relative me-1">
                                            <span class="bg-info rounded-circle d-inline-block"
                                                style="width:16px;height:16px;"></span>
                                            <span class="position-absolute top-100 start-50 translate-middle-x bg-info"
                                                style="width:2px;height:40px;z-index:0;"></span>
                                        </span>
                                        <div>
                                            <div class="fw-bold">Menunggu Bekerja</div>
                                            <div class="d-flex flex-column gap-0">
                                                <small class="text-muted">{{{$data_pekerjaan[0]->start_job}}} Mulai
                                                    Bekerja</small>
                                                <a class="text-muted" style="font-size: 10px;"
                                                    href="https://maps.google.com/?q={{{$data_pekerjaan[0]->latitude}}},{{{$data_pekerjaan[0]->longitude}}}">klik
                                                    untuk ke lokasi pekerjaan</a>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Step 4 -->

                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @elseif(session('account')['role']=='mitra')
                    <button class="btn btn-warning mt-5 w-100"
                        onclick="window.location.href='/daftar-Pelamar/{{{$data_pekerjaan[0]->id}}}'">Daftar
                        Pelamar</button>
                    @endif
                    @else
                    <button class="btn btn-info mt-2 w-100" onclick="window.location.href='/Login'">Login Untuk
                        Mendaftar</button>
                    @endif





            </div>
        </div>
        <div class="vr d-none d-md-flex"></div>
        <div class="m-3 p-3 h-100 d-flex justify-content-between Deskripsi_area d-flex flex-column gap-5">
            <div class="h-75">
                <h3 class="fw-bold">DESKRIPSI</h3>
                <hr>
                {!! $data_pekerjaan[0]->deskripsi !!}
            </div>

            <div class="">
                <h6 class="fw-bold">Tagg</h6>
                <div class="d-flex flex-wrap gap-2">
                    @foreach($data_pekerjaan[0]['kriteria'] as $kriteria) <span
                        class="badge w-auto text-wrap text-start fs-6 bg-prim-me">
                        {{{$kriteria}}}</span>
                    @endforeach
                </div>
            </div>
        </div>

    </div>

</div>
@endsection

@section('script')
<script>
    function hapusDataKontak(button) {
        const entry = button.closest('.contactEntries');
        entry.remove();
    }
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    function is_Alone(idPekerjaan) {
        Swal.fire({
            title: "Dengan siapa anda akan bekerja",
            text: "Anda bisa bekerja bersama teman atau kenalan anda",
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Sendiri",
            cancelButtonText: "Dengan Tim"
        }).then((result) => {
            if (result.isConfirmed) {
                ValidasiPendaftaran(idPekerjaan, false);
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                show_form_is_alone(idPekerjaan);
            }
        });
    }

    function show_form_is_alone(idPekerjaan) {
        var offcanvasElement = document.getElementById('form_is_alone');
        var offcanvas = new bootstrap.Offcanvas(offcanvasElement);
        offcanvas.show();
        let button_alone = offcanvasElement.querySelector('.Sendiri')
        button_alone.setAttribute('onclick', `ValidasiPendaftaran("${idPekerjaan}", false)`);
    }


    async function ValidasiPendaftaran(idPekerjaan, is_team) {
        is_alone = "";
        if (is_team) {
            is_alone = Prepared_data_team();
            console.log(is_alone);
        }
        let route = '/Lamar/' + idPekerjaan;
        // console.log('route: ', route);
        Swal.fire({
            title: "Apakah anda yakin?",
            text: "Pemberi kerja akan meninjau data preferensi usermu, klik Ya jika yakin!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, Daftar!"
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('/Lamar/' + idPekerjaan, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        // tambahkan header lain jika perlu, misal token, dll
                    }, body: JSON.stringify({
                        _token: csrfToken,
                        team: is_team,
                        data: is_alone,
                    }),
                    credentials: 'include'
                }).then(response => response.json())
                    .then(data => {
                        console.log('Response dari server:', data);
                        if (data.success == true) {
                            Swal.fire({
                                title: "Berhasil Mendaftar!",
                                text: "Biasanya butuh 1 - 2 minggu untuk HRD memproses lamaranmu, semoga dilancarkan!.",
                                icon: "success"
                            }).then((result) => {
                                location.reload();
                            });
                        }
                        else {
                            fail('Gagal Mendaftar', data.success)
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });

            }
        });
    }

    let jumlahKontak = 1;
    const maxKontak = 4;

    function Prepared_data_team() {
        // let Dataform;
        // const formData = new FormData();
        let form = document.querySelector('#team_data')
        let data = [];
        form.querySelectorAll('.contact-entry').forEach(item => {
            console.log('ini: ', item)
            let the_data = {
                nama: item.querySelector('.nama').value,
                telepon: item.querySelector('.telepon').value,
            }
            data.push(the_data);
        })
        // console.log(data);
        // data.push(the_data)
        // formData.append('data', JSON.stringify(data));
        return data;
    }

    function tambahDataKontak() {
        if (jumlahKontak >= maxKontak) {
            alert("Maksimal 4 data kontak yang diperbolehkan.");
            return;
        }

        jumlahKontak++;

        const container = document.getElementById("contactEntries");
        container.classList.add('contactEntries')

        // Elemen heading (Anggota X)
        const heading = document.createElement("h6");
        heading.className = "fw-bold";
        heading.innerHTML = `Anggota ${jumlahKontak} <span class="text-danger">*</span>`;

        // Elemen form (contact-entry)
        const divWrapper = document.createElement("div");
        divWrapper.className = "contact-entry mb-4 p-4 border rounded bg-light";

        divWrapper.innerHTML = `
        <div class="mb-3">
            <label class="form-label" for="nama-${jumlahKontak}" >Nama Lengkap <span class="text-danger">*</span></label>
            <input type="text" id="nama-${jumlahKontak}" name="nama[]" class="nama form-control" placeholder="Masukkan nama lengkap" required>
        </div>
        <div class="mb-3">
            <label class="form-label" for="telepon-${jumlahKontak}">Nomor Telepon <span class="text-danger">*</span></label>
            <input type="tel" id="telepon-${jumlahKontak}" name="telepon[]" class="telepon form-control" placeholder="Masukkan nomor telepon" required>
        </div>
        <button type="button" class="btn btn-danger" onclick="hapusDataKontak(this)">
            <i class="bi bi-trash"></i> Hapus
        </button>
    `;
        // Masukkan heading dan form ke dalam container
        container.appendChild(heading);
        container.appendChild(divWrapper);
    }

    function hapusDataKontak(button) {
        const entry = button.closest(".contact-entry");
        const heading = entry.previousElementSibling;

        if (document.querySelectorAll(".contact-entry").length <= 1) {
            alert("Minimal harus ada satu kontak.");
            return;
        }

        entry.remove();
        if (heading && heading.tagName === "H6") heading.remove();
        jumlahKontak--;
    }
    function submitForm() {
        // Validasi dan proses data di sini
        const form = document.getElementById('rejectForm');
        console.log(new FormData(form));
        // Tambahkan logika submit jika diperlukan
        alert("Form dikirim (simulasi).");
    }
</script>
@endsection

@php
function hitungJarak($lon1, $lat1, $lon2, $lat2)
{
$earthRadius = 6371; // kilometer

$dLat = deg2rad($lat2 - $lat1);
$dLon = deg2rad($lon2 - $lon1);

$a = sin($dLat / 2) * sin($dLat / 2) +
cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
sin($dLon / 2) * sin($dLon / 2);

$c = 2 * atan2(sqrt($a), sqrt(1 - $a));

$distance = $earthRadius * $c;

return number_format($distance, 2) . ' km';
}

function timeAgo($datetime) {
$now = new DateTime();
$past = new DateTime($datetime);
$diff = $now->diff($past);

if ($diff->y > 0) {
return $diff->y . ' tahun yang lalu';
} elseif ($diff->m > 0) {
return $diff->m . ' bulan yang lalu';
} elseif ($diff->d > 0) {
return $diff->d . ' hari yang lalu';
} elseif ($diff->h > 0) {
return $diff->h . ' jam yang lalu';
} elseif ($diff->i > 0) {
return $diff->i . ' menit yang lalu';
} elseif ($diff->s > 0) {
return $diff->s . ' detik yang lalu';
} else {
return 'Baru saja';
}
}



@endphp