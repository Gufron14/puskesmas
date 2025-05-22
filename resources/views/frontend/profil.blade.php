@extends('frontend.layouts.app')

@section('content')
    <section class="page-section vh-100" id="services">
        <div class="container pt-5 pb-5">
            <div class="mb-5">
                <h2 class="section-heading text-uppercase">Profil</h2>
            </div>
            <form action="">
                <center>
                    {{-- @if (Auth::user()->foto) --}}
                    <div class="avatar avatar-xl mt-4">
                        <img id="preview" src="{{ asset('backend/assets/avatars/Profiledefault.png') }}" alt="Preview Image"
                            class="avatar-img rounded-circle mb-2" style="display: block;width:200px;height:200px;">
                    </div>
                    {{-- @else
                        <h5 class="text-muted mt-4 mb-2">Belum Ada foto</h5>
                    @endif --}}
                    <div class="form-group">
                        <p class="text-muted"><small>Ukuran gambar maksimum: 2MB. Format gambar yang
                                diizinkan: JPG, JPEG, PNG.</small></p>
                        <input type="file" class="form-control-file" id="imageInput" name="foto" accept="image/*"
                            onchange="previewImage()" value="{{ asset('backend/assets/avatars/Profiledefault.png') }}">
                    </div>
                </center>
                <div class="mb-3">
                    <label class="form-label" for="name">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control" placeholder="Masukan Nama Lengkap" value="{{ Auth::user()->name }}" required>
                </div>

                <div class="mb-3">
                    <label for="noTelepon" class="form-label">No Telepon</label>
                    <input type="tel" class="form-control" id="noTelepon" value="{{ Auth::user()->telepon }}"
                        placeholder="Masukkan nomor telepon aktif anda" required>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-success">Submit</button>
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
@endsection
