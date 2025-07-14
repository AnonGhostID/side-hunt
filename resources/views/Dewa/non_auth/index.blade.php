@extends('Dewa.Base.Basic-page')

@section('css')
<link rel="stylesheet" href="{{auto_asset('Dewa/css/index.css') }}">
@endsection

@section('peta')
#map{
height: 800px;
}
@endsection

@section('add-onn')
<!-- Tambahkan Bootstrap & Bootstrap Icons CDN di head kalau belum ada -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">



@endsection

@section('content')
<div class="hero-section mb-5">
    <div class="container">
        <div class="row px-3 py-5">
            <div class="col-lg-6">
                <h1 class="container m-md-flex hero-heading mb-3">Temukan pekerjaan yang kamu inginkan!</h1>
                <div class="container-button d-grid gap-5  d-md-flex justify-content-md-start">
                    <button type="submit" onclick="click_Button('/kerja/')" class="btn btn-warning">Cari
                        Pekerjaan</button>
                    <!-- <button type="submit" class="btn btn-warning">Lokasi Anda</button> -->
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="card">
        <div class="card-header">{{ __('List') }}</div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead class="bg-darker">
                    <tr class="">
                        <th scope="col">Nama</th>
                        <th scope="col">Alamat</th>
                        <th scope="col">Lihat</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($jobs as $job)
                    <tr>
                        <td>{{ $job->nama }}</td>
                        <td>{{ $job->alamat }}</td>
                        <td><a href="/Pekerjaan/{{{$job->id}}}" class="btn btn-sm btn-dark">SHOW</a></td>
                    </tr>
                    @empty
                    <div class="alert alert-danger">
                        Data kerja sampingan tidak ada.
                    </div>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="container mt-5 mb-5 position-relative">
    <!-- Container -->
    <div class="show_container p-3 rounded-4 shadow-sm border position-absolute z-3"
        style="background:#fafbfc; top: 10px; left: 50%; transform: translateX(-50%); display: none; min-width:350px;">

        <!-- Tombol Silang -->
        <button type="button" class="btn-close position-absolute top-0 end-0 m-3" aria-label="Close"
            onclick="this.closest('.show_container').style.display='none'"></button>

        <div class="mb-3">
            <div class="fw-bold fs-5 mb-1 d-flex align-items-center">
                <i class="bi bi-person-badge me-2 text-primary"></i>
                <p class="nama_job clear-p mb-0">UI/UX Designer</p>
            </div>
        </div>
        <div class="mb-3 d-flex align-items-center">
            <i class="bi bi-geo-alt me-2 text-danger"></i>
            <span class="me-3 alamat_job">Jakarta, Indonesia</span>
        </div>
        <div class="mb-3 d-flex align-items-center">
            <i class="bi bi-cash-stack me-2 text-success"></i>
            <span class="salary">Rp 8.000.000 - 12.000.000</span>
        </div>
        <div class="mb-3 row g-2">
            <div class="col-4">
                <div class="small text-muted ">Mulai</div>
                <div class="fw-semibold start-date">
                    <i class="bi bi-calendar-event me-1 "></i>
                </div>
            </div>
            <div class="col-4">
                <div class="small text-muted ">Selesai</div>
                <div class="fw-semibold close-date"><i class="bi bi-calendar-check me-1 "></i></div>
            </div>
            <div class="col-4">
                <div class="small text-muted ">Deadline</div>
                <div class="fw-semibold close-job"><i class="bi bi-hourglass-split me-1 "></i></div>
            </div>
        </div>

        <!-- Tambahkan Tombol "See the Details" di bawah sini -->
        <a class="d-flex justify-content-end mt-3 see-detils" href="">
            <button type="button" class="btn btn-primary see-detils" onclick="">
                See the Details
            </button>
        </a>
    </div>


    <div id="map" class="z-1">
        <link rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/leaflet.locatecontrol/dist/L.Control.Locate.min.css" />
        <script src="https://cdn.jsdelivr.net/npm/leaflet.locatecontrol/dist/L.Control.Locate.min.js"
            charset="utf-8"></script>
        <!-- Load Leaflet from CDN -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>

        <!-- Load Esri Leaflet from CDN -->
        <script src="https://unpkg.com/esri-leaflet@3.0.12/dist/esri-leaflet.js"></script>
        <script src="https://unpkg.com/esri-leaflet-vector@4.2.3/dist/esri-leaflet-vector.js"></script>

        <!-- Load Esri Leaflet Geocoder from CDN -->
        <link rel="stylesheet" href="https://unpkg.com/esri-leaflet-geocoder@3.1.4/dist/esri-leaflet-geocoder.css">
        <script src="https://unpkg.com/esri-leaflet-geocoder@3.1.4/dist/esri-leaflet-geocoder.js"></script>

        <link rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/leaflet.locatecontrol/dist/L.Control.Locate.min.css" />
        <script src="https://cdn.jsdelivr.net/npm/leaflet.locatecontrol/dist/L.Control.Locate.min.js"
            charset="utf-8"></script>
        <script>
            const apiKey =
                "AAPK3e52398025234807add84f416a03c213CPb7ak6zNzwQYIBhQ9PIx-oBY_1mtsbVR1klbU-RrJ6TWtK5mP28C-lfmNqfndnS";

            const basemapEnum = "arcgis/navigation";

            const map = L.map("map", {
                minZoom: 10
            })

            // create control and add to map
            var lc = L.control.locate().addTo(map);

            // request location update and set location
            lc.start();

            map.setView([-2.526, 117.905], 8);

            L.esri.Vector.vectorBasemapLayer(basemapEnum, {
                apiKey: apiKey
            }).addTo(map);


            const searchControl = L.esri.Geocoding.geosearch({
                position: "topright",
                placeholder: "Cari alamat anda",
                useMapBounds: false,

                providers: [
                    L.esri.Geocoding.arcgisOnlineProvider({
                        apikey: apiKey,
                    })
                ]

            }).addTo(map);

            var greenIcon = L.icon({

                iconUrl: "data:image/svg+xml,%3Csvg width='98' height='87' viewBox='0 0 98 87' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M0 28.5H42.5L50.5 87L57.5 28.5H98V0H0V28.5Z' fill='%231B4841'/%3E%3Cpath d='M34.4425 6.72727H37.6342V17.3764C37.6342 18.3608 37.413 19.2159 36.9705 19.9418C36.533 20.6676 35.924 21.2269 35.1435 21.6197C34.3629 22.0124 33.4556 22.2088 32.4215 22.2088C31.5018 22.2088 30.6665 22.0472 29.9158 21.7241C29.1701 21.396 28.5785 20.8988 28.141 20.2326C27.7035 19.5614 27.4872 18.7187 27.4922 17.7045H30.7063C30.7163 18.1072 30.7983 18.4528 30.9524 18.7411C31.1115 19.0245 31.3278 19.2433 31.6012 19.3974C31.8796 19.5465 32.2077 19.6211 32.5856 19.6211C32.9833 19.6211 33.3189 19.5366 33.5923 19.3675C33.8707 19.1935 34.082 18.94 34.2262 18.6069C34.3704 18.2738 34.4425 17.8636 34.4425 17.3764V6.72727ZM54.3388 14.3636C54.3388 16.0291 54.0231 17.446 53.3917 18.6143C52.7653 19.7827 51.9102 20.6751 50.8263 21.2915C49.7475 21.9031 48.5344 22.2088 47.1871 22.2088C45.8299 22.2088 44.6119 21.9006 43.533 21.2841C42.4542 20.6676 41.6016 19.7752 40.9751 18.6069C40.3487 17.4386 40.0355 16.0241 40.0355 14.3636C40.0355 12.6982 40.3487 11.2812 40.9751 10.1129C41.6016 8.9446 42.4542 8.05469 43.533 7.44318C44.6119 6.8267 45.8299 6.51847 47.1871 6.51847C48.5344 6.51847 49.7475 6.8267 50.8263 7.44318C51.9102 8.05469 52.7653 8.9446 53.3917 10.1129C54.0231 11.2812 54.3388 12.6982 54.3388 14.3636ZM51.065 14.3636C51.065 13.2848 50.9034 12.375 50.5803 11.6342C50.2621 10.8935 49.8121 10.3317 49.2305 9.94886C48.6488 9.56605 47.9677 9.37465 47.1871 9.37465C46.4066 9.37465 45.7255 9.56605 45.1438 9.94886C44.5621 10.3317 44.1097 10.8935 43.7866 11.6342C43.4684 12.375 43.3093 13.2848 43.3093 14.3636C43.3093 15.4425 43.4684 16.3523 43.7866 17.093C44.1097 17.8338 44.5621 18.3956 45.1438 18.7784C45.7255 19.1612 46.4066 19.3526 47.1871 19.3526C47.9677 19.3526 48.6488 19.1612 49.2305 18.7784C49.8121 18.3956 50.2621 17.8338 50.5803 17.093C50.9034 16.3523 51.065 15.4425 51.065 14.3636ZM56.7307 22V6.72727H62.8458C63.9694 6.72727 64.9065 6.89382 65.6572 7.22692C66.4079 7.56001 66.9722 8.02237 67.3501 8.61399C67.7279 9.20064 67.9168 9.87678 67.9168 10.6424C67.9168 11.239 67.7975 11.7635 67.5589 12.2159C67.3202 12.6634 66.9921 13.0312 66.5745 13.3196C66.1618 13.603 65.6895 13.8043 65.1576 13.9237V14.0728C65.7393 14.0977 66.2836 14.2617 66.7908 14.565C67.3028 14.8683 67.718 15.2933 68.0361 15.8402C68.3543 16.3821 68.5134 17.0284 68.5134 17.7791C68.5134 18.5895 68.3121 19.3129 67.9094 19.9492C67.5116 20.5806 66.9225 21.0803 66.142 21.4482C65.3614 21.8161 64.3994 22 63.2559 22H56.7307ZM59.9598 19.3601H62.5922C63.4921 19.3601 64.1483 19.1886 64.561 18.8455C64.9736 18.4975 65.18 18.0352 65.18 17.4585C65.18 17.0359 65.078 16.663 64.8742 16.3398C64.6704 16.0167 64.3795 15.7631 64.0017 15.5792C63.6288 15.3952 63.1839 15.3033 62.6668 15.3033H59.9598V19.3601ZM59.9598 13.1183H62.3536C62.7961 13.1183 63.1888 13.0412 63.5319 12.8871C63.8799 12.728 64.1533 12.5043 64.3522 12.2159C64.556 11.9276 64.6579 11.582 64.6579 11.1793C64.6579 10.6275 64.4616 10.1825 64.0688 9.84446C63.681 9.50639 63.1292 9.33736 62.4133 9.33736H59.9598V13.1183Z' fill='white'/%3E%3C/svg%3E%0A",

                iconSize: [38, 38], // size of the icon
                // shadowSize: [, 64], // size of the shadow
                iconAnchor: [38, 38], // point of the icon which will correspond to marker's location
                // shadowAnchor: [4, 62],  // the same for the shadow
                popupAnchor: [-3, -76] // point from which the popup should open relative to the iconAnchor
            });
            @foreach($jobs as $job)
            L.marker([{{{ $job-> koordinat}}}], { icon: greenIcon }).addTo(map).on('click', function (e) {
                show_job(@json($job));
            });
            @endforeach
        </script>
    </div>
