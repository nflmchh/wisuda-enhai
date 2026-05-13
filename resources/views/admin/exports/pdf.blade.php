<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Peserta Wisuda</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 10px; color: #333; margin: 15px; }
        h1 { font-size: 14px; color: #1e3a5f; margin-bottom: 4px; }
        p.sub { font-size: 9px; color: #666; margin-bottom: 12px; }
        table { width: 100%; border-collapse: collapse; }
        thead tr { background: #1e3a5f; color: #fff; }
        th { padding: 6px 8px; text-align: left; font-size: 9px; font-weight: bold; text-transform: uppercase; }
        td { padding: 5px 8px; border-bottom: 1px solid #eee; }
        tr:nth-child(even) { background: #f8fafc; }
        .badge { display: inline-block; padding: 2px 6px; border-radius: 4px; font-size: 8px; font-weight: bold; }
        .hadir { background: #dcfce7; color: #166534; }
        .belum { background: #f1f5f9; color: #475569; }
    </style>
</head>
<body>
    <h1>Laporan Data Peserta Wisuda</h1>
    <p class="sub">
        Dicetak: {{ now()->format('d/m/Y H:i') }}
        @if(!empty($filters['status'])) | Status: {{ $filters['status'] }} @endif
        @if(!empty($filters['category'])) | Kategori: {{ $filters['category'] }} @endif
        | Total: {{ $participants->count() }} peserta
    </p>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nama</th>
                <th>NRP</th>
                <th>Kategori</th>
                <th>Fakultas</th>
                <th>Program Studi</th>
                <th>Status</th>
                <th>Jam Hadir</th>
                <th>Gate</th>
            </tr>
        </thead>
        <tbody>
            @foreach($participants as $i => $p)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $p->name }}</td>
                <td>{{ $p->nrp }}</td>
                <td>{{ $p->category }}</td>
                <td>{{ $p->faculty ?? '-' }}</td>
                <td>{{ $p->program_study ?? '-' }}</td>
                <td>
                    @if($p->attendance_status === 'hadir')
                        <span class="badge hadir">Hadir</span>
                    @else
                        <span class="badge belum">Belum</span>
                    @endif
                </td>
                <td>{{ $p->attended_at ? $p->attended_at->format('d/m H:i') : '-' }}</td>
                <td>{{ $p->scanner_gate ? 'G'.$p->scanner_gate : '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
