<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Selamat! Pemberitahuan Diterima Bekerja</title>
</head>

<body style="font-family: Arial, sans-serif; background: #f7f7f7; padding: 30px;">
    <div
        style="max-width: 600px; margin: auto; background: #fff; border-radius: 8px; box-shadow: 0 0 8px #eee; padding: 32px;">
        <h2 style="color: #43a047;">Selamat, Anda Diterima Bekerja!</h2>
        <p>Halo, <b>{{ $data[1]->nama }}</b>,</p>
        <p>
            Selamat! Anda telah resmi diterima sebagai <b>{{ $data[2]['nama'] }}</b> di perusahaan kami.<br>
            Kami sangat senang menyambut Anda sebagai bagian dari tim.
        </p>

        <h3 style="margin-bottom: 5px;">Informasi Mulai Bekerja:</h3>
        <ul>
            <li><b>Tanggal Mulai:</b> {{ $data[2]['start_job'] }} hingga {{ $data[2]['end_job'] }}</li>
            <li><b>Lokasi / Platform:</b> {{ $data[2]['alamat'] }}</li>
        </ul>

        <h3 style="margin-bottom: 5px;">Dokumen & Barang yang Perlu Dibawa:</h3>
        <ul>
            <li>KTP & fotokopi</li>
            <li>CV terbaru (hardcopy & softcopy)</li>
            <li>Ijazah & transkrip nilai</li>
            <li>Alat tulis pribadi</li>
            <li>Pakaian formal/rapi</li>
            <li>Pas foto 3x4 (2 lembar)</li>
        </ul>

        <p>
            Jika ada pertanyaan lebih lanjut, silakan hubungi {{$data[3]['nama']}} HRD.<br>
            Sampai jumpa di hari pertama kerja!
        </p>
        <br>
        <p>Hormat kami,<br>
            <b>Tim HRD Perusahaan</b>
        </p>
    </div>
</body>

</html>