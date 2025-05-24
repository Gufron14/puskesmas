@extends('backend.layouts.app')

@section('content')
    <div class="container-fluid">
        <h4 class="font-weight-bold text-dark mb-4">Rekam Medis</h4>
        <div class="table-responsive">
            <table class="table datatables" id="dataTable-1">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Pasien</th>
                        <th>NIK</th>
                        <th>Keluhan</th>
                        <th>Tensi</th>
                        <th>Hasil</th>
                        <th>Resep Obat</th>
                        <th>Tanggal</th>
                        <th>Total Bayar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pemeriksaans as $item)
                        @php
                            $resep = json_decode($item->resep_obat, true) ?? [];
                            $totalObat = collect($resep)->sum(
                                fn($obat) => ($obat['jumlah'] ?? 0) * ($obat['harga'] ?? 0),
                            );
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->pasien->nama_pasien ?? '-' }}</td>
                            <td>{{ $item->pasien->nik ?? '-' }}</td>
                            <td>{{ $item->gejala }}</td>
                            <td>{{ $item->tensi_sistolik }} / {{ $item->tensi_diastolik }}</td>
                            <td>{{ $item->catatan_dokter }}</td>
                            <td>
                                @if (count($resep))
                                    <ul class="mb-0 pl-3">
                                        @foreach ($resep as $r)
                                            <li>{{ $r['nama'] ?? '-' }} : Rp{{ number_format($r['harga']) ?? 0 }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal_pemeriksaan)->translatedFormat('l, d F Y, H:i') }}
                            </td>
                            <td>Rp{{ number_format($totalObat + $item->biaya, 0, ',', '.') }}</td>
                            <td>
                                <!-- Aksi seperti tombol lihat/cetak/edit -->
                                <button class="btn btn-danger" onclick="deleteActivity({{ $item->id }})">Hapus</button>
                                <form id="Hapus{{ $item->id }}" action="{{ route('pemeriksaan.destroy', $item->id) }}"
                                    method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                <a href="{{ route('rekamedis.edit', $item->pasien_id) }}" 
                                    class="btn btn-primary text-white">Edit</a>
                                <a href="{{ route('pemeriksaan.exportPdf', $item->pasien_id) }}" target="_blank"
                                    class="btn btn-success text-white">Cetak</a>
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
