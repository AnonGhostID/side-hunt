<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Pemberitahuan Hasil Seleksi Kerja</title>
</head>

<body style="font-family: Arial, sans-serif; background: #f7f7f7; padding: 30px;">
    <div
        style="max-width: 600px; margin: auto; background: #fff; border-radius: 8px; box-shadow: 0 0 8px #eee; padding: 32px;">
        <h2 style="color: #e53935;">Terima Kasih atas Partisipasi Anda</h2>
        <p>Halo, <b>{{ $data[1]->nama }}</b>,</p>
        <p>
            Kami mengucapkan terima kasih atas kepercayaan dan antusiasme Anda dalam melamar posisi <b>{{ $data[2]['nama'] }}</b> di perusahaan kami.
        </p>
        <p>
            Setelah melalui proses seleksi yang cukup ketat, dengan berat hati kami sampaikan bahwa Anda belum dapat kami lanjutkan ke tahap berikutnya atau diterima bekerja pada posisi tersebut.
        </p>
        <p>
            Keputusan ini tentu tidak mudah, mengingat banyak kandidat dengan kompetensi dan semangat yang sangat baik, termasuk Anda.
        </p>
        <h3 style="margin-bottom: 5px;">Tetap Semangat!</h3>
        <p>
            Kami sangat mengapresiasi waktu, tenaga, serta usaha yang telah Anda curahkan dalam proses rekrutmen ini.<br>
            Jangan berkecil hati—semangat belajar dan berkembang yang Anda miliki adalah modal berharga untuk sukses di masa depan.
        </p>
        <p>
            Kami akan menyimpan data Anda di database kami, dan tidak menutup kemungkinan akan menghubungi Anda kembali jika ada peluang yang sesuai di waktu mendatang.<br>
            Silakan terus pantau informasi lowongan kerja terbaru dari kami di website resmi ataupun media sosial perusahaan.
        </p>
        <br>
        <p>Terima kasih atas minat dan partisipasi Anda.<br>
        Kami doakan Anda sukses di kesempatan berikutnya!</p>
        <br>
        <p>Hormat kami,<br>
            <b>Tim HRD Perusahaan</b>
        </p>
    </div>
</body>

</html>
