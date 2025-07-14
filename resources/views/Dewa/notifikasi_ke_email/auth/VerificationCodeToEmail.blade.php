<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex justify-content-center align-items-center" style="height: 100vh;">
    <div class="card shadow p-4" style="max-width: 500px; width: 100%;">
        <h2 class="text-center mb-3 text-primary">Verification Code</h2>
        <p class="text-center fs-5">Your Verification code is: 
            <strong class="text-danger">{{$code}}</strong>
        </p>
        <hr>
        <p class="text-center">Silakan akses tombol berikut untuk verifikasi email:</p>
        <div class="text-center">
            <a href="{{ $server_url }}/Verify-Email" class="btn btn-warning">Verifikasi Email</a>
        </div>
        <p>Atau silahkan akses link berikut jika tombol bermasalah: {{ $server_url }}/Verify-Email</p>
    </div>

    <!-- Bootstrap JS (optional, for interactive components) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
