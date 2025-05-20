@extends('auth.layouts.app')

@section('content')
    <div class="container-fluid ">
        <div class="row justify-content-center vh-100 d-flex align-items-center">
            <div class="col-md-4">
                <div class="card shadow">
                    <div class="card-body p-5">
                        <form method="POST" action="">
                            @csrf
                            <div class="text-center mb-2">
                                <img src="{{ asset('backend/assets/logo/logo.png') }}" height="80" alt="logo">
                            </div>
                            <h4 class="text-center mb-5">Login</h4>
                            <div class="form-group mb-3">
                                <label class="text-dark font-weight-bold" for="no_telpon">Nomor Telepon</label>
                                <div class="input-group">
                                    <input id="no_telepon" type="text"
                                        class="form-control form-control-lg @error('no_telepon') is-invalid @enderror"
                                        name="no_telepon" value="{{ old('no_telepon') }}"
                                        placeholder="Masukan nomor telepon" required autocomplete="no_telepon" autofocus>
                                    @error('no_telepon')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="input-group-append">
                                        <div class="input-group-text" id="button-addon-date"><span
                                                class="fe fe-phone fe-16"></span></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-5">
                                <label class="text-dark font-weight-bold" for="password">Kata Sandi</label>
                                <div class="input-group">

                                    <input id="password" type="password"
                                        class="form-control form-control-lg @error('password') is-invalid @enderror"
                                        name="password" placeholder="Masukan kata sandi" required
                                        autocomplete="current-password">
                                    @error('password')
                                        <span class="invalid-feedback text-start" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="input-group-append">
                                        <div class="input-group-text" id="button-addon-date"><span
                                                class="fe fe-lock fe-16"></span></div>
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-lg btn-success btn-block font-weight-bold text-white"
                                type="submit">Login</button>
                            <p class="text-center mt-5 font-weight-bold text-dark mb-0">Belum Punya Akun ? <a
                                    href="/register">Register</a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
