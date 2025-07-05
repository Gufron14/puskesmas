@extends('backend.layouts.app')

@section('content')
    <div class="container-fluid">
        <h4 class="font-weight-bold mb-4">Pembayaran & Tebus Obat</h4>
        {{-- Error Validation --}}
        <x-error-validation-message errors="$errors" />
        <div class="mb-4">
            <form method="GET" action="{{ route('pembayaran') }}">
                <label for="pemeriksaan_id" class="font-weight-bold">Pilih Pasien Belum Bayar</label>
                <select name="id" id="pemeriksaan_id" class="form-control" onchange="this.form.submit()">
                    <option value="">-- Pilih Pasien --</option>
                    @foreach (\App\Models\Pemeriksaan::whereDoesntHave('pembayaran')->with('pasien.user')->orderByDesc('created_at')->get() as $periksa)
                        @php
                            $user = optional($periksa->pasien)->user;
                        @endphp
                        <option value="{{ $periksa->id }}" {{ request('id') == $periksa->id ? 'selected' : '' }}>
                            {{ $user->name ?? '-' }} - {{ $user->nik ?? '-' }}
                            ({{ \Carbon\Carbon::parse($periksa->waktu_pemeriksaan)->translatedFormat('d/m/Y H:i') }})
                        </option>
                    @endforeach
                </select>

            </form>
        </div>

        @if ($pemeriksaan)
            <div class="card shadow mb-1">
                <div class="card-body">
                    <h5 class="mb-3">Biaya Pemeriksaan</h5>
                    <div class="d-flex" style="gap: 10px">
                        <h6>Cecep Devi Ramadhani, S. Kep., Ners</h6>
                        <h6>Rp {{ number_format($pemeriksaan->biaya, 0, ',', '.') }}</h6>
                    </div>
                </div>
            </div>
            <div class="card shadow mb-1">
                <div class="card-body">
                    <h5 class="mb-3">Tebus Obat</h5>
                    @forelse ($resep as $item)
                        <div class="d-flex" style="gap: 10px">
                            <h6>{{ $item['nama_obat'] }}</h6>
                            <h6>{{ $item['jumlah'] }}x</h6>
                            <h6>Rp {{ number_format($item['harga'], 0, ',', '.') }}</h6>
                        </div>
                    @empty
                        <p>Tidak ada resep.</p>
                    @endforelse
                </div>
            </div>
            <div class="card shadow">
                <div class="card-body">
                    <h5 class="mb-3">Rincian Pembayaran</h5>
                    <table>
                        <tr>
                            <td>
                                <h6>Biaya Pemeriksaan</h6>
                            </td>
                            <td></td>
                            <td>
                                <h6>Rp {{ number_format($pemeriksaan->biaya, 0, ',', '.') }}</h6>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <h6>Tebus Obat</h6>
                            </td>
                            <td></td>
                            <td>
                                <h6>Rp {{ number_format($totalObat, 0, ',', '.') }}</h6>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <h6>Jumlah Bayar</h6>
                            </td>
                            <td></td>
                            <td>
                                <h6>Rp {{ number_format($pemeriksaan->biaya + $totalObat, 0, ',', '.') }}</h6>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        @else
            <p class="text-muted">Tidak ada data pemeriksaan yang dipilih.</p>
        @endif

        <div class="d-flex mt-4 mb-3 align-items-center" style="gap:50px">
            <h5>Metode Pembayaran</h5>
        </div>
        <form action="{{ route('pembayaran.post') }}" method="POST" onsubmit="return validateForm()">
            @csrf

            <input type="hidden" name="pemeriksaan_id" value="{{ $pemeriksaan->id ?? 0 }}">
            <input type="hidden" name="biaya_pemeriksaan" value="{{ $pemeriksaan->biaya ?? 0 }}">
            <input type="hidden" name="total_obat" value="{{ $totalObat ?? 0 }}">
            <input type="hidden" id="total_tagihan" name="total_tagihan"
                value="{{ ($pemeriksaan->biaya ?? 0) + ($totalObat ?? 0) }}">

            <div class="accordion w-100 mb-2" id="accordion1">
                <div class="card">
                    <div class="card-body">

                        <a role="button" href="#collapse2" data-toggle="collapse" data-target="#collapse2"
                            class="text-decoration-none text-dark" aria-expanded="false" aria-controls="collapse2">
                            <h5 class="mb-0">Transfer</h5>
                        </a>
                        <div id="collapse2" class="collapse mt-3" aria-labelledby="heading2" data-parent="#accordion1">
                            <div class="card mb-2">
                                <div class="card-body d-flex align-items-center" style="gap: 10px">
                                    <input type="radio" name="metode" value="BCA" id="transfer_bca" required
                                        onclick="togglePembayaran('transfer')">
                                    <label for="transfer_bca" class="mb-0">
                                        <h5 class="mb-0">BCA</h5>
                                    </label>
                                </div>
                            </div>
                            <div class="card mb-2">
                                <div class="card-body d-flex align-items-center" style="gap: 10px">
                                    <input type="radio" name="metode" value="BRI" id="transfer_bri" required
                                        onclick="togglePembayaran('transfer')">
                                    <label for="transfer_bri" class="mb-0">
                                        <h5 class="mb-0">BRI</h5>
                                    </label>
                                </div>
                            </div>
                            <div class="card mb-2">
                                <div class="card-body d-flex align-items-center" style="gap: 10px">
                                    <input type="radio" name="metode" value="MANDIRI" id="transfer_mandiri" required
                                        onclick="togglePembayaran('transfer')">
                                    <label for="transfer_mandiri" class="mb-0">
                                        <h5 class="mb-0">Mandiri</h5>
                                    </label>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="card mb-2">
                <div class="card-body d-flex align-items-center" style="gap: 10px">
                    <input type="radio" name="metode" value="qris" id="qris" required
                        onclick="togglePembayaran('qris')">
                    <label for="qris" class="mb-0">
                        <h5 class="mb-0">Qris</h5>
                    </label>
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-body d-flex align-items-center" style="gap: 10px">
                    <input type="radio" name="metode" value="tunai" id="tunai" required
                        onclick="togglePembayaran('tunai')">
                    <label for="tunai" class="mb-0">
                        <h5 class="mb-0">Tunai</h5>
                    </label>
                </div>
            </div>

            {{-- Jumlah Bayar --}}
            <div id="field-jumlah-bayar" class="form-group d-none">
                <label for="jumlah_bayar">Jumlah Bayar (Rp)</label>
                <input type="number" name="jumlah_bayar" id="jumlah_bayar" class="form-control"
                    oninput="hitungKembalian()">
            </div>

            {{-- Kembalian (hanya jika tunai) --}}
            <div id="field-kembalian" class="form-group mt-2 d-none">
                <label for="kembalian">Kembalian (Rp)</label>
                <input type="text" id="kembalian" class="form-control" readonly>
            </div>

            <button type="submit" class="btn btn-success text-white w-100 mt-3">Bayar Sekarang</button>
        </form>
    </div>
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

        function togglePembayaran(metode) {
            const jumlahBayarField = document.getElementById('field-jumlah-bayar');
            const kembalianField = document.getElementById('field-kembalian');
            const jumlahBayarInput = document.getElementById('jumlah_bayar');
            const kembalianInput = document.getElementById('kembalian');
            const totalTagihan = parseInt(document.getElementById('total_tagihan').value) || 0;

            jumlahBayarField.classList.remove('d-none');

            if (metode === 'transfer' || metode === 'qris') {
                kembalianField.classList.add('d-none');
                jumlahBayarInput.value = totalTagihan;
                jumlahBayarInput.readOnly = true; // tidak bisa diketik manual
                kembalianInput.value = '';
            } else if (metode === 'tunai') {
                kembalianField.classList.remove('d-none');
                jumlahBayarInput.value = '';
                jumlahBayarInput.readOnly = false;
                kembalianInput.value = '';
            }
        }


        function hitungKembalian() {
            const totalTagihan = parseInt(document.getElementById('total_tagihan').value) || 0;
            const jumlahBayar = parseInt(document.getElementById('jumlah_bayar').value) || 0;
            const kembalian = jumlahBayar - totalTagihan;
            document.getElementById('kembalian').value = kembalian >= 0 ? kembalian.toLocaleString('id-ID') : '0';
        }

        function validateForm() {
            const totalTagihan = parseInt(document.getElementById('total_tagihan').value) || 0;
            const jumlahBayar = parseInt(document.getElementById('jumlah_bayar').value) || 0;
            const metode = document.querySelector('input[name="metode"]:checked')?.value;

            if (!metode) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops!',
                    text: 'Pilih metode pembayaran terlebih dahulu.',
                });
                return false;
            }

            if (jumlahBayar < totalTagihan) {
                Swal.fire({
                    icon: 'error',
                    title: 'Jumlah Bayar Kurang!',
                    text: 'Jumlah yang dibayarkan tidak boleh kurang dari total tagihan.',
                });
                return false;
            }

            return true;
        }
    </script>
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
