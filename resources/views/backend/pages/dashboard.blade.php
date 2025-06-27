@extends('backend.layouts.app')

@section('content')
    <div class="container-fluid">
        <h4 class="font-weight-bold mb-4">Dashboard</h4>
        <div class="row">
            <div class="col-md-3">
                <div class="card rounded-lg shadow bg-primary">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <h1 class="text-white">{{ $pasien }}</h1>
                                <h6 class="text-white">Data Pasien</h6>
                            </div>
                            <div class="col-auto">
                                <span class="fe fe-32 fe-folder-plus text-white mb-0"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card rounded-lg shadow bg-warning">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <h1 class="text-white">{{ $antrian }}</h1>
                                <h6 class="text-white">Data Antrian</h6>
                            </div>
                            <div class="col-auto">
                                <span class="fe fe-32 fe-folder-plus text-white mb-0"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card rounded-lg shadow bg-danger">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <h1 class="text-white">{{ $rekamedis }}</h1>
                                <h6 class="text-white">Rekam Medis</h6>
                            </div>
                            <div class="col-auto">
                                <span class="fe fe-32 fe-inbox text-white mb-0"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card rounded-lg shadow bg-success">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <h1 class="text-white">Rp{{ number_format($totaluang, 0, ',', '.') }}</h1>
                                <h6 class="text-white">Total Keuangan</h6>
                            </div>
                            <div class="col-auto">
                                <span class="fe fe-32 fe-credit-card text-white mb-0"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
