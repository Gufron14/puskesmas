@extends('backend.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row align-items-center justify-content-between mb-4">
            <div class="col">
                <h4 class="font-weight-bold text-dark">Data Laporan Keuangan</h4>
            </div>

            <div class="col-auto">
                <button type="button" class="btn btn-success text-white" data-toggle="modal" data-target="#modalLaporan">
                    Cetak Laporan
                </button>
                <!-- Modal -->
                <div class="modal fade" id="modalLaporan" tabindex="-1" role="dialog" aria-labelledby="modalLaporanLabel"
                    aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <form action="{{ route('laporan.bulanan') }}" method="GET" target="_blank">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Pilih Bulan & Tahun</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="bulan">Bulan</label>
                                        <select name="bulan" id="bulan" class="form-control" required>
                                            @for ($i = 1; $i <= 12; $i++)
                                                <option value="{{ $i }}">
                                                    {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="tahun">Tahun</label>
                                        <select name="tahun" id="tahun" class="form-control" required>
                                            @for ($y = now()->year; $y >= 2020; $y--)
                                                <option value="{{ $y }}">{{ $y }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-success text-white">Cetak</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
        <div class="table-responsive">
            <table class="table datatables" id="dataTable-1">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Pasien</th>
                        <th>Tanggal</th>
                        <th>Harga Obat</th>
                        <th>Biaya Pemeriksaan</th>
                        <th>Total Harga</th>
                        <th>Metode Pembayaran</th>
                        <th>Jumlah Bayar</th>
                        <th>Kembalian</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pembayarans as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->pemeriksaan->pasien->nama_pasien }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->pemeriksaan->tanggal_pemeriksaan)->translatedFormat('l, d F Y, H:i') }}
                            </td>
                            <td>Rp{{ number_format($item->total_obat, 0, ',', '.') }}</td>
                            <td>Rp{{ number_format($item->biaya_pemeriksaan, 0, ',', '.') }}</td>
                            <td>Rp{{ number_format($item->total_tagihan, 0, ',', '.') }}</td>
                            <td>{{ strtoupper($item->metode) }}</td>
                            <td>Rp{{ number_format($item->jumlah_bayar, 0, ',', '.') }}</td>
                            <td>Rp{{ number_format($item->kembalian, 0, ',', '.') }}</td>
                            <td>
                                <button class="btn btn-danger" onclick="deleteActivity({{ $item->id }})">Hapus</button>
                                <form id="Hapus{{ $item->id }}" action="{{ route('pembayaran.destroy', $item->id) }}"
                                    method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                <a href="{{ route('pembayaran.invoice', $item->id) }}"
                                    class="btn btn-success text-white px-3" target="_blank">Cetak</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
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
    <script>
        function deleteActivity(id) {
            event.preventDefault();

            const formId = `Hapus${id}`;
            const form = document.getElementById(formId);

            Swal.fire({
                title: 'Apakah Anda Yakin ?',
                text: 'Data Akan Terhapus Secara Permanen!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a746',
                cancelButtonColor: '#FF0000',
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal',
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    </script>
@endsection
