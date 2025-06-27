@extends('backend.layouts.app')

@section('content')
    <div class="container-fluid">
        <h4 class="font-weight-bold text-dark mb-4">Pendaftaran Pasien Baru</h4>

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

        <form action="{{ route('pasien.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label text-dark font-weight-bold">Nama Pasien</label>
                <input type="text" class="form-control" name="name" value="{{ old('name') }}"
                    placeholder="Masukan nama lengkap" required>
            </div>

            <div class="mb-3">
                <label class="font-weight-bold" for="jenis_kelamin">Jenis Kelamin</label>
                <div class="d-flex align-items-center" style="gap: 10px">
                    <div class="custom-control custom-radio">
                        <input type="radio" id="customRadio1" name="jenis_kelamin" class="custom-control-input"
                            value="Laki-laki" checked>
                        <label class="custom-control-label" for="customRadio1">Laki-Laki</label>
                    </div>
                    <div class="custom-control custom-radio">
                        <input type="radio" id="customRadio2" name="jenis_kelamin" class="custom-control-input"
                            value="Perempuan">
                        <label class="custom-control-label" for="customRadio2">Perempuan</label>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="usia" class="form-label text-dark font-weight-bold">Usia</label>
                <input type="number" class="form-control" min="1" name="usia" value="{{ old('usia') }}"
                    required>
            </div>
            <div class="mb-3">
                <label for="nik" class="form-label text-dark font-weight-bold">NIK</label>
                <input type="text" class="form-control" name="nik" value="{{ old('nik') }}" maxlength="16"
                    placeholder="Masukan 16 digit NIK" required>
            </div>

            <div class="mb-3">
                <label for="alamat" class="form-label text-dark font-weight-bold">Alamat</label>
                <textarea class="form-control" name="alamat" rows="6" placeholder="Masukan alamat lengkap">{{ old('alamat') }}</textarea>
            </div>

            <div class="mb-3">
                <label for="telepon" class="form-label text-dark font-weight-bold">No Telepon</label>
                <input id="telepon" type="text" class="form-control" name="telepon" value="{{ old('telepon') }}"
                    placeholder="Masukan nomor telepon aktif (Contoh: 081234567890)" required autocomplete="tel" autofocus
                    pattern="^08[0-9]{8,12}" title="Nomor harus dimulai dari 08 dan hanya angka">
            </div>
            <div class="mb-3">
                <label class="text-dark font-weight-bold" for="password">Kata Sandi</label>
                <div class="input-group">
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                        name="password" placeholder="Masukan kata sandi" required autocomplete="current-password">

                    @error('password')
                        <span class="invalid-feedback text-start" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="custom-control custom-checkbox mt-2">
                    <input type="checkbox" class="custom-control-input" id="showHidePass">
                    <label class="custom-control-label text-dark" for="showHidePass">Tampilkan
                        Sandi</label>
                </div>
            </div>
            <div class="float-right">
                <a href="{{ route('pasien.index') }}" class="btn btn-danger">Kembali</a>
                <button type="submit" class="btn btn-success text-white">Simpan</button>
            </div>
        </form>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkbox = document.getElementById('showHidePass');
            const passwordInput = document.getElementById('password');

            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    passwordInput.type = 'text';
                } else {
                    passwordInput.type = 'password';
                }
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('telepon');
            input.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, ''); // hanya angka
                if (!this.value.startsWith('08')) {
                    this.setCustomValidity("Nomor telepon harus dimulai dari 08");
                } else {
                    this.setCustomValidity("");
                }
            });
        });
    </script>
@endsection
