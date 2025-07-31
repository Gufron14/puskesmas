<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Ringkasan Pemeriksaan</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            margin: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 16px;
            margin: 0;
        }

        .header h2,
        .header h3 {
            margin: 0;
        }

        .header p {
            font-size: 11px;
            margin: 2px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #f0f0f0;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>

    <div class="header">
        <h1>Ringkasan Pemeriksaan Pasien</h1>
        <h2>Pustu Desa Wakap</h2>
        <h3>Kec. Bantarkalong, Kab. Tasikmalaya</h3>
        <p>F3GM+PPM, Wakap, Jawa Barat 46187</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>NIK</th>
                <th>Alamat</th>
                <th>Tanggal Periksa</th>
                <th>Gejala</th>
                <th>Tensi</th>
                <th>Suhu</th>
                <th>Diagnosa</th>
                <th>Catatan Dokter</th>
                <th>Resep Obat</th>
                {{-- <th>Total Obat (Rp)</th>
                <th>Biaya Pemeriksaan (Rp)</th>
                <th>Total Bayar (Rp)</th> --}}
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $pemeriksaan->pasien->user->name ?? '-' }}</td>
                <td>{{ $pemeriksaan->pasien->user->nik ?? '-' }}</td>
                <td>{{ $pemeriksaan->pasien->user->alamat ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($pemeriksaan->tanggal_pemeriksaan)->translatedFormat('d F Y') }}</td>
                <td>{{ $pemeriksaan->gejala ?? '-' }}</td>
                <td>{{ $pemeriksaan->tensi_sistolik ?? '-' }} / {{ $pemeriksaan->tensi_diastolik ?? '-' }}</td>
                <td>{{ $pemeriksaan->suhu }}</td>
                <td>{{ $pemeriksaan->diagnosa }}</td>
                <td>{{ $pemeriksaan->catatan_dokter ?? '-' }}</td>
<td>
    @if (!empty($pemeriksaan->resep_decoded) && is_array($pemeriksaan->resep_decoded))
        @foreach ($pemeriksaan->resep_decoded as $r)
            {{ $r['nama_obat'] ?? '-' }} @if(!$loop->last), @endif
        @endforeach
    @else
        Tidak ada resep
    @endif
</td>
                {{-- <td>Rp{{ number_format($pemeriksaan->total_obat ?? 0) }}</td>
                <td>Rp{{ number_format($pemeriksaan->biaya ?? 0) }}</td>
                <td>Rp{{ number_format(($pemeriksaan->biaya ?? 0) + ($pemeriksaan->total_obat ?? 0)) }}</td> --}}
            </tr>
        </tbody>
    </table>

</body>

</html>
