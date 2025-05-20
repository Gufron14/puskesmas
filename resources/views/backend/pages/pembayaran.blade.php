@extends('backend.layouts.app')

@section('content')
    <div class="container-fluid">
        <h4 class="font-weight-bold mb-4">Pembayaran dan Tebus Obat</h4>
        <div class="card shadow">
            <div class="card-body">
                <h5>Biaya Pemeriksaan</h5>
                <div class="d-flex" style="gap: 10px">
                    <h6>Dr Asep Mursyad</h6>
                    <h6>Rp 40.000</h6>
                </div>
                <h5>Tebus Obat</h5>
                <div class="d-flex" style="gap: 10px">
                    <h6>Paracetamol</h6>
                    <h6>1x</h6>
                    <h6>Rp 40.000</h6>
                </div>
            </div>
        </div>
        <h4 class="my-3">RIncian Pembayaran</h4>
        <table>
            <tr>
                <td><h5>Biaya Pemeriksaan</h5></td>
                <td></td>
                <td><h5>Rp 40.000</h5></td>
            </tr>
            <tr>
                <td><h5>Tebus Obat</h5></td>
                <td></td>
                <td><h5>Rp 40.000</h5></td>
            </tr>
            <tr>
                <td><h5>Jumlah Bayar</h5></td>
                <td></td>
                <td><h5>Rp 50.000</h5></td>
            </tr>
        </table>
        <div class="d-flex my-3 align-items-center" style="gap:50px">
            <h6>Payment Method</h6>
            <h6 class="text-primary">See all</h6>
        </div>
        <div class="card">
            <div class="card-body d-flex align-items-center" style="gap: 10px">
                <h5 class="fe fe-credit-card "></h5>
                <h5>Dana</h5>
            </div>
        </div>
        <div class="card">
            <div class="card-body d-flex align-items-center" style="gap: 10px">
                <h5 class="fe fe-credit-card "></h5>
                <h5>Cash</h5>
            </div>
        </div>

    </div>
@endsection
