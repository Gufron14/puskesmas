@extends('frontend.layouts.app')

@section('content')
    <!-- Masthead-->
    <header class="masthead">
        <div class="container">
            <div class="masthead-heading text-uppercase">Selamat Datang di PUSTU (PUSKESMAS PEMBANTU )
                Desa Wakap</div>
            <div class="masthead-subheading">Jl.Patmah, Pustu desa wakap, F3GM+PPM, Wakap, Kec. Bantarkalong, Kabupaten
                Tasikmalaya, Jawa Barat
                46187 </div>
        </div>
    </header>
    <!-- Services-->
    <section class="page-section bg-cs-success" id="services">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-heading text-uppercase">Tentang Kami</h2>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <img src="{{ asset('frontend/assets/img/bg-tentang.jpg') }}" alt="" class="img-fluid mb-3">
                    <div class="card bg-success">
                        <div class="card-body">
                            <h4 class="text-center text-white">Jam Pelayanan</h4>
                            <div class="d-flex justify-content-center gap-3">
                                <h5 class="text-white">Senin-Jum’at 07.30-15.00</h5>
                                <h5 class="text-white">|</h5>
                                <h5 class="text-white">Sabtu-Minggu Libur</h5>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <h4 class="my-3 text-center">UPTD PUSTU Desa Wakap</h4>
                    <p>Puskesmas Pembantu (Pustu) Desa Wakap merupakan unit layanan kesehatan tingkat
                        pertama yang hadir untuk mendekatkan akses pelayanan kesehatan kepada masyarakat Desa Wakap dan
                        sekitarnya. Sebagai bagian dari jaringan pelayanan Puskesmas induk, Pustu Desa Wakap berkomitmen
                        memberikan pelayanan kesehatan dasar yang cepat, mudah, dan berkualitas bagi seluruh lapisan
                        masyarakat.
                    </p>
                    <p>

                        Kami melayani berbagai kebutuhan kesehatan seperti pemeriksaan umum, imunisasi, pelayanan ibu
                        dan anak, serta penanganan kasus penyakit ringan. Dengan dukungan tenaga medis yang profesional
                        dan ramah, serta fasilitas yang terus ditingkatkan, Pustu Desa Wakap berperan aktif dalam
                        meningkatkan derajat kesehatan masyarakat desa secara menyeluruh.

                    </p>
                    <p>
                        Pustu Desa Wakap tidak hanya menjadi tempat pengobatan, tetapi juga pusat edukasi dan pencegahan
                        penyakit melalui berbagai kegiatan promotif dan preventif. Kami percaya bahwa pelayanan
                        kesehatan yang baik dimulai dari kedekatan dan kepercayaan masyarakat terhadap tenaga kesehatan.

                    </p>
                </div>
            </div>
        </div>
    </section>
    <!-- Portfolio Grid-->
    <section class="page-section bg-white" id="portfolio">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-heading text-uppercase">Saran & Keluhan</h2>
            </div>
            <div class="row">
                {{-- Kolom Masukan --}}
                <div class="col-md-6">
                    @php
                        $hanyaMasukan = $masukans->filter(fn($m) => $m->masukan);
                    @endphp

                    @forelse ($hanyaMasukan as $masukan)
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="d-flex gap-4 align-items-center">
                                    <div class="team-member">
                                        <img class="mx-auto rounded-circle"
                                            src="{{ $masukan->user->foto ? asset('storage/' . $masukan->user->foto) : asset('frontend/assets/img/team/1.jpg') }}"
                                            alt="Foto Profil" width="80" height="80">
                                    </div>
                                    <div class="text-content">
                                        <h5>{{ $masukan->user->name }}</h5>
                                        <p class="mb-0">{{ $masukan->masukan }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-center">Belum ada masukan.</p>
                    @endforelse
                </div>

                {{-- Kolom Keluhan --}}
                <div class="col-md-6">
                    @php
                        $hanyaKeluhan = $masukans->filter(fn($m) => $m->keluhan);
                    @endphp

                    @forelse ($hanyaKeluhan as $masukan)
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="d-flex gap-4 align-items-center">
                                    <div class="team-member">
                                        <img class="mx-auto rounded-circle"
                                            src="{{ $masukan->user->foto ? asset('storage/' . $masukan->user->foto) : asset('frontend/assets/img/team/1.jpg') }}"
                                            alt="Foto Profil" width="80" height="80">
                                    </div>
                                    <div class="text-content">
                                        <h5>{{ $masukan->user->name }}</h5>
                                        <p class="mb-0">{{ $masukan->keluhan }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-center">Belum ada keluhan.</p>
                    @endforelse
                </div>
            </div>


        </div>
    </section>
    <!-- About-->
    <section class="page-section bg-success" id="about">
        <div class="container">
            <div class="text-center">
                <h2 class="section-heading text-uppercase text-white">PUSTU DESA WAKAP </h2>
                <h4 class="text-white mb-5">Sehatmu Bahagiaku</h4>
                <h4 class="text-white">Terwujudnya pusat kesehatan yang berkualitas dan mandiri</h4>
            </div>
        </div>
    </section>
@endsection
