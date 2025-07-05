<!DOCTYPE html>
<html>
<head>
    <title>Laporan Rekam Medis</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .header h2 {
            margin: 0;
            font-size: 18px;
        }
        .header p {
            margin: 5px 0;
        }
        .periode {
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }
        th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
        }
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN REKAM MEDIS</h2>
        <h4>PUSKESMAS PEMBANTU DESA WAKAP</h4>
        <p>Pustu Desa Wakap, F3GM+PPM, Wakap, Kec. Bantarkalong, Kabupaten Tasikmalaya, Jawa Barat 46187</p>
    </div>

    <div class="periode">
        Periode: {{ \Carbon\Carbon::parse($tanggalMulai)->translatedFormat('d F Y') }} - 
        {{ \Carbon\Carbon::parse($tanggalSelesai)->translatedFormat('d F Y') }}
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="20%">Nama Pasien</th>
                <th width="10%">Suhu</th>
                <th width="15%">Tensi</th>
                <th width="20%">Resep Obat</th>
                <th width="15%">Tanggal</th>
                <th width="15%">Total Bayar</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pemeriksaans as $item)
                @php
                    $resep = json_decode($item->resep_obat, true) ?? [];
                    $totalObat = collect($resep)->sum(
                        fn($obat) => ($obat['jumlah'] ?? 0) * ($obat['harga'] ?? 0),
                    );
                @endphp
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $item->user->name ?? '-' }}</td>
                    <td class="text-center">{{ $item->suhu }}°C</td>
                    <td class="text-center">{{ $item->tensi_sistolik }}/{{ $item->tensi_diastolik }} mmHg</td>
                    <td>
                        @if (count($item->resep_formatted))
                            @foreach ($item->resep_formatted as $r)
                                {{ $r['nama_obat'] ?? ($r['nama'] ?? '-') }}@if(!$loop->last), @endif
                            @endforeach
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-center">
                        {{ \Carbon\Carbon::parse($item->waktu_pemeriksaan)->translatedFormat('d/m/Y H:i') }}
                    </td>
                    <td class="text-right">Rp{{ number_format($totalObat + $item->biaya, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data pada periode ini</td>
                </tr>
            @endforelse
        </tbody>
        @if($pemeriksaans->count() > 0)
        <tfoot>
            <tr>
                <th colspan="6" class="text-center">TOTAL</th>
                <th class="text-right">
                    Rp{{ number_format($pemeriksaans->sum(function($item) {
                        $resep = json_decode($item->resep_obat, true) ?? [];
                        $totalObat = collect($resep)->sum(fn($obat) => ($obat['jumlah'] ?? 0) * ($obat['harga'] ?? 0));
                        return $totalObat + $item->biaya;
                    }), 0, ',', '.') }}
                </th>
            </tr>
        </tfoot>
        @endif
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }} WIB</p>
        <br><br>
        <p>_________________________</p>
        <p>Petugas</p>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
