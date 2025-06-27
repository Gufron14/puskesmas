@extends('backend.layouts.app')

@section('content')
    <div class="container-fluid">
        <h4 class="font-weight-bold mb-4">Edit Rekam Medis</h4>

        <form action="{{ route('rekamedis.update', $pemeriksaan->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group mb-4">
                <label for="simple-select2">Pilih Pasien</label>
                <select class="form-control select2" name="pasien_id" id="simple-select2">
                    <optgroup>
                        @foreach ($pasiens as $pasien)
                            <option value="{{ $pasien->id }}"
                                {{ $pasien->id == $pemeriksaan->pasien_id ? 'selected' : '' }}>
                                {{ $pasien->user->name }} - {{ $pasien->user->nik }}
                            </option>
                        @endforeach
                    </optgroup>
                </select>
            </div>

            <div class="form-group mb-4">
                <label class="font-weight-bold" for="tanggal_pemeriksaan">Tanggal Pemeriksaan</label>
                <input type="text" id="tanggal_pemeriksaan" name="tanggal_pemeriksaan" class="form-control" value="{{ \Carbon\Carbon::parse($pemeriksaan->tanggal_pemeriksaan)->format('Y-m-d') }}" placeholder="Pilih tanggal pemeriksaan" autocomplete="off" required readonly>
            </div>

            <div class="mb-3">
                <label for="tensi_darah" class="form-label">Tensi Darah</label>
                <div class="d-flex align-items-center" style="gap: 5px;">
                    <input type="number" id="sistolik" name="sistolik" class="form-control" min="1"
                        style="width: 80px;" maxlength="3" placeholder="" pattern="\d{2,3}" required
                        value="{{ $pemeriksaan->tensi_sistolik }}">
                    <span>/</span>
                    <input type="number" id="diastolik" name="diastolik" class="form-control" min="1"
                        style="width: 80px;" maxlength="3" placeholder="" pattern="\d{2,3}" required
                        value="{{ $pemeriksaan->tensi_diastolik }}">
                </div>
            </div>

            <div class="form-group mb-4">
                <label class="font-weight-bold" for="gejala">Gejala Awal</label>
                <textarea name="gejala" id="gejala" cols="30" rows="4" class="form-control">{{ $pemeriksaan->gejala }}</textarea>
            </div>

            <div class="form-group mb-4">
                <label class="font-weight-bold" for="catatan_dokter">Catatan Dokter</label>
                <textarea name="catatan_dokter" id="catatan_dokter" cols="30" rows="6" class="form-control">{{ $pemeriksaan->catatan_dokter }}</textarea>
            </div>

            <div class="form-group mb-4">
                <div class="row align-items-center mb-3">
                    <div class="col">
                        <label class="font-weight-bold">Resep Obat</label>
                    </div>
                    <div class="col-auto">
                        <button type="button" id="tambah-obat" class="btn btn-sm btn-primary mt-2">+ Tambah Obat</button>
                    </div>
                </div>

                <div id="resep-obat-wrapper">
                    <!-- Awal item resep, sesuaikan dengan data resep pemeriksaan -->
                    @foreach ($resepObat as $index => $item)
                        <div class="row mb-2 resep-item align-items-center">
                            <div class="col-md-4">
                                <input type="text" name="resep[{{ $index }}][nama]" class="form-control"
                                    placeholder="Nama Obat" required value="{{ $item['nama'] ?? '' }}">
                            </div>
                            <div class="col-md-3">
                                <input type="number" name="resep[{{ $index }}][jumlah]" class="form-control"
                                    placeholder="Jumlah" required value="{{ $item['jumlah'] ?? '' }}">
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="resep[{{ $index }}][harga_display]"
                                    class="form-control harga-display" placeholder="Harga (Rp)" required
                                    data-index="{{ $index }}"
                                    value="{{ number_format($item['harga'] ?? 0, 0, ',', '.') }}">
                                <input type="hidden" name="resep[{{ $index }}][harga]" class="harga"
                                    data-index="{{ $index }}" value="{{ $item['harga'] ?? '' }}" />
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-danger btn-sm remove-item">Hapus</button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="form-group mb-4">
                <label class="font-weight-bold" for="biaya">Biaya Pemeriksaan</label>
                <input type="text" data-type="rupiah" id="biaya_display" class="form-control"
                    placeholder="Masukan biaya obat" value="{{ $pemeriksaan->biaya }}">
                <input type="hidden" name="biaya" id="biaya" value="{{ $pemeriksaan->biaya }}" />
            </div>

            <div class="form-group float-right">
                <button class="btn btn-primary" type="submit">Simpan</button>
            </div>
        </form>

    </div>
    <script>
        // Fungsi format Rupiah (satu-satunya)
        function formatRupiah(angka, prefix = '') {
            let number_string = angka.replace(/[^,\d]/g, '').toString(),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix + rupiah;
        }

        // Update tombol remove (hide jika cuma 1 item)
        function updateRemoveButtons() {
            const items = document.querySelectorAll('.resep-item');
            const removeButtons = document.querySelectorAll('.remove-item');
            if (items.length <= 1) {
                removeButtons.forEach(btn => btn.style.display = 'none');
            } else {
                removeButtons.forEach(btn => btn.style.display = 'inline-block');
            }
        }

        // Bind event input format harga display dan update input hidden
        function bindHargaListener(input) {
            input.addEventListener('input', function() {
                let idx = this.dataset.index;
                this.value = formatRupiah(this.value);

                const angka = this.value.replace(/[^0-9]/g, '');
                const hiddenInput = document.querySelector('.harga[data-index="' + idx + '"]');
                if (hiddenInput) {
                    hiddenInput.value = angka;
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Set index awal berdasarkan jumlah resep yang sudah ada
            let index = document.querySelectorAll('.resep-item').length;

            // Bind semua input harga-display yang sudah ada
            document.querySelectorAll('.harga-display').forEach(bindHargaListener);

            // Event tombol tambah obat
            document.getElementById('tambah-obat').addEventListener('click', function() {
                const wrapper = document.getElementById('resep-obat-wrapper');
                const html = `
            <div class="row mb-2 resep-item align-items-center">
                <div class="col-md-4">
                    <input type="text" name="resep[${index}][nama]" class="form-control" placeholder="Nama Obat" required>
                </div>
                <div class="col-md-3">
                    <input type="number" name="resep[${index}][jumlah]" class="form-control" placeholder="Jumlah" required>
                </div>
                <div class="col-md-4">
                    <input type="text" name="resep[${index}][harga_display]" class="form-control harga-display" placeholder="Harga (Rp)" required data-index="${index}">
                    <input type="hidden" name="resep[${index}][harga]" class="harga" data-index="${index}" />
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-danger btn-sm remove-item">Hapus</button>
                </div>
            </div>`;
                wrapper.insertAdjacentHTML('beforeend', html);

                // Bind listener pada input harga-display baru
                const newInput = wrapper.querySelector(`.harga-display[data-index="${index}"]`);
                if (newInput) {
                    bindHargaListener(newInput);
                }

                index++;
                updateRemoveButtons();
            });

            // Event tombol hapus item resep (event delegation)
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-item')) {
                    e.target.closest('.resep-item').remove();
                    updateRemoveButtons();
                }
            });

            updateRemoveButtons();
        });
    </script>
@endsection

@push('scripts')
<!-- jQuery (CDN) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Datepicker CSS (CDN) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/css/bootstrap-datepicker.min.css">
<!-- Datepicker JS (CDN) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/js/bootstrap-datepicker.min.js"></script>
<script>
    $(document).ready(function () {
        $('#tanggal_pemeriksaan').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true,
            startDate: new Date()
        }).on('focus click', function () {
            $(this).datepicker('show');
        });
    });
</script>
@endpush
