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
        <table width="100%" style="border: none;">
            <tr>
                <td style="width: 60px;">
                    <img src="{{ asset('backend/assets/logo/logo.png') }}" alt="Logo" width="60">
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
                <td>{{ $pemeriksaan->user->name ?? '-' }}</td>
            </tr>
            <tr>
                <td>NIK</td>
                <td>:</td>
                <td>{{ $pemeriksaan->user->nik  ?? '-'}}</td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>:</td>
                <td>{{ $pemeriksaan->user->alamat ?? '-' }}</td>
            </tr>
            <tr>
                <td>Tanggal Periksa</td>
                <td>:</td>
                <td>{{ \Carbon\Carbon::parse($pemeriksaan->tanggal_pemeriksaan)->translatedFormat('d F Y') }}</td>
            </tr>
            <tr>
                <td>Keluhan</td>
                <td>:</td>
                <td>{{ $pemeriksaan->gejala }}</td>
            </tr>
            <tr>
                <td>Tensi</td>
                <td>:</td>
                <td>{{ $pemeriksaan->tensi_sistolik }} / {{ $pemeriksaan->tensi_diastolik }}</td>
            </tr>
            <tr>
                <td>Catatan Dokter</td>
                <td>:</td>
                <td>{{ $pemeriksaan->catatan_dokter }}</td>
            </tr>
            <tr>
                <td>Resep Obat</td>
                <td>:</td>
                <td>
                    @forelse ($pemeriksaan->resep_decoded as $resep)
                        {{ $resep['nama'] ?? '-' }} ({{ $resep['jumlah'] ?? 0 }} x
                        Rp{{ number_format($resep['harga'] ?? 0) }}),
                    @empty
                        Tidak ada
                    @endforelse
                </td>
            </tr>
            <tr>
                <td>Total Harga Obat</td>
                <td>:</td>
                <td>Rp{{ number_format($pemeriksaan->total_obat) }}</td>
            </tr>
            <tr>
                <td>Biaya Pemeriksaan</td>
                <td>:</td>
                <td>Rp{{ number_format($pemeriksaan->biaya) }}</td>
            </tr>
            <tr>
                <td>Total Bayar</td>
                <td>:</td>
                <td>Rp{{ number_format($pemeriksaan->biaya + $pemeriksaan->total_obat) }}</td>
            </tr>
        </table>
    </div>

    <script>
        // Auto print ketika halaman dimuat
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        }
    </script>

</body>

</html>
