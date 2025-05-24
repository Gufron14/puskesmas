@extends('frontend.layouts.app')

@section('content')
    <!-- Masthead-->
    <header class="masthead">
        <div class="container">
            <div class="masthead-heading text-uppercase">Saran dan keluhan</div>
            <div class="masthead-subheading">Silahkan sampikan saran dan keluhan anda
                atas pelayanan SIMRMPUS</div>
        </div>
    </header>
    <!-- Services-->
    <section class="page-section bg-white" id="services">
        <div class="container">
            <form id="masukanForm" action="{{ route('masukan.post') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="saran" class="form-label">1. Masukan Saran anda mengenai Pelayanan SIMRMPUS</label>
                    <textarea class="form-control" id="saran" name="masukan" rows="6" placeholder=""></textarea>
                </div>
                <div class="mb-3">
                    <label for="keluhan" class="form-label">2. Masukan Keluhan anda mengenai pelayanan SIMRMPUS</label>
                    <textarea class="form-control" id="keluhan" name="keluhan" rows="6" placeholder=""></textarea>
                </div>
                <div class="float-end">
                    <button class="btn btn-success px-4" type="submit">Kirim</button>
                </div>
            </form>


        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>

        document.getElementById('masukanForm').addEventListener('submit', function(e) {
            const masukan = document.getElementById('saran').value.trim();
            const keluhan = document.getElementById('keluhan').value.trim();

            if (masukan === '' && keluhan === '') {
                e.preventDefault(); // mencegah pengiriman
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian!',
                    text: 'Harap isi minimal salah satu dari masukan atau keluhan.',
                    confirmButtonColor: '#28a745'
                });
            }
        });
    </script>

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
@endsection
