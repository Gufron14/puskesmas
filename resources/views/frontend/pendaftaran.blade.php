@extends('frontend.layouts.app')

@section('content')
    <section class="page-section vh-100" id="services">
        <div class="container pt-5 pb-5">
            <div class="mb-5">
                <h2 class="section-heading text-uppercase">Pendaftaran Pasien Baru</h2>
            </div>
            <form action="{{ route('daftar.post') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="nama_pasien" class="form-label text-dark font-weight-bold">Nama Pasien</label>
                    <input type="text" class="form-control" name="nama_pasien" value="{{ old('nama_pasien') }}"
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
                    <label for="noAntrian" class="form-label">Pilih No Antrian</label>
                    <select name="nomor_antrian" id="noAntrian" class="form-control" required>
                        @if ($sisaAntrian > 0)
                            <option value="" selected disabled>-- Pilih Nomor Antrian --</option>
                            @foreach ($nomorWaktu as $item)
                                <option value="{{ $item['nomor'] }}">
                                    Antrian Nomor {{ $item['nomor'] }} ({{ $item['waktu'] }})
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


                <div class="mb-3">
                    <label for="nik" class="form-label text-dark font-weight-bold">NIK</label>
                    <input type="text" class="form-control" name="nik" value="{{ old('nik') }}"
                        placeholder="Masukan 16 digit NIK" required>
                </div>

                <div class="mb-3">
                    <label for="alamat" class="form-label text-dark font-weight-bold">Alamat</label>
                    <textarea class="form-control" name="alamat" rows="6" placeholder="Masukan alamat lengkap">{{ old('alamat') }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="telepon" class="form-label text-dark font-weight-bold">No Telepon</label>
                    <input id="telepon" type="text" class="form-control" name="telepon" value="{{ old('telepon') }}"
                        placeholder="Masukan nomor telepon aktif (Contoh: 081234567890)" required autocomplete="tel"
                        autofocus pattern="^08[0-9]{8,12}" title="Nomor harus dimulai dari 08 dan hanya angka">
                </div>
                <div class="float-right">
                    <a href="{{ route('pasien.index') }}" class="btn btn-danger">Kembali</a>
                    <button type="submit" class="btn btn-success text-white">Simpan</button>
                </div>
            </form>
        </div>
    </section>
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
@endsection
