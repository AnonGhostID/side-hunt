<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Pemberitahuan Interview</title>
</head>

<body style="font-family: Arial, sans-serif; background: #f7f7f7; padding: 30px;">
    <div
        style="max-width: 600px; margin: auto; background: #fff; border-radius: 8px; box-shadow: 0 0 8px #eee; padding: 32px;">
        <h2 style="color: #1e88e5;">Selamat, Lamaran Anda Lolos ke Tahap Selanjutnya!</h2>
        <p>Halo, <b>{{ $data[1]->nama }}</b>,</p>
        <p>
            Terima kasih telah melamar</b> sebagai <b>{{ $data[2]['nama'] }}</b>.<br>
            Kami dengan senang hati menginformasikan bahwa Anda telah lolos ke tahap selanjutnya dan diundang untuk
            mengikuti interview.
        </p>

        <h3 style="margin-bottom: 5px;">Jadwal Interview:</h3>
        <ul>
            <li><b>Tanggal:</b> {{ \Carbon\Carbon::parse($data[0]['jadwal_interview'])->translatedFormat('l, d F Y H:i')}}</li>
            <li><b>Platform:</b> Online (Google Meet)</li>
        </ul>

        <h3 style="margin-bottom: 5px;">Link Interview:</h3>
        <p>
            <a href="{{ $data[0]['link_Interview'] }}" style="color: #1e88e5;">{{ $data[0]['link_Interview'] }}</a>
        </p>

        <h3 style="margin-bottom: 5px;">Yang Perlu Dipersiapkan:</h3>
        <ul>
            <li>Koneksi internet yang stabil</li>
            <li>Perangkat dengan kamera dan mikrofon</li>
            <li>CV terbaru (softcopy)</li>
            <li>Alat tulis dan catatan (opsional)</li>
            <li>Pakaian formal/rapi</li>
            <li>Wajib Open Camera</li>
            <li>Pastikan Microphone anda bekerja dengan baik</li>
            @if (!empty($data['tambahan']))
            @foreach ($data['tambahan'] as $item)
            <li>{{ $item }}</li>
            @endforeach
            @endif
        </ul>

        <p>
            Tolong jangan balas pesan ini, Jika ada pertanyaan, Silahkan Lakukan Chat pada {{$data[3]['nama']}}<br>
            Sampai jumpa di interview!
        </p>
        <br>
        <p>Hormat kami,<br>
            <b>Tim HRD Perusahaan</b>
        </p>
    </div>
</body>

</html>