</div>
@endsection

@section('script')
<script>

    function show_job(job_object) {
        let show = document.querySelector('.show_container')
        show.style.display = "";
        show.querySelector('.nama_job').textContent = job_object.nama
        show.querySelector('.alamat_job').textContent = job_object.alamat
        show.querySelector('.salary').textContent = formatRupiah(job_object.min_gaji) + ' - ' + formatRupiah(job_object.max_gaji)
        show.querySelector('.start-date').textContent = formatTanggal(job_object.start_job)
        show.querySelector('.close-date').textContent = formatTanggal(job_object.end_job)
        show.querySelector('.close-job').textContent = formatTanggal(job_object.deadline_job)
        show.querySelector('.see-detils').setAttribute('href', `'/Pekerjaan/${job_object.id}'`);


    }


    function click_Button(link) {
        window.location.href = link;
    }
    function formatTanggal(input) {
        // Ambil hanya bagian tanggal (YYYY-MM-DD)
        let tanggal = input.split(" ")[0];
        let [tahun, bulan, hari] = tanggal.split("-");
        return `${hari}/${bulan}/${tahun}`;
    }


    function formatRupiah(angka) {
        if (typeof angka === "number") angka = angka.toString();
        // Hilangkan karakter selain angka
        angka = angka.replace(/[^,\d]/g, "");
        let split = angka.split(",");
        let sisa = split[0].length % 3;
        let rupiah = split[0].substr(0, sisa);
        let ribuan = split[0].substr(sisa).match(/\d{3}/g);

        if (ribuan) {
            let separator = sisa ? "." : "";
            rupiah += separator + ribuan.join(".");
        }
        rupiah = split[1] !== undefined ? rupiah + "," + split[1] : rupiah;
        return "Rp. " + rupiah;
    }

    @if (session('alert'))
        let split = "{{session('alert')}}".split('|');
    NotifyAlert(split[0], split[1], 0);
    @endif

    function NotifyAlert(titled, message, type = null) {
        let img = null;
        if (type == null) {
            type == 0 ? img = "{{ asset('img/success.svg')}}" : img = "{{ asset('img/failed.svg')}}";
        }
        Swal.fire({
            title: titled,
            text: msg,
            imageUrl: img,
            imageWidth: 100,
            imageHeight: 100,
            imageAlt: "Custom image",
            width: 300,
            height: 400,
        });
    }
</script>
@endsection