@extends('backend.layouts.app')

@section('content')
    <div class="container-fluid">
        <h4 class="font-weight-bold text-dark mb-4">Rekam Medis</h4>

        <!-- Filter Periode untuk Print -->
            <div class="mb-5">
                <form method="GET" action="{{ url('/admin/rekamedis/print-laporan') }}" target="_blank">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="tanggal_mulai">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control"
                                value="{{ request('tanggal_mulai', date('Y-m-01')) }}" required>
                        </div>
                        <div class="col-md-3">
                            <label for="tanggal_selesai">Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-control"
                                value="{{ request('tanggal_selesai', date('Y-m-d')) }}" required>
                        </div>
                        <div class="col-md-3">
                            <label>&nbsp;</label>
                            <div>
                                <button type="submit" class="btn btn-success">
                                    <i class="fe fe-printer"></i> Print Laporan
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>


        <div class="table-responsive">
            <table class="table datatables" id="dataTable-1">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Pasien</th>
                        <th>Suhu</th>
                        <th>Tensi</th>
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
                            <td>{{ $item->user->name ?? '-' }}</td>
                            <td>{{ $item->suhu }}&deg;C</td>
                            <td>{{ $item->tensi_sistolik }} / {{ $item->tensi_diastolik }} mmHg</td>
                            <td>
                                @if (count($item->resep_formatted))
                                    @foreach ($item->resep_formatted as $r)
                                        <span
                                            class="badge badge-primary mr-1 mb-1">{{ $r['nama_obat'] ?? ($r['nama'] ?? '-') }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                {{ \Carbon\Carbon::parse($item->waktu_pemeriksaan)->locale('id')->translatedFormat('l, d F Y, H:i') }}
                            </td>
                            <td>Rp{{ number_format($totalObat + $item->biaya, 0, ',', '.') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('rekamedis.show', $item->id) }}" type="button"
                                        class="btn btn-info btn-sm">
                                        <i class="fe fe-eye"></i> Detail
                                        </>
                                        <a href="{{ route('rekamedis.edit', $item->id) }}"
                                            class="btn btn-primary btn-sm text-white">
                                            <i class="fe fe-edit"></i> Edit
                                        </a>
                                        <button type="button" class="btn btn-success btn-sm dropdown-toggle"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fe fe-printer"></i> Cetak
                                        </button>
                                        <div class="dropdown-menu">

                                            <a class="dropdown-item" href="{{ url('/admin/rekamedis/print-pemeriksaan/' . $item->id) }}"
                                                target="_blank">🖨️ Cetak Langsung</a>
                                            <a class="dropdown-item" href="{{ url('/pemeriksaan/exportPdf/' . $item->id) }}"
                                                target="_blank">📄 Download PDF</a>
                                        </div>
                                </div>
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

        // Initialize DataTable with responsive options
        $(document).ready(function() {
            $('#dataTable-1').DataTable({
                responsive: true,
                autoWidth: false,
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                "order": [
                    [5, "desc"]
                ], // Sort by date column
                "columnDefs": [{
                        "orderable": false,
                        "targets": 7
                    } // Disable sorting on action column
                ]
            });
        });
    </script>

    <style>
        .border-left-primary {
            border-left: 4px solid #007bff !important;
        }

        .border-left-success {
            border-left: 4px solid #28a745 !important;
        }

        .border-left-warning {
            border-left: 4px solid #ffc107 !important;
        }

        .border-left-info {
            border-left: 4px solid #17a2b8 !important;
        }

        .border-left-danger {
            border-left: 4px solid #dc3545 !important;
        }

        .border-left-dark {
            border-left: 4px solid #343a40 !important;
        }

        .modal-lg {
            max-width: 900px;
        }

        .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border: 1px solid rgba(0, 0, 0, 0.125);
        }

        .table-borderless td {
            border: none;
            padding: 0.25rem 0.5rem;
        }
    </style>
@endsection
