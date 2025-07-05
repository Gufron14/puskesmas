@extends('backend.layouts.app')

@section('content')
    <div class="container-fluid">
        <h4 class="font-weight-bold mb-4">Pemeriksaan Pasien</h4>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Terjadi kesalahan:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <form action="{{ route('pemeriksaan.post') }}" method="POST" id="form-pemeriksaan">
            @csrf

            <input type="hidden" name="tanggal_pemeriksaan" value="{{ now()->toDateString() }}">

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label for="user_id">Pilih Pasien <span class="text-danger">*</span></label>
                        <select class="form-control" name="user_id" id="user_id" required>
                            <option value="">-- Pilih Pasien --</option>
                            @foreach ($pasiens->where('status', 'menunggu') as $pasien)
                                <option value="{{ $pasien->user->id }}"
                                    {{ old('user_id') == $pasien->user->id ? 'selected' : '' }}>
                                    {{ $pasien->user->name }} - {{ $pasien->user->nik }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label for="waktu_pemeriksaan">Waktu Pemeriksaan <span class="text-danger">*</span></label>
                        <input type="datetime-local" id="waktu_pemeriksaan" name="waktu_pemeriksaan" class="form-control"
                            value="{{ old('waktu_pemeriksaan', now()->format('Y-m-d\TH:i')) }}" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <label for="suhu" class="form-label">Suhu Tubuh (°C) <span class="text-danger">*</span></label>
                    <input type="number" name="suhu" class="form-control" id="suhu" min="30" max="45"
                        step="0.1" placeholder="36.5" required value="{{ old('suhu') }}">
                </div>
                <div class="col-md-4">
                    <label for="tensi_darah" class="form-label">Tensi Darah <span class="text-danger">*</span></label>
                    <div class="d-flex align-items-center" style="gap: 5px;">
                        <input type="number" id="sistolik" name="sistolik" class="form-control" min="50"
                            max="300" placeholder="Sistolik" required value="{{ old('sistolik') }}">
                        <span>/</span>
                        <input type="number" id="diastolik" name="diastolik" class="form-control" min="30"
                            max="200" placeholder="Diastolik" required value="{{ old('diastolik') }}">
                    </div>
                </div>

                <div class="col-md-4">
                    <label for="biaya" class="form-label">Biaya Pemeriksaan <span class="text-danger">*</span></label>
                    <input type="number" name="biaya" class="form-control" id="biaya" min="0"
                        placeholder="50000" required value="{{ old('biaya') }}">
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="form-group mb-4">
                        <label for="gejala">Gejala Awal</label>
                        <textarea name="gejala" id="gejala" cols="30" rows="3" class="form-control" maxlength="1000"
                            placeholder="Masukkan gejala yang dialami pasien">{{ old('gejala') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label for="diagnosa">Diagnosa Sementara</label>
                        <textarea name="diagnosa" id="diagnosa" cols="30" rows="4" class="form-control" maxlength="2000"
                            placeholder="Masukkan diagnosa">{{ old('diagnosa') }}</textarea>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label for="catatan_dokter">Catatan Dokter</label>
                        <textarea name="catatan_dokter" id="catatan_dokter" cols="30" rows="4" class="form-control"
                            maxlength="2000" placeholder="Masukkan catatan dokter">{{ old('catatan_dokter') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Resep Obat Section -->
            <div class="card mb-4">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="mb-0">Resep Obat <span class="text-danger">*</span></h5>
                        </div>
                        <div class="col-auto">
                            <button type="button" id="btn-tambah-obat" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Tambah Obat
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="resep-container">
                        @php
                            $oldResep = old('resep', [
                                ['nama_obat' => '', 'jenis_obat' => '', 'keterangan_makan' => '', 'jumlah' => 1],
                            ]);
                        @endphp

                        @foreach ($oldResep as $index => $resep)
                            <div class="resep-item mb-3" data-index="{{ $index }}">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label>Nama Obat <span class="text-danger">*</span></label>
                                        <select name="resep[{{ $index }}][nama_obat]" class="form-control"
                                            required>
                                            <option value="">-- Pilih Obat --</option>
                                            @foreach ($obats as $obat)
                                                <option value="{{ $obat->id }}" data-harga="{{ $obat->harga }}"
                                                    data-stok="{{ $obat->stok }}"
                                                    {{ ($resep['nama_obat'] ?? '') == $obat->id ? 'selected' : '' }}>
                                                    {{ $obat->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label>Jenis Obat <span class="text-danger">*</span></label>
                                        <select name="resep[{{ $index }}][jenis_obat]" class="form-control"
                                            required>
                                            <option value="">-- Pilih Jenis --</option>
                                            @foreach ($jenisObats as $jenis)
                                                <option value="{{ $jenis->id }}"
                                                    {{ ($resep['jenis_obat'] ?? '') == $jenis->id ? 'selected' : '' }}>
                                                    {{ $jenis->jenis_obat }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label>Harga Satuan</label>
                                        <input type="text" class="form-control harga-obat" readonly
                                            value="@if (isset($resep['nama_obat']) && $resep['nama_obat'] && $obats->find($resep['nama_obat'])) {{ 'Rp' . number_format($obats->find($resep['nama_obat'])->harga, 0, ',', '.') }} @endif"
                                            placeholder="Pilih obat dulu">
                                    </div>
                                    <div class="col-md-2">
                                        <label>Aturan Minum <span class="text-danger">*</span></label>
                                        <select name="resep[{{ $index }}][keterangan_makan]" class="form-control"
                                            required>
                                            <option value="">-- Pilih --</option>
                                            <option value="sebelum_makan"
                                                {{ ($resep['keterangan_makan'] ?? '') == 'sebelum_makan' ? 'selected' : '' }}>
                                                Sebelum Makan</option>
                                            <option value="sesudah_makan"
                                                {{ ($resep['keterangan_makan'] ?? '') == 'sesudah_makan' ? 'selected' : '' }}>
                                                Sesudah Makan</option>
                                        </select>
                                    </div>
                                    <div class="col-md-1">
                                        <label>Jumlah <span class="text-danger">*</span></label>
                                        <input type="number" name="resep[{{ $index }}][jumlah]"
                                            class="form-control" min="1" value="{{ $resep['jumlah'] ?? 1 }}"
                                            required>
                                    </div>
                                    <div class="col-md-2">
                                        <label>Subtotal</label>
                                        <input type="text" class="form-control subtotal-obat" readonly
                                            value="@if (isset($resep['nama_obat']) && $resep['nama_obat'] && $obats->find($resep['nama_obat'])) {{ 'Rp' . number_format($obats->find($resep['nama_obat'])->harga * ($resep['jumlah'] ?? 1), 0, ',', '.') }} @endif"
                                            placeholder="Rp 0">
                                        <small class="text-info stok-info">
                                            @if (isset($resep['nama_obat']) && $resep['nama_obat'] && $obats->find($resep['nama_obat']))
                                                Stok: {{ $obats->find($resep['nama_obat'])->stok }}
                                            @endif
                                        </small>
                                    </div>
                                    <div class="col-md-1">
                                        <label>&nbsp;</label>
                                        @if ($index > 0)
                                            <button type="button" class="btn btn-danger btn-sm btn-remove-resep d-block">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        @else
                                            <div class="btn btn-sm d-block" style="visibility: hidden;">
                                                <i class="fas fa-times"></i>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>


                    <div class="col-md-4 text-right">
                        <div class="card bg-light">
                            <div class="card-body py-2 total-biaya-card">
                                @php
                                    $totalObat = 0;
                                    foreach ($oldResep as $resep) {
                                        if (isset($resep['nama_obat']) && $resep['nama_obat']) {
                                            $obat = $obats->find($resep['nama_obat']);
                                            if ($obat) {
                                                $totalObat += ($resep['jumlah'] ?? 1) * $obat->harga;
                                            }
                                        }
                                    }
                                    $biayaPemeriksaan = old('biaya', 0);
                                    $totalBiaya = $totalObat + $biayaPemeriksaan;
                                @endphp
                                <strong>Total Obat: Rp{{ number_format($totalObat, 0, ',', '.') }}</strong><br>
                                <strong>Biaya Pemeriksaan:
                                    Rp{{ number_format($biayaPemeriksaan, 0, ',', '.') }}</strong><br>
                                <strong class="text-primary">Total Keseluruhan:
                                    Rp{{ number_format($totalBiaya, 0, ',', '.') }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="form-group text-right">
                <button type="button" class="btn btn-secondary mr-2" onclick="window.history.back()">Batal</button>
                <button class="btn btn-primary" type="submit">
                    <i class="fas fa-save"></i> Simpan Pemeriksaan
                </button>
            </div>
        </form>
    </div>

    <!-- JavaScript yang disederhanakan -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let resepIndex = {{ count($oldResep) }};

            // Data obat dan jenis obat untuk perhitungan
            const obatData = @json($obats->keyBy('id'));
            const jenisObatData = @json($jenisObats->keyBy('id')); // Tambahkan ini

            // Tambah resep obat baru





            // Hapus resep obat
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('btn-remove-resep') || e.target.closest(
                        '.btn-remove-resep')) {
                    const button = e.target.classList.contains('btn-remove-resep') ? e.target : e.target
                        .closest('.btn-remove-resep');
                    const resepItem = button.closest('.resep-item');
                    const resepItems = document.querySelectorAll('.resep-item');

                    if (resepItems.length > 1) {
                        resepItem.remove();
                    } else {
                        alert('Minimal harus ada satu resep obat');
                    }
                }
            });


        });
    </script>

    <!-- JavaScript untuk live perhitungan -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let resepIndex = {{ count($oldResep) }};

            // Data obat untuk perhitungan
            const obatData = @json($obats->keyBy('id'));
            const jenisObatData = @json($jenisObats->keyBy('id'));

            // Format number untuk display
            function formatRupiah(number) {
                return 'Rp' + number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }

            // Parse number dari string
            function parseNumber(str) {
                return parseInt(str.toString().replace(/[^0-9]/g, '')) || 0;
            }

            // Update total biaya keseluruhan
            function updateTotalBiaya() {
                let totalObat = 0;
                const biayaPemeriksaan = parseNumber(document.getElementById('biaya').value);

                // Hitung total obat dari semua subtotal
                document.querySelectorAll('.subtotal-obat').forEach(function(subtotalInput) {
                    if (subtotalInput.value) {
                        const subtotal = parseNumber(subtotalInput.value);
                        totalObat += subtotal;
                    }
                });

                const totalKeseluruhan = totalObat + biayaPemeriksaan;

                // Update tampilan total
                const totalCard = document.querySelector('.total-biaya-card');
                if (totalCard) {
                    totalCard.innerHTML = `
            <strong>Total Obat: ${formatRupiah(totalObat)}</strong><br>
            <strong>Biaya Pemeriksaan: ${formatRupiah(biayaPemeriksaan)}</strong><br>
            <strong class="text-primary">Total Keseluruhan: ${formatRupiah(totalKeseluruhan)}</strong>
        `;
                }
            }

            // Event listener untuk perubahan select obat
            document.addEventListener('change', function(e) {
                if (e.target.matches('select[name*="nama_obat"]')) {
                    const resepItem = e.target.closest('.resep-item');
                    const selectedOption = e.target.options[e.target.selectedIndex];
                    const hargaInput = resepItem.querySelector('.harga-obat');
                    const subtotalInput = resepItem.querySelector('.subtotal-obat');
                    const jumlahInput = resepItem.querySelector('input[name*="jumlah"]');
                    const stokInfo = resepItem.querySelector('.stok-info');

                    if (selectedOption.value) {
                        const harga = parseInt(selectedOption.dataset.harga) || 0;
                        const stok = parseInt(selectedOption.dataset.stok) || 0;
                        const jumlah = parseInt(jumlahInput.value) || 1;
                        const subtotal = harga * jumlah;

                        // Update harga
                        hargaInput.value = formatRupiah(harga);

                        // Update subtotal
                        subtotalInput.value = formatRupiah(subtotal);

                        // Update info stok
                        stokInfo.textContent = `Stok: ${stok}`;

                        // Set max jumlah sesuai stok
                        jumlahInput.setAttribute('max', stok);

                        // Validasi jika jumlah melebihi stok
                        if (jumlah > stok) {
                            jumlahInput.value = stok;
                            subtotalInput.value = formatRupiah(harga * stok);
                        }
                    } else {
                        hargaInput.value = '';
                        subtotalInput.value = '';
                        stokInfo.textContent = '';
                        jumlahInput.removeAttribute('max');
                    }

                    updateTotalBiaya();
                }
            });

            // Event listener untuk perubahan jumlah
            document.addEventListener('input', function(e) {
                if (e.target.matches('input[name*="jumlah"]')) {
                    const resepItem = e.target.closest('.resep-item');
                    const selectObat = resepItem.querySelector('select[name*="nama_obat"]');
                    const subtotalInput = resepItem.querySelector('.subtotal-obat');
                    const jumlah = parseInt(e.target.value) || 0;

                    if (selectObat.value) {
                        const selectedOption = selectObat.options[selectObat.selectedIndex];
                        const harga = parseInt(selectedOption.dataset.harga) || 0;
                        const stok = parseInt(selectedOption.dataset.stok) || 0;
                        const subtotal = harga * jumlah;

                        // Update subtotal
                        subtotalInput.value = formatRupiah(subtotal);

                        // Validasi stok
                        if (jumlah > stok) {
                            e.target.value = stok;
                            subtotalInput.value = formatRupiah(harga * stok);
                            alert(`Jumlah tidak boleh melebihi stok yang tersedia (${stok})`);
                        }
                    }

                    updateTotalBiaya();
                }
            });

            // Event listener untuk perubahan biaya pemeriksaan
            document.getElementById('biaya').addEventListener('input', function() {
                updateTotalBiaya();
            });

            // Tambah resep obat baru - menggunakan data dari obatData
            document.getElementById('btn-tambah-obat').addEventListener('click', function() {
                let optionsHtml = '<option value="">-- Pilih Obat --</option>';
                Object.values(obatData).forEach(obat => {
                    optionsHtml += `<option value="${obat.id}" data-harga="${obat.harga}" data-stok="${obat.stok}">${obat.nama}</option>`;
                });

                let jenisOptionsHtml = '<option value="">-- Pilih Jenis --</option>';
                Object.values(jenisObatData).forEach(jenis => {
                    jenisOptionsHtml += `<option value="${jenis.id}">${jenis.jenis_obat}</option>`;
                });

                const newResepHtml = `
        <div class="resep-item mb-3" data-index="${resepIndex}">
            <div class="row">
                <div class="col-md-2">
                    <label>Nama Obat <span class="text-danger">*</span></label>
                    <select name="resep[${resepIndex}][nama_obat]" class="form-control" required>
                        ${optionsHtml}
                    </select>
                </div>
                <div class="col-md-2">
                    <label>Jenis Obat <span class="text-danger">*</span></label>
                    <select name="resep[${resepIndex}][jenis_obat]" class="form-control" required>
                        ${jenisOptionsHtml}
                    </select>
                </div>
                <div class="col-md-2">
                    <label>Harga Satuan</label>
                    <input type="text" class="form-control harga-obat" readonly placeholder="Pilih obat dulu">
                </div>
                <div class="col-md-2">
                    <label>Aturan Minum <span class="text-danger">*</span></label>
                    <select name="resep[${resepIndex}][keterangan_makan]" class="form-control" required>
                        <option value="">-- Pilih --</option>
                        <option value="sebelum_makan">Sebelum Makan</option>
                        <option value="sesudah_makan">Sesudah Makan</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <label>Jumlah <span class="text-danger">*</span></label>
                    <input type="number" name="resep[${resepIndex}][jumlah]" class="form-control" 
                           min="1" value="1" required>
                </div>
                <div class="col-md-2">
                    <label>Subtotal</label>
                    <input type="text" class="form-control subtotal-obat" readonly placeholder="Rp 0">
                    <small class="text-info stok-info"></small>
                </div>
                <div class="col-md-1">
                    <label>&nbsp;</label>
                    <button type="button" class="btn btn-danger btn-sm btn-remove-resep d-block">
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    `;

                document.getElementById('resep-container').insertAdjacentHTML('beforeend', newResepHtml);
                resepIndex++;
                updateTotalBiaya();
            });

            // Hapus resep obat
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('btn-remove-resep') || e.target.closest(
                        '.btn-remove-resep')) {
                    const button = e.target.classList.contains('btn-remove-resep') ? e.target : e.target
                        .closest('.btn-remove-resep');
                    const resepItem = button.closest('.resep-item');
                    const resepItems = document.querySelectorAll('.resep-item');

                    if (resepItems.length > 1) {
                        resepItem.remove();
                        updateTotalBiaya();
                    } else {
                        alert('Minimal harus ada satu resep obat');
                    }
                }
            });

            // Validasi form sebelum submit
            document.getElementById('form-pemeriksaan').addEventListener('submit', function(e) {
                const resepItems = document.querySelectorAll('.resep-item');
                let isValid = true;
                let errorMessages = [];

                // Validasi setiap resep
                resepItems.forEach(function(item, index) {
                    const selectObat = item.querySelector('select[name*="nama_obat"]');
                    const selectJenis = item.querySelector('select[name*="jenis_obat"]');
                    const selectKeterangan = item.querySelector('select[name*="keterangan_makan"]');
                    const inputJumlah = item.querySelector('input[name*="jumlah"]');

                    if (!selectObat.value) {
                        isValid = false;
                        errorMessages.push(`Resep ${index + 1}: Nama obat harus dipilih`);
                    }

                    if (!selectJenis.value) {
                        isValid = false;
                        errorMessages.push(`Resep ${index + 1}: Jenis obat harus dipilih`);
                    }

                    if (!selectKeterangan.value) {
                        isValid = false;
                        errorMessages.push(`Resep ${index + 1}: Aturan Minum harus dipilih`);
                    }

                    if (!inputJumlah.value || parseInt(inputJumlah.value) < 1) {
                        isValid = false;
                        errorMessages.push(`Resep ${index + 1}: Jumlah obat harus diisi minimal 1`);
                    }

                    // Validasi stok
                    if (selectObat.value && obatData[selectObat.value]) {
                        const stok = parseInt(obatData[selectObat.value].stok) || 0;
                        const jumlah = parseInt(inputJumlah.value) || 0;
                        if (jumlah > stok) {
                            isValid = false;
                            errorMessages.push(
                                `Resep ${index + 1}: Jumlah obat melebihi stok yang tersedia (${stok})`
                            );
                        }
                    }
                });

                // Validasi biaya pemeriksaan
                const biayaPemeriksaan = parseNumber(document.getElementById('biaya').value);
                if (biayaPemeriksaan <= 0) {
                    isValid = false;
                    errorMessages.push('Biaya pemeriksaan harus diisi');
                }

                if (!isValid) {
                    e.preventDefault();
                    alert('Terdapat kesalahan:\n\n' + errorMessages.join('\n'));
                    return false;
                }

                // Konfirmasi sebelum submit
                const totalBiaya = document.querySelector('.total-biaya-card').textContent;
                if (!confirm('Apakah Anda yakin ingin menyimpan pemeriksaan ini?')) {
                    e.preventDefault();
                    return false;
                }
            });

            // Initial calculation
            updateTotalBiaya();
        });
    </script>


    <style>
        .resep-item {
            border: 1px solid #e9ecef;
            border-radius: 5px;
            padding: 15px;
            background-color: #f8f9fa;
        }

        .resep-item:hover {
            background-color: #e9ecef;
        }

        .text-danger {
            color: #dc3545 !important;
        }

        .alert-info {
            background-color: #d1ecf1;
            border-color: #bee5eb;
            color: #0c5460;
        }
    </style>
@endsection
