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
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle">
                            <thead class="table-primary text-center">
                                <tr>
                                    <th>Tanggal Pemeriksaan</th>
                                    <th>Waktu Pemeriksaan</th>
                                    <th>Tekanan Darah</th>
                                    <th>Suhu</th>
                                    <th>Keluhan</th>
                                    <th>Diagnosa</th>
                                    <th>Catatan Dokter</th>
                                    <th>Resep Obat</th>
                                    <th>PDF</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pemeriksaans as $item)
                                    @php
                                        $user = $item->pasien->user ?? null;
                                        $resep = json_decode($item->resep_obat, true) ?? [];
                                        $totalObat = collect($resep)->sum(
                                            fn($obat) => ($obat['jumlah'] ?? 0) * ($obat['harga'] ?? 0),
                                        );
                                        $tanggal = \Carbon\Carbon::parse($item->tanggal_pemeriksaan);
                                    @endphp
                                    <tr>
                                        <td>{{ $tanggal->format('d/m/Y') }}</td>
                                        <td>{{ $item->waktu_pemeriksaan->format('H:i') }} WIB</td>
                                        <td>{{ $item->tensi_sistolik }}/{{ $item->tensi_diastolik }} mmHg</td>
                                        <td>{{ $item->suhu ?? '-' }}°C</td>
                                        <td>{{ $item->gejala }}</td>
                                        <td>{{ $item->diagnosa ?? '-' }}</td>
                                        <td>{{ $item->catatan_dokter ?? '-' }}</td>
                                        {{-- <td class="text-end">Rp{{ number_format($item->biaya ?? 0, 0, ',', '.') }}</td>
                                        <td class="text-end">Rp{{ number_format($totalObat, 0, ',', '.') }}</td>
                                        <td class="text-end fw-bold text-primary">Rp{{ number_format($totalObat + ($item->biaya ?? 0), 0, ',', '.') }}</td> --}}
                                        <td>
                                            @if (count($resep))
                                                <ul class="list-unstyled mb-0">
                                                    @foreach ($resep as $r)
                                                        <li>
                                                            <strong>{{ $r['nama_obat'] ?? '-' }}</strong>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <em>-</em>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('pemeriksaan.exportPdf', $item->id) }}" target="_blank"
                                                class="btn btn-sm btn-success">
                                                Cetak
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            @else
                <div class="alert alert-info text-center">
                    Silakan <a href="{{ route('login') }}">login</a> terlebih dahulu untuk melihat riwayat rekam medis Anda.
                </div>
            @endauth
        </div>
    </section>
@endsection
