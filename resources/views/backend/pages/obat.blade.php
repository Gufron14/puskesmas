{{-- Tampilan Tabel CRUD Obat --}}

@extends('backend.layouts.app')

@section('content')
    <div class="container-fluid">
        <h4 class="font-weight-bold text-dark mb-4">Obat</h4>

        {{-- Error Validation --}}
        <x-error-validation-message errors="$errors" />

        {{-- Alert Message --}}
        @if (session()->has('success'))
            <div class="row">
                <div class="col-md-12">
                    <x-success-message action="{{ session()->get('success') }}" />
                </div>
            </div>
        @endif

        {{-- Filter Search --}}
        {{-- <div class="row mb-4">
            <div class="col-md-4">
                <form action="{{ route('obat.index') }}" method="GET">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" placeholder="Cari Obat">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">Cari</button>
                        </div>
                    </div>
                </form>
            </div>
        </div> --}}

        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Data Obat</h6>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createObatModal">
                    <i class="fe fe-plus"></i> Tambah Obat
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered datatable" id="dataTable-1" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Obat</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Deskripsi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($obats as $obat)
                                <tr id="obat-row-{{ $obat->id }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $obat->nama }}</td>
                                    <td>Rp{{ number_format($obat->harga, 0, ',', '.') }}</td>
                                    <td>{{ $obat->stok }}</td>
                                    <td>{{ $obat->deskripsi ?? '-' }}</td>
                                    <td>
                                        <button class="btn btn-primary btn-sm" onclick="editObat({{ $obat->id }}, '{{ $obat->nama }}', {{ $obat->harga }}, {{ $obat->stok }}, '{{ $obat->deskripsi }}')">
                                            <i class="fe fe-edit fe-16"></i>
                                        </button>
                                        {{-- Button Hapus --}}
                                        <button class="btn btn-danger btn-sm" onclick="deleteObat({{ $obat->id }})">
                                            <i class="fe fe-trash fe-16"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Create Obat --}}
    <div class="modal fade" id="createObatModal" tabindex="-1" role="dialog" aria-labelledby="createObatModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createObatModalLabel">Tambah Obat Baru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="createObatForm" action="{{ route('obat.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="create_nama">Nama Obat <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="create_nama" name="nama" required>
                        </div>
                        <div class="form-group">
                            <label for="create_harga">Harga <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="create_harga" name="harga" min="0"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="create_stok">Stok</label>
                            <input type="number" class="form-control" id="create_stok" name="stok" min="0"
                                value="0">
                        </div>
                        <div class="form-group">
                            <label for="create_deskripsi">Deskripsi</label>
                            <textarea class="form-control" id="create_deskripsi" name="deskripsi" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Edit Obat --}}
    <div class="modal fade" id="editObatModal" tabindex="-1" role="dialog" aria-labelledby="editObatModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editObatModalLabel">Edit Obat</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editObatForm" action="" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_obat_id" name="obat_id">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="edit_nama">Nama Obat <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_nama" name="nama" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_harga">Harga <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="edit_harga" name="harga" min="0"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="edit_stok">Stok</label>
                            <input type="number" class="form-control" id="edit_stok" name="stok" min="0">
                        </div>
                        <div class="form-group">
                            <label for="edit_deskripsi">Deskripsi</label>
                            <textarea class="form-control" id="edit_deskripsi" name="deskripsi" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Scripts --}}
    <script>
        function editObat(id) {
            window.location.href = `/obat/${id}/edit`;
        }

        function deleteObat(id) {
            if (confirm('Apakah Anda yakin ingin menghapus obat ini?')) {
                let form = document.createElement('form');
                form.method = 'POST';
                form.action = `/obat/${id}`;
                form.innerHTML = `
                    @csrf
                    @method('DELETE')
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }

        function editObat(id, nama, harga, stok, deskripsi) {
            $('#editObatForm').attr('action', `/obat/${id}`);
            $('#edit_nama').val(nama);
            $('#edit_harga').val(harga);
            $('#edit_stok').val(stok);
            $('#edit_deskripsi').val(deskripsi || '');
            $('#editObatModal').modal('show');
        }
    </script>
@endsection
