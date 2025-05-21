@extends('backend.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row align-items-center justify-content-between mb-4">
            <div class="col">
                <h4 class="font-weight-bold text-dark">Data Laporan Keuangan</h4>
            </div>
            <div class="col-auto">
                <a href="" class="btn btn-success text-white font-weight-bold">Cetak Laporan</a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table datatables" id="dataTable-1">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Pasien</th>
                        <th>Tanggal</th>
                        <th>Harga Obat</th>
                        <th>Biaya Pemeriksaan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
@endsection
