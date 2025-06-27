@extends('backend.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row align-items-center justify-content-between mb-4">
            <div class="col">
                <h4 class="font-weight-bold text-dark">Daftar Semua Pasien</h4>
            </div>
            <div class="col-auto">
                <a href="{{ route('pendaftaran') }}" class="btn btn-success text-white font-weight-bold">Tambah Pasien <i
                        class="fe fe-plus fe-12"></i></a>
            </div>
        </div>
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
        <div class="table-responsive">
            <table class="table datatables" id="dataTable-1">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Pasien</th>
                        <th>Jenis Kelamin</th>
                        <th>Usia</th>
                        <th>NIK</th>
                        <th>Alamat</th>
                        <th>Telepon</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pasiens as $pasien)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $pasien->name }}</td>
                            <td>{{ $pasien->jenis_kelamin }}</td>
                            <td>{{ $pasien->usia }}</td>
                            <td>{{ $pasien->nik }}</td>
                            <td>{{ $pasien->alamat }}</td>
                            <td>{{ $pasien->telepon }}</td>
                            <td>
                                <a type="button" class="btn  btn-primary" href="{{ route('pasien.edit', $pasien->id) }}"><i
                                        class="fe fe-edit fe-16"></i></a>
                                <button class="btn btn-danger" onclick="deleteActivity({{ $pasien->id }})"><i
                                        class="fe fe-trash fe-16"></i></button>
                                <form id="Hapus{{ $pasien->id }}" action="{{ route('pasien.destroy', $pasien->id) }}"
                                    method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
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
