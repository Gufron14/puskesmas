@extends('frontend.layouts.app')

@section('content')
    <section class="page-section vh-100" id="services">
        <div class="container pt-5 pb-5">
            <div class="mb-5">
                <h3 class="section-heading text-uppercase">Pendaftaran Antrian</h3>
            </div>
            @php
                $isLogin = Auth::check();
            @endphp
            <x-error-validation-message errors="$errors" />
            @if ($antrian && $antrian->status === 'off')
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <p class="mb-0 font-weight-bold">
                        Pendaftaran Antrian Sedang tutup, Coba lagi nanti!
                    </p>
                </div>
            @endif
            @php
                $user = Auth::user();
            @endphp

@php
    $user = Auth::user();
@endphp

<form action="{{ route('daftar.post') }}" method="POST">
    @csrf

    <div class="d-flex gap-3">
        <div class="col-md-6 mb-3">
            <label for="tanggal_antrian" class="form-label">Tanggal Antrian</label>
            <input type="date" id="tanggal_antrian" name="tanggal_antrian" class="form-control" required>
        </div>

        <div class="col-md-6 mb-3">
            <label for="noAntrian" class="form-label">Pilih No Antrian</label>
            <select name="nomor_antrian" id="noAntrian" class="form-control" required disabled>
                <option value="" selected disabled>-- Pilih tanggal terlebih dahulu --</option>
            </select>
            <div class="form-text" id="sisaAntrianInfo">
                <span class="text-muted">Silakan pilih tanggal terlebih dahulu</span>
            </div>
        </div>
    </div>

    <div class="d-flex gap-3 mb-3 flex-wrap">
        <div class="col-md-3 mb-3">
            <label for="nama_pasien" class="form-label">Nama Pasien</label>
            <input type="text" class="form-control" name="nama_pasien"
                value="{{ old('nama_pasien', optional($user)->name) }}" placeholder="Nama lengkap">
        </div>

        <div class="col-md-2 mb-3">
            <label for="nik" class="form-label">NIK</label>
            <input type="text" class="form-control" name="nik"
                value="{{ old('nik', optional($user)->nik) }}" placeholder="16 digit NIK">
        </div>

        <div class="col-md-2 mb-3">
            <label for="usia" class="form-label">Usia</label>
            <input type="number" class="form-control" name="usia" min="1"
                value="{{ old('usia', optional($user)->usia) }}">
        </div>

        <div class="col-md-2 mb-3">
            <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
            <select name="jenis_kelamin" class="form-select">
                <option value="">Pilih</option>
                <option value="Laki-laki" {{ old('jenis_kelamin', optional($user)->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                <option value="Perempuan" {{ old('jenis_kelamin', optional($user)->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
            </select>
        </div>

        <div class="col-md-3 mb-3">
            <label for="telepon" class="form-label">Telepon</label>
            <input type="text" class="form-control" name="telepon"
                value="{{ old('telepon', optional($user)->telepon) }}" placeholder="Contoh: 081234567890">
        </div>
    </div>

    <div class="mb-3">
        <label for="alamat" class="form-label">Alamat</label>
        <textarea class="form-control" name="alamat" rows="3" placeholder="Masukan alamat lengkap">{{ old('alamat', optional($user)->alamat) }}</textarea>
    </div>

    @if ($antrian && $antrian->status === 'on')
        <button type="submit" class="btn btn-success">Simpan</button>
    @else
        <button type="button" class="btn btn-secondary" onclick="showAntrianTutup()">Antrian Ditutup</button>
    @endif
</form>

        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Pendaftaran Berhasil!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#28a745',
                timer: 5000,
                timerProgressBar: true,
                showConfirmButton: true,
                confirmButtonText: 'OK'
            });
        </script>
    @endif
    <script>
        function showAntrianTutup() {
            Swal.fire({
                icon: 'warning',
                title: 'Antrian Ditutup',
                text: 'Pendaftaran antrian hari ini sedang ditutup. Silakan coba lagi nanti.',
                confirmButtonText: 'OK'
            });
        }

        function showLoginWarning() {
            Swal.fire({
                icon: 'warning',
                title: 'Login Diperlukan',
                text: 'Silakan login terlebih dahulu untuk mendaftar antrian.',
                confirmButtonText: 'Login',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('login') }}";
                }
            });
        }
    </script>
@endsection

@push('scripts')
    <!-- jQuery (pastikan hanya sekali di-load dalam layout utama) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap Datepicker CSS & JS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#tanggal_antrian').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true,
                startDate: new Date(), // mencegah pilih tanggal lampau
                daysOfWeekDisabled: [0], // disable hari Minggu
            });

            // Event listener untuk perubahan tanggal
            $('#tanggal_antrian').on('changeDate', function(e) {
                const tanggal = e.format('yyyy-mm-dd');
                loadNomorAntrian(tanggal);
            });

            // Juga handle manual input
            $('#tanggal_antrian').on('blur', function() {
                const tanggal = $(this).val();
                if (tanggal) {
                    loadNomorAntrian(tanggal);
                }
            });
        });

        function loadNomorAntrian(tanggal) {
            const selectAntrian = $('#noAntrian');
            const sisaAntrianInfo = $('#sisaAntrianInfo');
            
            @if(!$user)
                // Jika user belum login, tampilkan pesan dan jangan lakukan AJAX
                selectAntrian.html('<option value="" disabled>Silakan login terlebih dahulu</option>');
                sisaAntrianInfo.html('<span class="text-warning">Silakan login untuk melihat antrian</span>');
                return;
            @endif
            
            // Reset dropdown
            selectAntrian.prop('disabled', true).html('<option value="">Loading...</option>');
            sisaAntrianInfo.html('<span class="text-muted">Loading...</span>');

            // AJAX request
            $.ajax({
                url: '{{ route('get.nomor.antrian') }}',
                method: 'POST',
                data: {
                    tanggal: tanggal,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        selectAntrian.html('<option value="" selected disabled>-- Pilih Nomor Antrian --</option>');
                        
                        if (response.data.length > 0) {
                            response.data.forEach(function(item) {
                                selectAntrian.append(`<option value="${item.nomor}">Antrian Nomor ${item.nomor}</option>`);
                            });
                            selectAntrian.prop('disabled', false);
                            sisaAntrianInfo.html(`<span class="text-success">Sisa antrian: ${response.sisaAntrian}</span>`);
                        } else {
                            selectAntrian.html('<option value="" disabled>Maaf, kuota antrian penuh</option>');
                            sisaAntrianInfo.html('<span class="text-danger">Tidak ada sisa antrian untuk tanggal ini</span>');
                        }
                    } else {
                        selectAntrian.html('<option value="" disabled>Tidak dapat memuat antrian</option>');
                        sisaAntrianInfo.html(`<span class="text-danger">${response.message}</span>`);
                        
                        // Show error message
                        if (response.message.includes('login')) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Login Diperlukan',
                                text: response.message,
                                confirmButtonText: 'Login',
                                confirmButtonColor: '#007bff'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = "{{ route('login') }}";
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message,
                                confirmButtonColor: '#dc3545'
                            });
                        }
                    }
                },
                error: function(xhr) {
                    selectAntrian.html('<option value="" disabled>Error memuat data</option>');
                    sisaAntrianInfo.html('<span class="text-danger">Terjadi kesalahan</span>');
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Terjadi kesalahan saat memuat data antrian',
                        confirmButtonColor: '#dc3545'
                    });
                }
            });
        }
    </script>
@endpush
