@extends('backend.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row align-items-center justify-content-between mb-4">
            <div class="col">
                <h4 class="font-weight-bold text-dark">Data Antrian</h4>
            </div>

            <div class="col-auto">
                @if ($antrian && $antrian->status === 'on')
                    <a href="{{ route('antrian.create') }}" class="btn btn-success text-white font-weight-bold">
                        Tambah Data Antrian <i class="fe fe-plus fe-12"></i>
                    </a>
                @elseif ($antrian && $antrian->status === 'off')
                    <button type="button" onclick="showAntrianTutup()" class="btn btn-success text-white font-weight-bold">
                        Tambah Data Antrian <i class="fe fe-plus fe-12"></i>
                    </button>
                @else
                    <button type="button" onclick="showBukaAntrian()" class="btn btn-warning text-white font-weight-bold">
                        Buka Antrian <i class="fe fe-unlock fe-12"></i>
                    </button>
                @endif
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
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="filterTanggal" class="form-label">Filter Tanggal Antrian</label>
                <input type="date" id="filterTanggal" class="form-control" onchange="filterTabel()">
            </div>
        </div>
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
                        <th>Nomor Antrian</th>
                        <th>Tanggal Antrian</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pasiens as $pasien)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $pasien->user->name }}</td>
                            <td>{{ $pasien->user->jenis_kelamin }}</td>
                            <td>{{ $pasien->user->usia }}</td>
                            <td>{{ $pasien->user->nik }}</td>
                            <td>{{ $pasien->user->alamat }}</td>
                            <td>{{ $pasien->user->telepon }}</td>
                            <td>Antrian Nomor {{ $pasien->nomor_antrian }}</td>
                            <td>{{ $pasien->tanggal_antrian }}</td>
                            <td>
                                @if ($pasien->status === 'menunggu')
                                    <span class="badge bg-warning text-white px-3 py-2">MENUNGGU</span>
                                @elseif ($pasien->status === 'selesai')
                                    <span class="badge bg-success text-white px-3 py-2">DIPERIKSA</span>
                                @else
                                    <span class="badge bg-secondary">-</span>
                                @endif
                            </td>
                            <td>
                                {{-- <a type="button" class="btn  btn-primary"
                                    href="{{ route('pasien.edit', $pasien->id) }}"><i class="fe fe-edit fe-16"></i></a> --}}
                                <form action="{{ route('antrian.destroy', $pasien->id) }}" method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus pasien {{ $pasien->user->name }} dari antrian?')"
                                    style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fe fe-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
    <script>
        function showAntrianTutup() {
            Swal.fire({
                icon: 'warning',
                title: 'Antrian Ditutup',
                text: 'Saat ini antrian sedang tutup. Silakan coba lagi nanti.',
                confirmButtonText: 'OK'
            });
        }

        function showBukaAntrian() {
            Swal.fire({
                title: 'Buka Antrian',
                text: 'Antrian belum diatur. Silakan tentukan jumlah kuota antrian hari ini.',
                input: 'number',
                inputAttributes: {
                    min: 1,
                    max: 100,
                    placeholder: 'Masukkan jumlah kuota (1-100)'
                },
                inputValidator: (value) => {
                    if (!value) {
                        return 'Kuota antrian harus diisi!';
                    }
                    if (value < 1 || value > 100) {
                        return 'Kuota antrian harus antara 1-100!';
                    }
                },
                showCancelButton: true,
                confirmButtonText: 'Buka Antrian',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                showLoaderOnConfirm: true,
                preConfirm: async (kuota) => {
                    try {
                        const response = await fetch('{{ route('antrian.buka') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                jumlah: kuota
                            })
                        });

                        const result = await response.json();

                        if (!response.ok || !result.success) {
                            throw new Error(result.message || 'Terjadi kesalahan pada server');
                        }

                        return result;
                    } catch (error) {
                        Swal.showValidationMessage(
                            `Gagal membuka antrian: ${error.message}`
                        );
                    }
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: result.value.message,
                        confirmButtonText: 'OK'
                    }).then(() => {
                        // Reload halaman untuk memperbarui status
                        window.location.reload();
                    });
                }
            });
        }
    </script>
    <script>
        function filterTabel() {
            let inputTanggal = document.getElementById('filterTanggal').value;
            let rows = document.querySelectorAll('#dataTable-1 tbody tr');

            rows.forEach(row => {
                let tanggal = row.children[8].textContent.trim(); // Kolom ke-9 (index 8)
                if (inputTanggal === '' || tanggal.includes(inputTanggal)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
    </script>
@endsection
