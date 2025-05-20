@extends('backend.layouts.app')

@section('content')
    <div class="container-fluid">
        <h4 class="font-weight-bold mb-4">Dashboard</h4>
        <div class="row">
            <div class="col-md-3">
                <div class="card rounded-lg shadow bg-primary">
                    <div class="card-body">
                        <h1>100</h1>
                        <h6>Data Dokter</h6>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card rounded-lg shadow bg-warning">
                    <div class="card-body">
                        <h1>100</h1>
                        <h6>Data Pasien</h6>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card rounded-lg shadow bg-danger">
                    <div class="card-body">
                        <h1>100</h1>
                        <h6>Rekam Medis</h6>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card rounded-lg shadow bg-success">
                    <div class="card-body">
                        <h1>100</h1>
                        <h6>Total Keuangan</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
