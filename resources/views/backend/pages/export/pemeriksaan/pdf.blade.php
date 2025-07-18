<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Pemeriksaan</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            margin: 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .logo {
            width: 60px;
        }

        .title {
            text-align: center;
            flex: 1;
        }

        .title h1 {
            font-size: 16px;
            margin: 0;
        }

        .title h2 {
            font-size: 14px;
            margin: 2px 0;
        }

        .title h3 {
            font-size: 13px;
            margin: 2px 0;
        }

        .address {
            font-size: 11px;
            margin-top: 5px;
        }

        .patient-info {
            font-size: 12px;
            margin-top: 5px;
        }

        .patient-info td {
            padding: 2px 5px;
        }

        table.detail {
            margin-top: 10px;
            width: 100%;
            border-collapse: collapse;
        }

        table.detail th,
        table.detail td {
            border: 1px solid #000;
            padding: 5px;
        }

        hr {
            margin-top: 10px;
            margin-bottom: 10px;
            border: 1px solid #000;
        }
    </style>
</head>

<body>
    <div class="header">
        <table width="100%" style="border: none;">
            <tr>
                <td style="width: 60px;">
                    @if(file_exists(public_path('backend/assets/logo/logo.png')))
                        <img src="{{ public_path('backend/assets/logo/logo.png') }}" alt="Logo" width="60">
                    @endif
                </td>
                <td style="text-align: center;">
                    <h1 style="margin: 0;">Hasil Rekam Medis</h1>
                    <h2 style="margin: 0;">Pustu (Puskesmas Pembantu)</h2>
                    <h3 style="margin: 0;">Desa Wakap</h3>
                    <p style="margin: 0; font-size: 12px;">
                        Pustu desa wakap, F3GM+PPM, Wakap,<br>
                        Kec. Bantarkalong, Kabupaten Tasikmalaya, Jawa Barat 46187
                    </p>
                </td>
            </tr>
        </table>
    </div>

    <hr>

    <div class="patient-info">
        <table border="0">
            <tr>
                <td>Nama</td>
                <td>:</td>
                <td>{{ $pemeriksaan->pasien->user->name ?? '-' }}</td>
            </tr>
            <tr>
                <td>NIK</td>
                <td>:</td>
                <td>{{ $pemeriksaan->pasien->user->nik ?? '-' }}</td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>:</td>
                <td>{{ $pemeriksaan->pasien->user->alamat ?? '-' }}</td>
            </tr>
            <tr>
                <td>Tanggal Periksa</td>
                <td>:</td>
                <td>{{ \Carbon\Carbon::parse($pemeriksaan->tanggal_pemeriksaan)->translatedFormat('d F Y') }}</td>
            </tr>
            <tr>
                <td>Keluhan</td>
                <td>:</td>
                <td>{{ $pemeriksaan->gejala ?? '-' }}</td>
            </tr>
            <tr>
                <td>Tensi</td>
                <td>:</td>
                <td>{{ $pemeriksaan->tensi_sistolik ?? '-' }} / {{ $pemeriksaan->tensi_diastolik ?? '-' }}</td>
            </tr>
            <tr>
                <td>Catatan Dokter</td>
                <td>:</td>
                <td>{{ $pemeriksaan->catatan_dokter ?? '-' }}</td>
            </tr>
            <tr>
                <td>Resep Obat</td>
                <td>:</td>
                <td>
                    @if(!empty($pemeriksaan->resep_decoded))
                        @foreach ($pemeriksaan->resep_decoded as $resep)
                            {{ $resep['nama'] ?? '-' }} ({{ $resep['jumlah'] ?? 0 }} x
                            Rp{{ number_format($resep['harga'] ?? 0) }})@if(!$loop->last), @endif
                        @endforeach
                    @else
                        Tidak ada
                    @endif
                </td>
            </tr>
            <tr>
                <td>Total Harga Obat</td>
                <td>:</td>
                <td>Rp{{ number_format($pemeriksaan->total_obat ?? 0) }}</td>
            </tr>
            <tr>
                <td>Biaya Pemeriksaan</td>
                <td>:</td>
                <td>Rp{{ number_format($pemeriksaan->biaya ?? 0) }}</td>
            </tr>
            <tr>
                <td>Total Bayar</td>
                <td>:</td>
                <td>Rp{{ number_format(($pemeriksaan->biaya ?? 0) + ($pemeriksaan->total_obat ?? 0)) }}</td>
            </tr>
        </table>
    </div>

</body>

</html>
