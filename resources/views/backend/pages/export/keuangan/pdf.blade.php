<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Invoice Pembayaran</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            padding: 40px;
            color: #333;
        }

        .invoice-box {
            max-width: 800px;
            margin: auto;
            border: 1px solid #eee;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
        }

        .invoice-box h2 {
            margin-top: 0;
            font-size: 24px;
            font-weight: bold;
        }

        .info {
            margin-bottom: 20px;
        }

        .info p {
            margin: 4px 0;
        }

        table {
            width: 100%;
            line-height: 24px;
            text-align: left;
            border-collapse: collapse;
        }

        table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        table th,
        table td {
            padding: 10px;
            border: 1px solid #ddd;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-style: italic;
            color: #555;
        }

        .logo {
            font-size: 20px;
            font-weight: bold;
            color: #007BFF;
            margin-bottom: 20px;
        }

        .text-right {
            text-align: right;
        }
    </style>
</head>

<body>
    <div class="invoice-box">
        <h2>Invoice Pembayaran</h2>

        <div class="info">
            <p><strong>Nama Pasien:</strong> {{ $pembayaran->pemeriksaan->pasien->user->name }}</p>
            <p><strong>Tanggal Pemeriksaan:</strong>
                {{ \Carbon\Carbon::parse($pembayaran->pemeriksaan->tanggal_pemeriksaan)->translatedFormat('l, d F Y, H:i') }}
            </p>
        </div>

        <table>
            <tr>
                <th>Deskripsi</th>
                <th class="text-right">Jumlah</th>
            </tr>
            <tr>
                <td>Biaya Pemeriksaan</td>
                <td class="text-right">Rp{{ number_format($pembayaran->biaya_pemeriksaan, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Total Obat</td>
                <td class="text-right">Rp{{ number_format($pembayaran->total_obat, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Total Tagihan</th>
                <th class="text-right">Rp{{ number_format($pembayaran->total_tagihan, 0, ',', '.') }}</th>
            </tr>
            <tr>
                <td>Jumlah Bayar</td>
                <td class="text-right">Rp{{ number_format($pembayaran->jumlah_bayar, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Kembalian</td>
                <td class="text-right">Rp{{ number_format($pembayaran->kembalian, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Metode Pembayaran</td>
                <td class="text-right">{{ strtoupper($pembayaran->metode) }}</td>
            </tr>
        </table>

        <div class="footer">
            Terima kasih atas kunjungan Anda.
        </div>
    </div>
</body>

</html>
