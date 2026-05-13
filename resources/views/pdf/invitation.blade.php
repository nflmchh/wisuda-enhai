<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Undangan Wisuda — {{ $participant->name }}</title>
    <style>
        @page {
            margin: 0;
            size: A4 portrait;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #f5f0e8;
            color: #1a2e5a;
        }

        .page-wrap { padding: 14pt; background: #f5f0e8; }

        .inner {
            background: #fff;
            border: 2pt solid #c9a227;
        }

        /* Header */
        .header {
            background: #1a2e5a;
            padding: 18pt 30pt 16pt;
            text-align: center;
        }
        .hdr-eye {
            color: #c9a227;
            font-size: 6.5pt;
            letter-spacing: 4pt;
            margin-bottom: 7pt;
        }
        .hdr-title {
            color: #fff;
            font-size: 28pt;
            font-weight: bold;
            margin: 0 0 5pt 0;
            letter-spacing: 2pt;
        }
        .hdr-sub {
            color: rgba(255,255,255,0.6);
            font-size: 7.5pt;
            letter-spacing: 0.8pt;
        }

        .gold-bar { height: 3pt; background: #c9a227; font-size:0; line-height:0; }

        .body { padding: 10pt 32pt 10pt; }

        /* Ornament rule */
        .orn { width:100%; border-collapse:collapse; margin: 7pt 0; }
        .orn td.l { border-top:1pt solid #c9a227; height:1pt; }
        .orn td.g { color:#c9a227; font-size:8pt; padding:0 8pt; text-align:center; white-space:nowrap; vertical-align:middle; }

        /* Greeting */
        .greeting { text-align:center; margin: 2pt 0 8pt; }
        .g-lbl { font-size:6.5pt; color:#aaa; letter-spacing:3pt; margin-bottom:5pt; }
        .g-name { font-size:21pt; font-weight:bold; color:#1a2e5a; line-height:1.15; }
        .g-nrp  { font-size:9.5pt; color:#c9a227; font-weight:bold; letter-spacing:2pt; margin-top:3pt; }

        /* Two-column: data left, QR right */
        .cols { width:100%; border-collapse:collapse; }
        .col-data { vertical-align:top; padding-right:16pt; }
        .col-qr   { vertical-align:top; text-align:center; width:165pt; }

        /* Data section */
        .sec-lbl { font-size:6.5pt; color:#aaa; letter-spacing:3pt; margin-bottom:7pt; }
        .dtable { width:100%; border-collapse:collapse; font-size:8.5pt; }
        .dtable tr { border-bottom:0.75pt solid #ece8dc; }
        .dtable tr:last-child { border-bottom:none; }
        .dtable td { padding:4.5pt 6pt; }
        .dtable td.l { color:#999; width:40%; }
        .dtable td.s { color:#c9a227; width:5%; text-align:center; }
        .dtable td.v { color:#1a2e5a; font-weight:bold; }

        /* QR */
        .qr-sec-lbl { font-size:6.5pt; color:#aaa; letter-spacing:3pt; margin-bottom:8pt; text-align:center; }
        .qr-img { width:145pt; height:145pt; display:block; }
        .qr-token { font-size:5.5pt; color:#ccc; margin-top:6pt; text-align:center; word-break:break-all; }

        /* Note */
        .note {
            background:#faf7f0;
            border-left:3pt solid #c9a227;
            padding:7pt 10pt;
            font-size:7.5pt;
            color:#555;
            line-height:1.6;
            margin: 8pt 0 4pt;
        }
        .note strong { color:#1a2e5a; }

        /* Footer */
        .footer { background:#1a2e5a; padding:8pt 30pt; text-align:center; }
        .footer p { color:rgba(255,255,255,0.5); font-size:6.5pt; line-height:1.6; margin:0; }
        .footer strong { color:#c9a227; }
    </style>
</head>
<body>
<div class="page-wrap">
<div class="inner">

    <div class="header">
        <div class="hdr-eye">UNDANGAN RESMI KEHADIRAN</div>
        <div class="hdr-title">WISUDA</div>
        <div class="hdr-sub">Absensi Wisuda Hybrid &nbsp;&middot;&nbsp; Dokumen Resmi</div>
    </div>
    <div class="gold-bar"></div>

    <div class="body">

        {{-- Ornament --}}
        <table class="orn"><tr>
            <td class="l"></td><td class="g">&#9670;</td><td class="l"></td>
        </tr></table>

        {{-- Greeting --}}
        <div class="greeting">
            <div class="g-lbl">KEPADA YTH.</div>
            <div class="g-name">{{ $participant->name }}</div>
            <div class="g-nrp">{{ $participant->nrp }}</div>
        </div>

        {{-- Ornament --}}
        <table class="orn"><tr>
            <td class="l"></td><td class="g">&#9670;</td><td class="l"></td>
        </tr></table>

        {{-- Two-column: data | QR --}}
        <table class="cols">
            <tr>
                {{-- Left: data peserta --}}
                <td class="col-data">
                    <div class="sec-lbl">DATA PESERTA</div>
                    <table class="dtable">
                        <tr>
                            <td class="l">Nama Lengkap</td>
                            <td class="s">:</td>
                            <td class="v">{{ $participant->name }}</td>
                        </tr>
                        <tr>
                            <td class="l">NRP / NIM</td>
                            <td class="s">:</td>
                            <td class="v">{{ $participant->nrp }}</td>
                        </tr>
                        @if($participant->faculty)
                        <tr>
                            <td class="l">Fakultas</td>
                            <td class="s">:</td>
                            <td class="v">{{ $participant->faculty }}</td>
                        </tr>
                        @endif
                        @if($participant->program_study)
                        <tr>
                            <td class="l">Program Studi</td>
                            <td class="s">:</td>
                            <td class="v">{{ $participant->program_study }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td class="l">Kategori</td>
                            <td class="s">:</td>
                            <td class="v">{{ $participant->category }}</td>
                        </tr>
                    </table>
                </td>

                {{-- Right: QR code --}}
                <td class="col-qr">
                    <div class="qr-sec-lbl">QR CODE KEHADIRAN</div>
                    <table style="margin:0 auto; border-collapse:collapse;">
                        <tr>
                            <td style="border:2pt solid #1a2e5a; padding:8pt; background:#fff; text-align:center;">
                                <img class="qr-img" src="data:image/png;base64,{{ $qrPng }}" alt="QR Code">
                            </td>
                        </tr>
                    </table>
                    <div class="qr-token">{{ $participant->qr_token }}</div>
                </td>
            </tr>
        </table>

        {{-- Note --}}
        <div class="note">
            <strong>Petunjuk:</strong> Tunjukkan QR Code ini kepada petugas scanner saat tiba di lokasi wisuda.
            Pastikan layar HP terang dan QR Code terlihat jelas. Berlaku hanya <strong>satu kali</strong>.
        </div>

    </div>{{-- /body --}}

    <div class="gold-bar"></div>

    <div class="footer">
        <p><strong>Sistem Absensi Wisuda Hybrid</strong><br>Dokumen resmi. Jangan diubah atau dipalsukan.</p>
    </div>

</div>
</div>
</body>
</html>
