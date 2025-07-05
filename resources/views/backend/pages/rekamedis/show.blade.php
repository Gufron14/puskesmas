@extends('backend.layouts.app')

@section('content')

<div class="row">
    <!-- Kolom Kiri -->
    <div class="col-md-6">
        <!-- Informasi Pasien -->
        <div class="card border-left-primary mb-3">
            <div class="card-body">
                <h6 class="card-title text-primary"><i class="fe fe-user"></i> Informasi Pasien</h6>
                <table class="table table-sm table-borderless">
                    <tr><td><strong>Nama Lengkap</strong></td><td>:</td><td>{{ $rekamedis->user->name ?? '-' }}</td></tr>
                    <tr><td><strong>NIK</strong></td><td>:</td><td>{{ $rekamedis->user->nik ?? '-' }}</td></tr>
                    <tr><td><strong>Jenis Kelamin</strong></td><td>:</td><td>{{ ucfirst($rekamedis->user->jenis_kelamin ?? '-') }}</td></tr>
                    <tr><td><strong>Telepon</strong></td><td>:</td><td>{{ $rekamedis->user->telepon ?? '-' }}</td></tr>
                    <tr><td><strong>Alamat</strong></td><td>:</td><td>{{ $rekamedis->user->alamat ?? '-' }}</td></tr>
                    @if ($rekamedis->user->tanggal_lahir)
                        <tr>
                            <td><strong>Tanggal Lahir</strong></td>
                            <td>:</td>
                            <td>{{ \Carbon\Carbon::parse($rekamedis->user->tanggal_lahir)->format('d/m/Y') }}</td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>

        <!-- Hasil Pemeriksaan -->
        {{-- <div class="card border-left-success mb-3">
            <div class="card-body">
                <h6 class="card-title text-success"><i class="fe fe-clipboard"></i> Hasil Pemeriksaan</h6>
                <p>{{ $rekamedis->hasil_pemeriksaan ?? '-' }}</p>
            </div>
        </div> --}}
                <!-- Diagnosa -->
                @if ($rekamedis->diagnosa)
                <div class="card border-left-warning mb-3">
                    <div class="card-body">
                        <h6 class="card-title text-warning"><i class="fe fe-clipboard"></i> Diagnosa Sementara</h6>
                        <p class="mb-0">{{ $rekamedis->diagnosa }}</p>
                    </div>
                </div>
            @endif

        <!-- Obat yang Diberikan -->
        @if ($rekamedis->obats && $rekamedis->obats->count() > 0)
            <div class="card border-left-info mb-3">
                <div class="card-body">
                    <h6 class="card-title text-info"><i class="fe fe-package"></i> Obat yang Diberikan</h6>
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Nama Obat</th>
                                <th>Jenis</th>
                                <th>Jumlah</th>
                                <th>Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rekamedis->obats as $obat)
                                <tr>
                                    <td>{{ $obat->nama_obat }}</td>
                                    <td>{{ $obat->jenis_obat }}</td>
                                    <td>{{ $obat->pivot->jumlah ?? 1 }}</td>
                                    <td>Rp {{ number_format($obat->harga, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>

    <!-- Kolom Kanan -->
    <div class="col-md-6">
        <!-- Data Pemeriksaan -->
        <div class="card border-left-success mb-3">
            <div class="card-body">
                <h6 class="card-title text-success"><i class="fe fe-activity"></i> Data Pemeriksaan</h6>
                <table class="table table-sm table-borderless">
                    <tr>
                        <td><strong>Tanggal Periksa</strong></td>
                        <td>:</td>
                        <td>{{ $rekamedis->tanggal_pemeriksaan ? \Carbon\Carbon::parse($rekamedis->tanggal_pemeriksaan)->format('d/m/Y') : '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Waktu Periksa</strong></td>
                        <td>:</td>
                        <td>{{ $rekamedis->waktu_pemeriksaan ? \Carbon\Carbon::parse($rekamedis->waktu_pemeriksaan)->format('H:i') : '-' }} WIB</td>
                    </tr>
                    <tr>
                        <td><strong>Tekanan Darah</strong></td>
                        <td>:</td>
                        <td>{{ $rekamedis->tensi_sistolik ?? '-' }}/{{ $rekamedis->tensi_diastolik ?? '-' }} mmHg</td>
                    </tr>
                    <tr>
                        <td><strong>Suhu Tubuh</strong></td>
                        <td>:</td>
                        <td>{{ $rekamedis->suhu ?? '-' }}°C</td>
                    </tr>
                    <tr>
                        <td><strong>Keluhan</strong></td>
                        <td>:</td>
                        <td>{{ $rekamedis->gejala ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Catatan Dokter -->
        @if ($rekamedis->catatan_dokter)
            <div class="card border-left-info mb-3">
                <div class="card-body">
                    <h6 class="card-title text-info"><i class="fe fe-edit-3"></i> Catatan Dokter</h6>
                    <p class="mb-0">{{ $rekamedis->catatan_dokter }}</p>
                </div>
            </div>
        @endif
    </div>
</div>
<!-- Resep Obat Detail -->
<div class="card border-left-danger mt-3">
    <div class="card-body">
        <h6 class="card-title text-danger"><i class="fe fe-package"></i> Resep Obat Detail</h6>
        @if (count($resep ?? []))
            <div class="table-responsive">
                <table class="table table-sm table-striped">
                    <thead class="thead-light">
                        <tr>
                            <th>Nama Obat</th>
                            <th>Jenis Obat</th>
                            <th>Jumlah</th>
                            <th>Harga Satuan</th>
                            <th>Aturan Minum</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rekamedis->resep_formatted as $r)
                            <tr>
                                <td><strong>{{ $r['nama_obat'] ?? ($r['nama'] ?? '-') }}</strong></td>
                                <td><span class="badge badge-secondary">{{ $r['jenis_obat'] ?? 'Tidak Diketahui' }}</span></td>
                                <td><span class="badge badge-primary">{{ $r['jumlah'] ?? 0 }} pcs</span></td>
                                <td>Rp{{ number_format($r['harga'] ?? 0, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge badge-{{ ($r['keterangan_makan'] ?? '') == 'sesudah_makan' ? 'success' : 'warning' }}">
                                        {{ $r['keterangan_display'] ?? ucwords(str_replace('_', ' ', $r['keterangan_makan'] ?? '-')) }}
                                    </span>
                                </td>
                                <td><strong>Rp{{ number_format(($r['jumlah'] ?? 0) * ($r['harga'] ?? 0), 0, ',', '.') }}</strong></td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="thead-light">
                        <tr>
                            <th colspan="5" class="text-right">Total Obat:</th>
                            <th>Rp{{ number_format($totalObat, 0, ',', '.') }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @else
            <div class="alert alert-info">
                <i class="fe fe-info"></i> Tidak ada resep obat untuk pemeriksaan ini
            </div>
        @endif
    </div>
</div>

<!-- Ringkasan Biaya -->
<div class="card border-left-dark mt-4">
    <div class="card-body">
        <h6 class="card-title text-dark"><i class="fe fe-dollar-sign"></i> Ringkasan Biaya</h6>
        <div class="table-responsive">
            <table class="table table-sm table-borderless">
                <tr>
                    <td><strong>Biaya Pemeriksaan</strong></td>
                    <td>:</td>
                    <td class="text-right">Rp{{ number_format($rekamedis->biaya ?? 0, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td><strong>Total Biaya Obat</strong></td>
                    <td>:</td>
                    <td class="text-right">Rp{{ number_format($totalObat, 0, ',', '.') }}</td>
                </tr>
                <tr class="border-top">
                    <td><strong class="text-primary">TOTAL KESELURUHAN</strong></td>
                    <td>:</td>
                    <td class="text-right"><strong class="text-primary h5">Rp{{ number_format($totalObat + ($rekamedis->biaya ?? 0), 0, ',', '.') }}</strong></td>
                </tr>
            </table>
        </div>
    </div>
</div>

@endsection
