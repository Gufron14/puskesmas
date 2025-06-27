@extends('frontend.layouts.app')

@section('content')
    <section class="page-section vh-100" id="services">
        <div class="container pt-5 pb-5">
            <div class="mb-5">
                <h2 class="section-heading text-uppercase">Profil</h2>
            </div>

            <form action="{{ route('profil.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <center>
                    <div class="avatar avatar-xl mt-4">
                        <img id="preview"
                            src="{{ Auth::user()->foto ? asset('storage/' . Auth::user()->foto) : asset('backend/assets/avatars/Profiledefault.png') }}"
                            alt="Preview Image" class="avatar-img rounded-circle mb-2"
                            style="display: block; width:200px; height:200px;">
                    </div>

                    <div class="form-group">
                        <p class="text-muted"><small>Ukuran gambar maksimum: 2MB. Format gambar: JPG, JPEG, PNG.</small></p>
                        <input type="file" class="form-control-file" id="imageInput" name="foto" accept="image/*"
                            onchange="previewImage()">
                    </div>
                </center>

                <div class="mb-3">
                    <label class="form-label" for="name">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control" placeholder="Masukkan Nama Lengkap"
                        value="{{ Auth::user()->name }}" required>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="font-weight-bold text-dark">Jenis Kelamin</label>
                            <div class="d-flex align-items-center" style="gap: 10px">
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="customRadio1" name="jenis_kelamin"
                                        class="custom-control-input" value="Laki-laki"
                                        {{ Auth::user()->jenis_kelamin == 'Laki-laki' ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="customRadio1">Laki-Laki</label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="customRadio2" name="jenis_kelamin"
                                        class="custom-control-input" value="Perempuan"
                                        {{ Auth::user()->jenis_kelamin == 'Perempuan' ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="customRadio2">Perempuan</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-dark font-weight-bold">Usia</label>
                            <input type="number" class="form-control" min="1" name="usia"
                                value="{{ Auth::user()->usia }}" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-dark font-weight-bold">NIK</label>
                            <input type="text" class="form-control" name="nik" value="{{ Auth::user()->nik }}"
                                placeholder="Masukkan 16 digit NIK" maxlength="16" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-dark font-weight-bold">Alamat</label>
                            <textarea class="form-control" name="alamat" rows="4" placeholder="Masukkan alamat lengkap" required>{{ Auth::user()->alamat }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="row mb-5">
                    <div class="form-group mb-3 col-md-6">
                        <label class="text-dark font-weight-bold">Nomor Telepon</label>
                        <div class="input-group">
                            <input id="telepon" type="text" class="form-control @error('telepon') is-invalid @enderror"
                                name="telepon" value="{{ Auth::user()->telepon }}" placeholder="Contoh: 081234567890"
                                required pattern="^08[0-9]{8,12}" title="Nomor harus dimulai dari 08 dan hanya angka">

                            @error('telepon')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group mb-3 col-md-6">
                        <label class="text-dark font-weight-bold">Kata Sandi <small class="text-danger">*(Kosongkan jika tidak ingin mengganti
                                sandi)</small></label>
                        <div class="input-group">
                            <input id="password" type="password"
                                class="form-control" name="password"
                                placeholder="Masukkan kata sandi baru" >


                        </div>
                        <div class="custom-control custom-checkbox mt-3">
                            <input type="checkbox" class="custom-control-input" id="showHidePass"
                                onclick="togglePassword()">
                            <label class="custom-control-label text-dark" for="showHidePass">Tampilkan Sandi</label>
                        </div>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>

        </div>
    </section>
    <script>
        function previewImage() {
            var input = document.getElementById('imageInput');
            var preview = document.getElementById('preview');

            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#28a745'
            });
        </script>
    @endif
    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Pmberitahuan!',
                text: '{{ session('error') }}',
                confirmButtonColor: '#28a745'
            });
        </script>
    @endif
@endsection
