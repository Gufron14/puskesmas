<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Rekap Pemeriksaan</title>
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

        .header h2 {
            font-size: 14px;
            margin: 2px 0;
        }

        .header h3 {
            font-size: 13px;
            margin: 2px 0;
        }

        .header p {
            font-size: 11px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .print-btn {
            margin: 20px 0;
            text-align: center;
        }

        .print-btn button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        .print-btn button:hover {
            background-color: #0056b3;
        }

        @media print {
            .print-btn {
                display: none;
            }
        }
    </style>
</head>

<body>

    <div class="print-btn">
        <button onclick="window.print()">🖨️ Cetak</button>
        <button onclick="window.close()" style="background-color: #6c757d; margin-left: 10px;">❌ Tutup</button>
    </div>

    <div class="header">
        <h1>Rekap Pemeriksaan Pasien</h1>
        <h2>Pustu Desa Wakap</h2>
        <h3>Kec. Bantarkalong, Kab. Tasikmalaya</h3>
        <p>F3GM+PPM, Wakap, Jawa Barat 46187</p>
    </div>

<table>
    <thead>
        <tr>
            <th>Nama</th>
            <th>Suhu (°C)</th>
            <th>Tensi (mmHg)</th>
            <th>Tanggal Periksa</th>
            <th>Keluhan</th>
            <th>Diagnosa</th>
            <th>Catatan Dokter</th>
            <th>Resep Obat</th>
            {{-- <th>Total Bayar (Rp)</th> --}}
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>{{ $pemeriksaan->user->name ?? '-' }}</td>
            <td>{{ $pemeriksaan->suhu }}</td>
            <td>{{ $pemeriksaan->tensi_sistolik }} / {{ $pemeriksaan->tensi_diastolik }}</td>
            <td>{{ \Carbon\Carbon::parse($pemeriksaan->tanggal_pemeriksaan)->translatedFormat('d M Y') }}</td>
            <td>{{ $pemeriksaan->gejala }}</td>
            <td>{{ $pemeriksaan->diagnosa }}</td>
            <td>{{ $pemeriksaan->catatan_dokter }}</td>
<td>
    @if (!empty($pemeriksaan->resep_decoded) && is_array($pemeriksaan->resep_decoded))
        @foreach ($pemeriksaan->resep_decoded as $r)
            {{ $r['nama_obat'] ?? '-' }} @if(!$loop->last), @endif
        @endforeach
    @else
        Tidak ada resep
    @endif
</td>


            {{-- <td>{{ number_format($pemeriksaan->biaya + $pemeriksaan->total_obat) }}</td> --}}
        </tr>
    </tbody>
</table>


    <script>
        window.onload = function () {
            setTimeout(function () {
                window.print();
            }, 500);
        }
    </script>

</body>

</html>
