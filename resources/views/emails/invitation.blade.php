<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Undangan Wisuda</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, Helvetica, sans-serif; background: #f1f5f9; color: #334155; }
        .wrapper { max-width: 600px; margin: 32px auto; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
        .header { background: linear-gradient(135deg, #1e3a5f 0%, #2563eb 100%); padding: 40px 32px; text-align: center; }
        .header h1 { color: #fff; font-size: 22px; font-weight: 700; margin-bottom: 4px; }
        .header p { color: rgba(255,255,255,0.7); font-size: 13px; }
        .body { padding: 32px; }
        .greeting { font-size: 15px; color: #475569; margin-bottom: 20px; line-height: 1.6; }
        .info-card { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; margin: 20px 0; }
        .info-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #e2e8f0; }
        .info-row:last-child { border-bottom: none; }
        .info-label { color: #64748b; font-size: 13px; }
        .info-value { color: #1e293b; font-size: 13px; font-weight: 600; }
        .qr-section { text-align: center; padding: 24px; background: #f8fafc; border-radius: 8px; margin: 20px 0; }
        .qr-section p { font-size: 13px; color: #64748b; margin-bottom: 16px; }
        .qr-code { display: inline-block; padding: 12px; background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; }
        .qr-code svg { display: block; }
        .token { font-family: 'Courier New', monospace; font-size: 11px; color: #94a3b8; margin-top: 8px; word-break: break-all; }
        .instruction { background: #eff6ff; border-left: 4px solid #3b82f6; padding: 16px; border-radius: 0 8px 8px 0; margin: 20px 0; }
        .instruction h4 { color: #1d4ed8; font-size: 13px; font-weight: 700; margin-bottom: 8px; }
        .instruction ol { padding-left: 16px; }
        .instruction li { font-size: 13px; color: #3730a3; margin-bottom: 6px; line-height: 1.5; }
        .footer { background: #f8fafc; padding: 24px 32px; border-top: 1px solid #e2e8f0; text-align: center; }
        .footer p { font-size: 11px; color: #94a3b8; line-height: 1.6; }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="header">
        <h1>Undangan Wisuda</h1>
        <p>QR Code Kehadiran Resmi</p>
    </div>

    <div class="body">
        <p class="greeting">
            Kepada Yth. <strong>{{ $participant->name }}</strong>,<br><br>
            Dengan hormat, kami mengundang Anda untuk hadir dalam acara <strong>Wisuda</strong>.
            Berikut adalah QR Code kehadiran resmi Anda yang wajib dibawa pada hari pelaksanaan.
        </p>

        <div class="info-card">
            <div class="info-row">
                <span class="info-label">Nama Lengkap</span>
                <span class="info-value">{{ $participant->name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">NRP / NIM</span>
                <span class="info-value">{{ $participant->nrp }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Kategori</span>
                <span class="info-value">{{ $participant->category }}</span>
            </div>
            @if($participant->faculty)
            <div class="info-row">
                <span class="info-label">Fakultas</span>
                <span class="info-value">{{ $participant->faculty }}</span>
            </div>
            @endif
            @if($participant->program_study)
            <div class="info-row">
                <span class="info-label">Program Studi</span>
                <span class="info-value">{{ $participant->program_study }}</span>
            </div>
            @endif
        </div>

        <div class="qr-section">
            <p><strong>QR Code Kehadiran Anda</strong><br>Tunjukkan kode ini kepada petugas registrasi</p>
            <div class="qr-code">
                {!! $qrSvg !!}
            </div>
            <p class="token">Token: {{ $participant->qr_token }}</p>
        </div>

        <div class="instruction">
            <h4>Petunjuk Penggunaan</h4>
            <ol>
                <li>Simpan email ini atau screenshot QR Code di atas.</li>
                <li>Tunjukkan QR Code kepada petugas scanner saat tiba di lokasi.</li>
                <li>Petugas akan melakukan scan QR Code Anda.</li>
                <li>Pastikan QR Code terlihat jelas dan tidak buram saat di-scan.</li>
                <li>QR Code ini hanya dapat digunakan sekali.</li>
            </ol>
        </div>
    </div>

    <div class="footer">
        <p>
            Email ini dikirim secara otomatis oleh Sistem Absensi Wisuda Hybrid.<br>
            Jangan balas email ini. Jika ada pertanyaan, hubungi panitia wisuda.<br>
            &copy; {{ date('Y') }} Absensi Wisuda Hybrid
        </p>
    </div>
</div>
</body>
</html>
