@extends('layouts.portal')

@section('title', 'Portal Pekerjaan')
@section('page-title', 'Temukan pekerjaan yang kamu inginkan!')
@section('page-subtitle')
    Jelajahi lowongan terbaru, rekomendasi personal, dan lihat peta peluang di sekitar Anda.
@endsection

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet.locatecontrol/dist/L.Control.Locate.min.css" />
    <link rel="stylesheet" href="https://unpkg.com/esri-leaflet-geocoder@3.1.4/dist/esri-leaflet-geocoder.css">
    <style>
        #map { height: 24rem; border-radius: 1rem; }
    </style>
@endpush

@php
if (!function_exists('portal_distance')) {
    function portal_distance($lon1, $lat1, $lon2 = null, $lat2 = null)
    {
        if (!$lon1 || !$lat1 || !$lon2 || !$lat2) {
            return 'Lokasi belum lengkap';
        }

        $earthRadius = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) ** 2 +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) ** 2;

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earthRadius * $c;

        return number_format($distance, 2) . ' km';
    }
}
@endphp

@section('content')
<div class="container mx-auto px-4 py-6 space-y-8">
    <section class="grid gap-6 lg:grid-cols-12">
        <div class="lg:col-span-5">
            <div class="rounded-2xl bg-gradient-to-r from-blue-600 via-blue-500 to-indigo-500 p-8 text-white shadow-xl">
                <h2 class="text-3xl font-semibold leading-tight">Temukan pekerjaan yang kamu inginkan!</h2>
                <p class="mt-4 text-sm text-blue-100">
                    Gunakan rekomendasi dan penelusuran untuk menemukan pekerjaan yang cocok dengan lokasi dan minatmu.
                </p>
                <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                    <button type="button" onclick="window.location.href='/cari'"
                            class="inline-flex items-center justify-center rounded-lg bg-white px-4 py-2 text-sm font-semibold text-blue-700 shadow-sm hover:bg-blue-50">
                        <i class="fas fa-search mr-2"></i>Cari Pekerjaan
                    </button>
                    <button type="button" id="locate-me"
                            class="inline-flex items-center justify-center rounded-lg border border-white/30 px-4 py-2 text-sm font-semibold text-white hover:bg-white/10">
                        <i class="fas fa-location-crosshairs mr-2"></i>Gunakan Lokasi Saya
                    </button>
                </div>
            </div>
        </div>
        <div class="lg:col-span-7">
            <div class="rounded-2xl border border-gray-200 bg-white shadow-sm">
                <header class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Lowongan Terbaru</h3>
                        <p class="text-sm text-gray-500">Lihat sejumlah pekerjaan terbaru yang sesuai.</p>
                    </div>
                </header>
                <div class="max-h-80 overflow-y-auto">
                    <table class="min-w-full divide-y divide-gray-100 text-left">
                        <thead class="bg-gray-50 sticky top-0 z-10">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">Nama</th>
                                <th scope="col" class="px-6 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">Alamat</th>
                                <th scope="col" class="px-6 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($jobs as $job)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-800">{{ $job->nama }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $job->alamat }}</td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('pekerjaan.show', $job->id) }}" class="inline-flex items-center rounded-lg bg-blue-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-blue-700">
                                            <i class="fas fa-eye mr-2"></i>Lihat
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-10 text-center text-sm text-gray-500">
                                        <i class="fas fa-briefcase mb-2 text-2xl text-gray-300"></i>
                                        <p>Belum ada pekerjaan yang tersedia saat ini.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <section class="grid gap-6 lg:grid-cols-12">
        <div class="lg:col-span-5 space-y-4">
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-900">Rekomendasi Pekerjaan</h3>
                <p class="mt-1 text-sm text-gray-500">Pilihan terbaru yang kami rasa cocok untukmu.</p>
                @php
                    // Safeguard undefined variables for user location
                    $accountLongitude = $accountLongitude ?? (session('account')->longitude ?? null);
                    $accountLatitude = $accountLatitude ?? (session('account')->latitude ?? null);
                    // Build quick lookup of jobs keyed by id for recommendations
                    $jobsById = ($all instanceof \Illuminate\Support\Collection)
                        ? $all->keyBy('id')
                        : collect($all)->keyBy('id');
                @endphp
                <div class="mt-5 space-y-4">
                    @forelse($match as $jobId => $score)
                        @php $job = $jobsById->get($jobId); @endphp
                        @if(!$job)
                            @continue
                        @endif
                        <a href="{{ route('pekerjaan.show', $job->id) }}" class="flex items-center justify-between rounded-xl border border-gray-100 bg-gray-50 px-4 py-3 transition hover:border-blue-200 hover:bg-blue-50">
                            <div>
                                <p class="text-sm font-semibold text-gray-800">{{ $job->nama }}</p>
                                <p class="text-xs text-gray-500">{{ portal_distance($job->longitude, $job->latitude, $accountLongitude, $accountLatitude) }}</p>
                            </div>
                            <div class="flex items-center gap-2 text-xs font-medium text-blue-600">
                                <span>Detail</span>
                                <i class="fas fa-arrow-up-right-from-square"></i>
                            </div>
                        </a>
                    @empty
                        <div class="rounded-lg border border-dashed border-gray-200 bg-white px-4 py-6 text-center text-sm text-gray-500">
                            Belum ada rekomendasi tersedia. Lengkapi profil untuk mendapatkan saran yang lebih akurat.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="lg:col-span-7">
            <div class="rounded-2xl border border-gray-200 bg-white shadow-sm">
                <header class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Peta Peluang</h3>
                        <p class="text-sm text-gray-500">Gunakan pencarian lokasi untuk menemukan pekerjaan di sekitar.</p>
                    </div>
                    <button id="reset-map" class="inline-flex items-center rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-medium text-gray-600 hover:border-blue-300 hover:text-blue-600 hover:bg-blue-50">
                        <i class="fas fa-rotate mr-2"></i>Reset View
                    </button>
                </header>
                <div class="px-6 py-6">
                    <div id="map" class="w-full rounded-xl border border-gray-100"></div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@php
    $mapJobs = $peta->map(function ($item) {
        return [
            'id' => $item->id,
            'nama' => $item->nama,
            'alamat' => $item->alamat,
            'lat' => $item->latitude,
            'lng' => $item->longitude,
        ];
    })->values();
