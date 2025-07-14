<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reset Password Akun Anda</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
<body style="margin:0;padding:0;background:#f2f4f8;">
    <div style="background:#f2f4f8;min-height:100vh;padding:40px 0;">
        <div style="
            max-width: 480px;
            margin: 0 auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 6px 32px 0 rgba(0,0,0,0.06);
            padding: 32px 32px 12px 32px;
            text-align: center;
        ">
            <h1 style="margin-top:0;">Side-Hunt</h1>
            <h2 style="margin:0 0 8px 0;font-size:22px;color:#293042;">Permintaan Ganti Password</h2>
            <p style="margin:0 0 24px 0;font-size:15px;color:#4b5563;">
                Hai,<br>
                Kami menerima permintaan untuk mengganti password akun Anda.<br>
                Silakan klik tombol di bawah ini untuk melanjutkan proses penggantian password:
            </p>
            <a href="{{ $url.'/reset_password/'.$code.'/'.$email}}"
               style="display:inline-block;margin:12px 0 28px 0;padding:14px 36px;background:#4f8ef7;color:#fff;font-weight:600;text-decoration:none;font-size:16px;border-radius:7px;box-shadow:0 1px 6px 0 rgba(79,142,247,0.1);letter-spacing:0.5px;">
                Reset Password Sekarang
            </a>
            <p style="margin:32px 0 0 0;font-size:13px;color:#9ca3af;">
                Link ini akan kedaluwarsa dalam 1 jam.<br>
                Jika Anda tidak pernah meminta penggantian password, silakan abaikan email ini. Akun Anda tetap aman.
            </p>
            <p style="font-size:12px;color:#b0b6be;margin:32px 0 0 0;">&copy; {{ date('Y') }} Side-Hunt. All rights reserved.</p>
        </div>
        <div style="text-align:center;color:#b0b6be;font-size:12px;margin-top:16px;">
            Email ini dikirim otomatis oleh sistem. Mohon tidak membalas email ini.
        </div>
    </div>
</body>
</html>
