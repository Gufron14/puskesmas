@extends('frontend.layouts.app')

@section('content')
    <section class="page-section" id="rekam-medis" style="background-color: #f8f9fa">
        <div class="container pt-5 pb-5">
            <div class="mb-4 text-center">
                <h3 class="section-heading text-uppercase">Riwayat Rekam Medis</h3>
            </div>

            @auth
                @if ($pemeriksaans->isEmpty())
                    <div class="alert alert-warning text-center">
                        Belum ada data rekam medis ditemukan untuk akun Anda.
                    </div>
                @else
                    @foreach ($pemeriksaans as $item)
                        @php
                            $user = $item->pasien->user ?? null;
                            $resep = json_decode($item->resep_obat, true) ?? [];
                            $totalObat = collect($resep)->sum(
                                fn($obat) => ($obat['jumlah'] ?? 0) * ($obat['harga'] ?? 0),
                            );
                            $tanggal = \Carbon\Carbon::parse($item->tanggal_pemeriksaan);
                        @endphp

                        <div class="card shadow-sm mb-5 border-0">
                            <div class="card-body p-5">
                                <div class="row">
                                    <!-- Kolom Kiri -->
                                    <div class="col-md-6">
                                        <div class="mt-3">
                                            <h6 class="text-warning"><i class="fe fe-clipboard"></i>Keluhan</h6>
                                            <p class="mb-0">{{ $item->gejala }}</p>
                                        </div>
                                        @if ($item->diagnosa)
                                            <div class="mt-3">
                                                <h6 class="text-warning"><i class="fe fe-clipboard"></i> Diagnosa</h6>
                                                <p class="mb-0">{{ $item->diagnosa }}</p>
                                            </div>
                                        @endif
                                        @if ($item->catatan_dokter)
                                            <div class="mt-3">
                                                <h6 class="text-info"><i class="fe fe-edit-3"></i> Catatan Dokter</h6>
                                                <p class="mb-0">{{ $item->catatan_dokter }}</p>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Kolom Kanan -->
                                    <div class="col-md-6">

                                        <div class="card shadow-sm mb-4">
                                            <div class="card-body p-4">
                                                <h6 class="text-success mb-3"><i class="fe fe-activity"></i> Pemeriksaan</h6>
                                                <table class="table table-sm table-borderless">
                                                    <tr>
                                                        <td><strong>Tanggal</strong></td>
                                                        <td>:</td>
                                                        <td>{{ $tanggal->format('d/m/Y') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Jam</strong></td>
                                                        <td>:</td>
                                                        <td>{{ $item->waktu_pemeriksaan->format('H:i') }} WIB</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Tekanan Darah</strong></td>
                                                        <td>:</td>
                                                        <td>{{ $item->tensi_sistolik }}/{{ $item->tensi_diastolik }} mmHg</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Suhu Tubuh</strong></td>
                                                        <td>:</td>
                                                        <td>{{ $item->suhu ?? '-' }}°C</td>
                                                    </tr>
                                                    {{-- <tr><td><strong>Keluhan</strong></td><td>:</td><td>{{ $item->gejala ?? '-' }}</td></tr> --}}
                                                </table>
                                            </div>
                                        </div>

                                        <div class="card shadow-sm">
                                            <div class="card-body p-4">

                                                <h6 class="text-dark"><i class="fe fe-dollar-sign"></i> Ringkasan Biaya</h6>
                                                <div class="table-responsive">
                                                    <table class="table table-sm table-borderless">
                                                        <tr>
                                                            <td><strong>Biaya Pemeriksaan</strong></td>
                                                            <td>:</td>
                                                            <td class="text-end">
                                                                Rp{{ number_format($item->biaya ?? 0, 0, ',', '.') }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>Total Obat</strong></td>
                                                            <td>:</td>
                                                            <td class="text-end">Rp{{ number_format($totalObat, 0, ',', '.') }}
                                                            </td>
                                                        </tr>
                                                        <tr class="border-top">
                                                            <td><strong>Total Keseluruhan</strong></td>
                                                            <td>:</td>
                                                            <td class="text-end text-primary fw-bold h5">
                                                                Rp{{ number_format($totalObat + ($item->biaya ?? 0), 0, ',', '.') }}
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if (count($resep))
                                    <hr>
                                    <h6 class="text-danger"><i class="fe fe-package"></i> Resep Obat</h6>
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Nama Obat</th>
                                                    <th>Jumlah</th>
                                                    <th>Harga Satuan</th>
                                                    <th>Aturan</th>
                                                    <th>Subtotal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($resep as $r)
                                                    <tr>
                                                        <td>{{ $r['nama_obat'] ?? '-' }}</td>
                                                        <td>{{ $r['jumlah'] ?? 0 }} pcs</td>
                                                        <td>Rp{{ number_format($r['harga'] ?? 0, 0, ',', '.') }}</td>
                                                        <td>
                                                            <span
                                                                class="badge bg-{{ ($r['keterangan_makan'] ?? '') == 'sesudah_makan' ? 'success' : 'warning' }}">
                                                                {{ $r['keterangan_display'] ?? ucwords(str_replace('_', ' ', $r['keterangan_makan'] ?? '-')) }}
                                                            </span>
                                                        </td>
                                                        <td><strong>Rp{{ number_format(($r['jumlah'] ?? 0) * ($r['harga'] ?? 0), 0, ',', '.') }}</strong>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>

                            <div class="card-footer d-flex justify-content-between bg-white">
                                <small class="text-muted">ID Pemeriksaan: #{{ $item->id }}</small>
                                <a href="{{ route('pemeriksaan.exportPdf', $item->id) }}" target="_blank"
                                    class="btn btn-sm btn-outline-success">
                                    <i class="fe fe-printer"></i> Cetak PDF
                                </a>
                            </div>
                        </div>
                    @endforeach
                @endif
            @else
                <div class="alert alert-info text-center">
                    Silakan <a href="{{ route('login') }}">login</a> terlebih dahulu untuk melihat riwayat rekam medis Anda.
                </div>
            @endauth
        </div>
    </section>
@endsection
