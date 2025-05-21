@extends('auth.layouts.app')

@section('content')
    <div class="container-fluid ">
        <div class="row justify-content-center vh-100 d-flex align-items-center">
            <div class="col-md-4">
                <div class="card shadow">
                    <div class="card-body p-5">
                        <form action="{{ url('/register') }}" method="POST">
                            @csrf
                            <div class="text-center mb-2">
                                <img src="{{ asset('backend/assets/logo/logo.png') }}" height="80" alt="logo">
                            </div>
                            <h4 class="text-center mb-5">Register</h4>
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
                            <div class="form-group mb-3">
                                <label class="text-dark font-weight-bold" for="name">Nama Lengkap</label>
                                <div class="input-group">

                                    <input id="name" type="text"
                                        class="form-control form-control-lg @error('name') is-invalid @enderror"
                                        name="name" placeholder="Masukan nama lengkap" value="{{ old('name') }}" required
                                        autocomplete="name" autocomplete="off" autofocus>

                                    <div class="input-group-append">
                                        <div class="input-group-text" id="button-addon-date"><span
                                                class="fe fe-mail fe-16"></span></div>
                                    </div>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <label class="text-dark font-weight-bold" for="no_telpon">Nomor Telepon</label>
                                <div class="input-group">
                                    <input id="telepon" type="text"
                                        class="form-control form-control-lg @error('telepon') is-invalid @enderror"
                                        name="telepon" value="{{ old('telepon') }}"
                                        placeholder="Masukan nomor telepon (Contoh: 081234567890)" required
                                        autocomplete="tel" autofocus pattern="^08[0-9]{8,12}"
                                        title="Nomor harus dimulai dari 08 dan hanya angka">

                                    <div class="input-group-append">
                                        <div class="input-group-text" id="button-addon-date"><span
                                                class="fe fe-phone fe-16"></span></div>
                                    </div>
                                    @error('telepon')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <label class="text-dark font-weight-bold" for="password">Kata Sandi</label>
                                <div class="input-group">

                                    <input id="password" type="password"
                                        class="form-control form-control-lg @error('password') is-invalid @enderror"
                                        name="password" placeholder="Masukan kata sandi" required
                                        autocomplete="current-password">
                                    <div class="input-group-append">
                                        <div class="input-group-text" id="button-addon-date"><span
                                                class="fe fe-lock fe-16"></span></div>
                                    </div>
                                    @error('password')
                                        <span class="invalid-feedback text-start" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="custom-control custom-checkbox mb-5">
                                <input type="checkbox" class="custom-control-input" id="showHidePass">
                                <label class="custom-control-label text-dark" for="showHidePass">Tampilkan Sandi</label>
                            </div>

                            <button class="btn btn-lg btn-success btn-block font-weight-bold text-white"
                                type="submit">Register</button>
                            <p class="text-center mt-5 font-weight-bold text-dark mb-0">Sudah Punya Akun ? <a
                                    href="{{ route('login') }}">Login</a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
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
