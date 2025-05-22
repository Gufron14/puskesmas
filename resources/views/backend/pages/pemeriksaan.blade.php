@extends('backend.layouts.app')

@section('content')
    <div class="container-fluid">
        <h4 class="font-weight-bold mb-4">Pemeriksaan Pasien</h4>

        <form action="{{ route('pemeriksaan.post') }}" method="POST">
            @csrf
            <div class="form-group mb-4">
                <label for="simple-select2">Pilih Pasien</label>
                <select class="form-control select2" name="pasien_id" id="simple-select2">
                    <optgroup>
                        @foreach ($pasiens as $pasien)
                            <option value="{{ $pasien->id }}">{{ $pasien->nama_pasien }} - No. Antrian:
                                {{ $pasien->nomor_antrian }}</option>
                        @endforeach
                    </optgroup>
                </select>
            </div>
            <div class="form-group mb-4">
                <label class="font-weight-bold" for="tanggal_pemeriksaan">Tanggal Pemeriksaan</label>
                <input type="datetime-local" id="tanggal_pemeriksaan" name="tanggal_pemeriksaan" class="form-control">
            </div>
            <div class="mb-3">
                <label for="tensi_darah" class="form-label">Tensi Darah</label>
                <div class="d-flex align-items-center" style="gap: 5px;">
                    <input type="number" id="sistolik" name="sistolik" class="form-control" min="1"
                        style="width: 80px;" maxlength="3" placeholder="" pattern="\d{2,3}" required>
                    <span>/</span>
                    <input type="number" id="diastolik" name="diastolik" class="form-control" min="1"
                        style="width: 80px;" maxlength="3" placeholder="" pattern="\d{2,3}" required>
                </div>
            </div>
            <div class="form-group mb-4">
                <label class="font-weight-bold" for="gejala">Gejala Awal</label>
                <textarea name="gejala" id="gejala" cols="30" rows="4" class="form-control"></textarea>
            </div>
            <div class="form-group mb-4">
                <label class="font-weight-bold" for="catatan_dokter">Catatan Dokter</label>
                <textarea name="catatan_dokter" id="catatan_dokter" cols="30" rows="6" class="form-control"></textarea>
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
                    <!-- awal item resep -->
                    <div class="row mb-2 resep-item align-items-center">
                        <div class="col-md-4">
                            <input type="text" name="resep[0][nama]" class="form-control" placeholder="Nama Obat"
                                required>
                        </div>
                        <div class="col-md-3">
                            <input type="number" name="resep[0][jumlah]" class="form-control" placeholder="Jumlah"
                                required>
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="resep[0][harga_display]" class="form-control harga-display"
                                placeholder="Harga (Rp)" required data-index="0">
                            <input type="hidden" name="resep[0][harga]" class="harga" data-index="0" />
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-danger btn-sm remove-item">Hapus</button>
                        </div>
                    </div>
                </div>

            </div>

            <div class="form-group mb-4">
                <label class="font-weight-bold" for="biaya">Biaya Pemeriksaan</label>
                <input type="text" data-type="rupiah" id="biaya_display" class="form-control"
                    placeholder="Masukan biaya obat">
                <input type="hidden" name="biaya" id="biaya" />
            </div>
            <div class="form-group float-right">
                <button class="btn btn-primary" type="submit">Simpan</button>
            </div>
        </form>
    </div>
    <script>
        let index = 1;

        function updateRemoveButtons() {
            const items = document.querySelectorAll('.resep-item');
            const removeButtons = document.querySelectorAll('.remove-item');
            if (items.length <= 1) {
                removeButtons.forEach(btn => btn.style.display = 'none');
            } else {
                removeButtons.forEach(btn => btn.style.display = 'inline-block');
            }
        }

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

            // Bind event listener ke input harga-display yang baru
            let newHargaDisplay = wrapper.querySelector(`.harga-display[data-index="${index}"]`);
            if (newHargaDisplay) {
                newHargaDisplay.addEventListener('input', function() {
                    let val = this.value;
                    this.value = formatRupiah(val);

                    let angka = this.value.replace(/[^0-9]/g, '');
                    let hiddenInput = document.querySelector('.harga[data-index="' + index + '"]');
                    if (hiddenInput) {
                        hiddenInput.value = angka;
                    }
                });
            }

            index++;
            updateRemoveButtons();
        });

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-item')) {
                e.target.closest('.resep-item').remove();
                updateRemoveButtons();
            }
        });

        // Fungsi format rupiah perlu di luar supaya bisa dipakai
        function formatRupiah(angka) {
            let number_string = angka.replace(/[^,\d]/g, '').toString();
            let split = number_string.split(',');
            let sisa = split[0].length % 3;
            let rupiah = split[0].substr(0, sisa);
            let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
            return rupiah;
        }

        // Jalankan saat halaman pertama kali dimuat
        document.addEventListener('DOMContentLoaded', updateRemoveButtons);
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const rupiahInput = document.querySelector('#biaya_display');
            const hiddenInput = document.querySelector('#biaya');

            rupiahInput.addEventListener('input', function(e) {
                let value = this.value.replace(/[^,\d]/g, '').toString();
                let split = value.split(',');
                let sisa = split[0].length % 3;
                let rupiah = split[0].substr(0, sisa);
                let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                if (ribuan) {
                    rupiah += (sisa ? '.' : '') + ribuan.join('.');
                }

                rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
                this.value = rupiah;

                // Simpan nilai angka ke input hidden
                hiddenInput.value = value.replace(/[^0-9]/g, '');
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
            function formatRupiah(angka, prefix) {
                let number_string = angka.replace(/[^,\d]/g, '').toString();
                let split = number_string.split(',');
                let sisa = split[0].length % 3;
                let rupiah = split[0].substr(0, sisa);
                let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                if (ribuan) {
                    let separator = sisa ? '.' : '';
                    rupiah += separator + ribuan.join('.');
                }

                rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
                return prefix === undefined ? rupiah : (rupiah ? prefix + rupiah : '');
            }

            // Tangani semua input harga-display
            document.querySelectorAll('.harga-display').forEach(function(input) {
                input.addEventListener('input', function(e) {
                    let index = this.dataset.index;
                    let val = this.value;
                    this.value = formatRupiah(val);

                    // Update input hidden sesuai index
                    let angka = this.value.replace(/[^0-9]/g, '');
                    let hiddenInput = document.querySelector('.harga[data-index="' + index + '"]');
                    if (hiddenInput) {
                        hiddenInput.value = angka;
                    }
                });
            });
        });
    </script>
@endsection
