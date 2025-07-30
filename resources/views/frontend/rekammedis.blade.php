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
                        <table class="table table-bordered table-sm align-middle">
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
                                    <th>Aksi</th>
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
                                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#exampleModal">
                                                Lihat Resep
                                            </button>
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

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Resep Obat</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
@if (count($resep))
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Obat</th>
                <th>Aturan Minum</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($resep as $index => $r)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $r['nama_obat'] ?? '-' }}</td>
                    <td>{{ $r['keterangan_makan'] ?? '-' }}</td>
                    <td>
                        {{ $r['jumlah'] ?? 0 }}
                        @switch($r['jenis_obat'])
                            @case('Serbuk')
                                Kantong
                                @break
                            @case('Kapsul')
                                Kapsul
                                @break
                            @case('Tablet')
                                Tablet
                                @break
                            @case('Sirup')
                            @case('Obat Tetes')
                                Botol
                                @break
                            @case('Salep')
                                Pcs
                                @break
                            @default
                                -
                        @endswitch
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <em>-</em>
@endif

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        {{-- <button type="button" class="btn btn-primary">Tutup</button> --}}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
