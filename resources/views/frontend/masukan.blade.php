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
            <form action="">
                <div class="mb-3">
                    <label for="alamat" class="form-label">1. Masukan Saran anda mengenai Pelayanan SIMRMPUS</label>
                    <textarea class="form-control" id="alamat" rows="6" placeholder="" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="alamat" class="form-label">2. Masukan Keluhan anda mengenai pelayanan SIMRMPUS</label>
                    <textarea class="form-control" id="alamat" rows="6" placeholder="" required></textarea>
                </div>
            </form>
        </div>
    </section>
@endsection
