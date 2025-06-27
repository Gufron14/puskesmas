@extends('backend.layouts.app')

@section('content')
    <div class="container-fluid">
        <h4 class="font-weight-bold text-dark mb-4">Daftar Antrian</h4>

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

        <form action="{{ route('antrian.store') }}" method="POST">
            @csrf
            <div class="form-group mb-3">
                <label for="simple-select2">Pilih Pasien</label>
                <select class="form-control select2" name="user_id" id="simple-select2">
                    <optgroup>
                        @foreach ($pasiens as $pasien)
                            <option value="{{ $pasien->id }}">{{ $pasien->name }} -
                                {{ $pasien->nik }}</option>
                        @endforeach
                    </optgroup>
                </select>
            </div>
            <div class="mb-3">
                <label for="noAntrian" class="form-label">Pilih No Antrian</label>
                <select name="nomor_antrian" id="noAntrian" class="form-control" required>
                    @if ($sisaAntrian > 0)
                        <option value="" selected disabled>-- Pilih Nomor Antrian --</option>
                        @foreach ($nomorWaktu as $item)
                            <option value="{{ $item['nomor'] }}">
                                Antrian Nomor {{ $item['nomor'] }}
                            </option>
                        @endforeach
                    @else
                        <option value="">Maaf, kuota antrian hari ini penuh.</option>
                    @endif
                </select>
                <div class="form-text text-danger">
                    @if ($sisaAntrian > 0)
                        Sisa antrian hari ini: {{ $sisaAntrian }}
                    @else
                        Tidak ada sisa antrian untuk hari ini.
                    @endif
                </div>
            </div>
            <div class="float-right">
                <a href="{{ route('antrian.index') }}" class="btn btn-danger">Kembali</a>
                <button type="submit" class="btn btn-success text-white">Simpan</button>
            </div>
        </form>
    </div>
@endsection
