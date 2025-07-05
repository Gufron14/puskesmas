@extends('frontend.layouts.app')

@section('content')
    <section class="page-section vh-100" id="services">
        <div class="container pt-5 pb-5">
            <div class="mb-5">
                <h2 class="section-heading text-uppercase">Riwayat Rekam Medis</h2>
            </div>
            @auth
                @if ($pemeriksaans->isEmpty())
                    <div class="alert alert-warning">
                        Belum ada data rekam medis ditemukan untuk akun Anda.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Pasien</th>
                                    <th>NIK</th>
                                    <th>Keluhan</th>
                                    <th>Tensi</th>
                                    <th>Hasil</th>
                                    <th>Resep Obat</th>
                                    <th>Tanggal</th>
                                    <th>Total Bayar</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pemeriksaans as $item)
                                    @php
                                        $resep = json_decode($item->resep_obat, true) ?? [];
                                        $totalObat = collect($resep)->sum(
                                            fn($obat) => ($obat['jumlah'] ?? 0) * ($obat['harga'] ?? 0),
                                        );
                                    @endphp
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->pasien->user->name ?? '-' }}</td>
                                        <td>{{ $item->pasien->user->nik ?? '-' }}</td>
                                        <td>{{ $item->gejala }}</td>
                                        <td>{{ $item->tensi_sistolik }} / {{ $item->tensi_diastolik }}</td>
                                        <td>{{ $item->catatan_dokter }}</td>
                                        <td>
                                            @if (count($resep))
                                                <ul class="mb-0 pl-3">
                                                    @foreach ($resep as $r)
                                                        <li>{{ $r['nama'] ?? '-' }} : Rp{{ number_format($r['harga']) ?? 0 }}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($item->tanggal_pemeriksaan)->translatedFormat('l, d F Y, H:i') }}
                                        </td>
                                        <td>Rp{{ number_format($totalObat + $item->biaya, 0, ',', '.') }}</td>
{{-- filepath: c:\Users\GUFRON\Documents\Project Web\puskesmas\resources\views\frontend\rekammedis.blade.php --}}
<td>
    <a href="{{ route('pemeriksaan.exportPdf', $item->id) }}" target="_blank"
        class="btn btn-success text-white">Cetak</a>
</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            @else
                <div class="alert alert-info">
                    Silakan <a href="{{ route('login') }}">login</a> terlebih dahulu untuk melihat riwayat rekam medis Anda.
                </div>
            @endauth

        </div>
    </section>
@endsection
