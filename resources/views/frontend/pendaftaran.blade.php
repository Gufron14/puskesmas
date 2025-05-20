@extends('frontend.layouts.app')

@section('content')
    <section class="page-section vh-100" id="services">
        <div class="container pt-5 pb-5">
            <div class="mb-5">
                <h2 class="section-heading text-uppercase">Pendaftaran Pasien Baru</h2>
            </div>
            <form action="">
                <div class="mb-3">
                    <label class="form-label" for="name">Nama Pasien</label>
                    <input type="text" name="name" class="form-control" placeholder="Masukan Nama Lengkap" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Jenis Kelamin</label>
                    <div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="jenisKelamin" id="lakiLaki"
                                value="Laki-laki" required>
                            <label class="form-check-label" for="lakiLaki">Laki-Laki</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="jenisKelamin" id="perempuan"
                                value="Perempuan">
                            <label class="form-check-label" for="perempuan">Perempuan</label>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="usia" class="form-label">Usia</label>
                    <input type="number" class="form-control" id="usia" placeholder="Masukkan usia anda" required>
                </div>

                <div class="mb-3">
                    <label for="noAntrian" class="form-label">No Antrian</label>
                    <input type="text" class="form-control" id="noAntrian" value="4" readonly>
                    <div class="mt-1">
                        <small class="text-muted">08:00-08:20</small>
                    </div>
                    <div class="error-text">Sisa no antrian 3</div>
                </div>

                <div class="mb-3">
                    <label for="alamat" class="form-label">Alamat Lengkap</label>
                    <textarea class="form-control" id="alamat" rows="3" placeholder="Masukkan alamat lengkap anda" required></textarea>
                </div>

                <div class="mb-3">
                    <label for="noTelepon" class="form-label">No Telepon</label>
                    <input type="tel" class="form-control" id="noTelepon"
                        placeholder="Masukkan nomor telepon aktif anda" required>
                </div>

                <div class="mb-3">
                    <label for="keluhan" class="form-label">Keluhan</label>
                    <textarea class="form-control" id="keluhan" rows="3"
                        placeholder="Tuliskan gejala atau keluhan yang dirasakan anda dengan jelas" required></textarea>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
            </form>
        </div>
    </section>
@endsection