@endphp

@section('script')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
    <script src="https://unpkg.com/esri-leaflet@3.0.12/dist/esri-leaflet.js"></script>
    <script src="https://unpkg.com/esri-leaflet-vector@4.2.3/dist/esri-leaflet-vector.js"></script>
    <script src="https://unpkg.com/esri-leaflet-geocoder@3.1.4/dist/esri-leaflet-geocoder.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/leaflet.locatecontrol/dist/L.Control.Locate.min.js" charset="utf-8"></script>
    <script>
        const apiKey = 'AAPK3e52398025234807add84f416a03c213CPb7ak6zNzwQYIBhQ9PIx-oBY_1mtsbVR1klbU-RrJ6TWtK5mP28C-lfmNqfndnS';
        const defaultCenter = [-2.526, 117.905];
        const map = L.map('map', { minZoom: 2 }).setView(defaultCenter, 5);

        L.esri.Vector.vectorBasemapLayer('arcgis/navigation', { apiKey }).addTo(map);
        const locateControl = L.control.locate({ position: 'topright', strings: { title: 'Gunakan lokasi saya' } }).addTo(map);
        const resultsLayer = L.layerGroup().addTo(map);
        // Fix: ensure proper sizing after initial render to avoid half-gray tiles
        (function ensureMapSizing() {
            const doInvalidate = () => map.invalidateSize({ animate: false });

            // Next frame and short delay in case of late layout/fonts
            requestAnimationFrame(doInvalidate);
            setTimeout(doInvalidate, 150);

            // On window lifecycle events
            window.addEventListener('load', doInvalidate, { once: true });
            window.addEventListener('orientationchange', doInvalidate);
            window.addEventListener('resize', doInvalidate);

            // Observe container sizing and run once when non-zero
            const el = document.getElementById('map');
            if (el && 'ResizeObserver' in window) {
                let done = false;
                const ro = new ResizeObserver((entries) => {
                    const r = entries[0]?.contentRect;
                    if (!done && r && r.width > 0 && r.height > 0) {
                        doInvalidate();
                        done = true;
                        ro.disconnect();
                    }
                });
                ro.observe(el);
            }

            // If rendered offscreen/hidden first, re-invalidate when it becomes visible
            if ('IntersectionObserver' in window) {
                const el2 = document.getElementById('map');
                let fired = false;
                const io = new IntersectionObserver((entries) => {
                    if (!fired && entries.some((e) => e.isIntersecting)) {
                        doInvalidate();
                        fired = true;
                        io.disconnect();
                    }
                }, { threshold: 0.1 });
                el2 && io.observe(el2);
            }
        })();

        const jobs = @json($mapJobs);

        const markers = [];
        jobs.forEach((job) => {
            if (!job.lat || !job.lng) {
                return;
            }
            const marker = L.marker([job.lat, job.lng]).addTo(map);
            marker.bindPopup(`
                <div class="space-y-2">
                    <p class="text-sm font-semibold text-gray-900">${job.nama}</p>
                    <p class="text-xs text-gray-500">${job.alamat ?? 'Alamat tidak tersedia'}</p>
                    <a href="/kerja/${job.id}" class="inline-flex items-center rounded-lg bg-blue-600 px-3 py-1.5 text-xs font-semibold text-white">Detail</a>
                </div>
            `);
            markers.push(marker);
        });

        const searchControl = L.esri.Geocoding.geosearch({
            placeholder: 'Cari alamat...',
            position: 'topleft',
            useMapBounds: false,
            providers: [L.esri.Geocoding.arcgisOnlineProvider({ apikey: apiKey })]
        }).addTo(map);

        searchControl.on('results', (data) => {
            resultsLayer.clearLayers();
            if (!data.results.length) {
                return;
            }
            const { latlng, properties } = data.results[0];
            const marker = L.marker(latlng).addTo(resultsLayer).bindPopup(properties.LongLabel);
            marker.openPopup();
            map.flyTo(latlng, 14);
        });

        document.getElementById('locate-me')?.addEventListener('click', () => locateControl.start());
        document.getElementById('reset-map')?.addEventListener('click', () => {
            map.closePopup();
            map.setView(defaultCenter, 5);
        });

        map.on('locationerror', () => {
            alert('Tidak dapat mengambil lokasi Anda. Pastikan izin lokasi diberikan.');
        });
    </script>
@endsection


