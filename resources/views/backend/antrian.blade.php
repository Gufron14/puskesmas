@extends('backend.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row align-items-center justify-content-between mb-4">
            <div class="col">
                <h4 class="font-weight-bold text-dark">Antrian</h4>
            </div>
        </div>
        {{-- Error Validation --}}
        <x-error-validation-message errors="$errors" />

        {{-- Alert Message --}}
        @if (session()->has('success'))
            <div class="row">
                <div class="col-md-12">
                    <x-success-message action="{{ session()->get('success') }}" />
                </div>
            </div>
        @endif
        <form action="{{ route('pengaturan.antrian.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <label for="">Jumlah Antrian</label>
                    <input type="number" name="jumlah" class="form-control" value="{{ $antrian->jumlah ?? '' }}" required>
                </div>
                <div class="col-md-6">
                    <label for="">Status</label>
                    <div class="d-flex align-items-center" style="gap: 10px">
                        <div class="custom-control custom-radio">
                            <input type="radio" id="customRadio1" name="status" class="custom-control-input"
                                value="on" {{ ($antrian->status ?? '') == 'on' ? 'checked' : '' }}>
                            <label class="custom-control-label" for="customRadio1">Buka Antrian</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input type="radio" id="customRadio2" name="status" class="custom-control-input"
                                value="off" {{ ($antrian->status ?? '') == 'off' ? 'checked' : '' }}>
                            <label class="custom-control-label" for="customRadio2">Tutup Antrian</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-end mt-4">
                <button type="submit" class="btn btn-success text-white">Simpan</button>
            </div>
        </form>

    </div>
@endsection
