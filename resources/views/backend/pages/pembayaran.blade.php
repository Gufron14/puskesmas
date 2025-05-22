@extends('backend.layouts.app')

@section('content')
    <div class="container-fluid">
        <h4 class="font-weight-bold mb-4">Pembayaran dan Tebus Obat</h4>
        <div class="card shadow mb-3">
            <div class="card-body">
                @if ($pemeriksaan)
                    <h5>Biaya Pemeriksaan</h5>
                    <div class="d-flex" style="gap: 10px">
                        <h6>Dr {{ $pemeriksaan->dokter->nama ?? '-' }}</h6>
                        <h6>Rp {{ number_format($pemeriksaan->biaya, 0, ',', '.') }}</h6>
                    </div>
                    <h5>Tebus Obat</h5>
                    @forelse ($resep as $item)
                        <div class="d-flex" style="gap: 10px">
                            <h6>{{ $item['nama'] }}</h6>
                            <h6>{{ $item['jumlah'] }}x</h6>
                            <h6>Rp {{ number_format($item['harga'], 0, ',', '.') }}</h6>
                        </div>
                    @empty
                        <p>Tidak ada resep.</p>
                    @endforelse
                    <h4 class="my-3">Rincian Pembayaran</h4>
                    <table>
                        <tr>
                            <td>
                                <h5>Biaya Pemeriksaan</h5>
                            </td>
                            <td></td>
                            <td>
                                <h5>Rp {{ number_format($pemeriksaan->biaya, 0, ',', '.') }}</h5>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <h5>Tebus Obat</h5>
                            </td>
                            <td></td>
                            <td>
                                <h5>Rp {{ number_format($totalObat, 0, ',', '.') }}</h5>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <h5>Jumlah Bayar</h5>
                            </td>
                            <td></td>
                            <td>
                                <h5>Rp {{ number_format($pemeriksaan->biaya + $totalObat, 0, ',', '.') }}</h5>
                            </td>
                        </tr>
                    </table>
                @else
                    <p class="text-muted">Tidak ada data pemeriksaan yang dipilih.</p>
                @endif


                <div class="d-flex my-3 align-items-center" style="gap:50px">
                    <h6>Payment Method</h6>
                    <h6 class="text-primary">See all</h6>
                </div>

                <div class="card mb-2">
                    <div class="card-body d-flex align-items-center" style="gap: 10px">
                        <h5 class="fe fe-credit-card "></h5>
                        <h5>Dana</h5>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-body d-flex align-items-center" style="gap: 10px">
                        <h5 class="fe fe-credit-card "></h5>
                        <h5>Cash</h5>
                    </div>
                </div>

                <form action="" method="POST">
                    @csrf
                    <input type="hidden" name="pemeriksaan_id" value="{{ $pemeriksaan->id ?? 0 }}">
                    <input type="hidden" name="total" value="{{ $pemeriksaan->biaya ?? 0 + $totalObat ?? 0 }}">
                    <button type="submit" class="btn btn-success w-100">Bayar Sekarang</button>
                </form>
            </div>
        @endsection
