<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Presensi {{ $period['label'] }}</title>
    <style>
        @page { margin: 20mm 15mm; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; color: #1f2937; }
        h1 { font-size: 18px; text-align: center; margin-bottom: 4px; }
        .sub { text-align: center; font-size: 12px; color: #6b7280; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th {
            background: #e0e7ff; text-align: center; font-size: 9px;
            font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;
            padding: 8px 6px; border: 1px solid #cbd5e1;
        }
        td {
            text-align: center; padding: 6px; border: 1px solid #cbd5e1;
        }
        td.name { text-align: left; font-weight: 600; }
        .pct-high { color: #2563eb; font-weight: 700; }
        .pct-mid { color: #ca8a04; font-weight: 700; }
        .pct-low { color: #dc2626; font-weight: 700; }
        .footer { text-align: center; font-size: 9px; color: #9ca3af; margin-top: 20px; }
    </style>
</head>
<body>
    <h1>Rekap Presensi 11 RPL B</h1>
    <div class="sub">{{ $period['label'] }} &bull; {{ $totalSessions }} pertemuan</div>

    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th style="text-align:left">Nama Siswa</th>
                <th>NIS</th>
                <th>Hadir</th>
                <th>Sakit</th>
                <th>Izin</th>
                <th>Alpha</th>
                <th>Total</th>
                <th>%</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $s)
            <tr>
                <td>{{ $s->absent_number }}</td>
                <td class="name">{{ $s->name }}</td>
                <td>{{ $s->nis }}</td>
                <td>{{ $s->hadir }}</td>
                <td>{{ $s->sakit }}</td>
                <td>{{ $s->izin }}</td>
                <td>{{ $s->alpha }}</td>
                <td>{{ $s->total }}</td>
                <td class="{{ $s->pct >= 90 ? 'pct-high' : ($s->pct >= 75 ? 'pct-mid' : 'pct-low') }}">{{ $s->pct }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dihasilkan dari Sistem Presensi 11 RPL B &mdash; {{ now()->locale('id')->isoFormat('D MMMM Y') }}
    </div>
</body>
</html>
