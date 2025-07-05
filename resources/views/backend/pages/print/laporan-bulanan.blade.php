<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>Laporan Keuangan Bulanan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }

        h2,
        h4 {
            text-align: center;
            margin-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 8px 10px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        .total-row {
            font-weight: bold;
            background-color: #e0e0e0;
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

    <h2>Laporan Keuangan Bulanan</h2>
    <h4>{{ \Carbon\Carbon::create($tahun, $bulan)->translatedFormat('F Y') }}</h4>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pasien</th>
                <th>Tanggal Pemeriksaan</th>
                <th>Harga Obat</th>
                <th>Biaya Pemeriksaan</th>
                <th>Total Tagihan</th>
                <th>Metode Pembayaran</th>
                <th>Jumlah Bayar</th>
                <th>Kembalian</th>
            </tr>
        </thead>
        <tbody>
            @php
                $total_obat = 0;
                $total_pemeriksaan = 0;
                $total_tagihan = 0;
                $total_bayar = 0;
                $total_kembalian = 0;
            @endphp
            @foreach ($pembayarans as $key => $item)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $item->pemeriksaan->pasien->user->name ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->pemeriksaan->tanggal_pemeriksaan)->translatedFormat('d M Y, H:i') }}
                    </td>
                    <td>Rp{{ number_format($item->total_obat, 0, ',', '.') }}</td>
                    <td>Rp{{ number_format($item->biaya_pemeriksaan, 0, ',', '.') }}</td>
                    <td>Rp{{ number_format($item->total_tagihan, 0, ',', '.') }}</td>
                    <td>{{ strtoupper($item->metode) }}</td>
                    <td>Rp{{ number_format($item->jumlah_bayar, 0, ',', '.') }}</td>
                    <td>Rp{{ number_format($item->kembalian, 0, ',', '.') }}</td>
                </tr>

                @php
                    $total_obat += $item->total_obat;
                    $total_pemeriksaan += $item->biaya_pemeriksaan;
                    $total_tagihan += $item->total_tagihan;
                    $total_bayar += $item->jumlah_bayar;
                    $total_kembalian += $item->kembalian;
                @endphp
            @endforeach
            <tr class="total-row">
                <td colspan="3">Total</td>
                <td>Rp{{ number_format($total_obat, 0, ',', '.') }}</td>
                <td>Rp{{ number_format($total_pemeriksaan, 0, ',', '.') }}</td>
                <td>Rp{{ number_format($total_tagihan, 0, ',', '.') }}</td>
                <td></td>
                <td>Rp{{ number_format($total_bayar, 0, ',', '.') }}</td>
                <td>Rp{{ number_format($total_kembalian, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <p style="margin-top: 30px; text-align: center;">Laporan ini dibuat secara otomatis oleh sistem.</p>

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
