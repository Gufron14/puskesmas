@extends('backend.layouts.app')

@section('content')
    <div class="container-fluid">
        <h4 class="font-weight-bold mb-4">Pendaftaran Pasien Baru</h4>
        <div class="card shadow rounded-lg">
            <div class="card-body">
                <form action="">
                    <div class="row">
                        <div class="form-group mb-4 col-md-10">
                            <label class="font-weight-bold" for="name">Nama Pasien</label>
                            <input type="text" id="name" name="nama_pasien" class="form-control"
                                placeholder="Masukan nama pasien">
                        </div>
                        <div class="form-group mb-4 col-md-2">
                            <label class="font-weight-bold" for="usia_pasien">Usia Pasien</label>
                            <input type="number" id="usia_pasien" name="usia_pasien" class="form-control" min="1"
                                placeholder="Masukan usia pasien">
                        </div>
                    </div>
                    <div class="form-group mb-4">
                        <label class="font-weight-bold" for="jenis_kelamin">Jenis Kelamin</label>
                        <div class="d-flex align-items-center" style="gap: 10px">
                            <div class="custom-control custom-radio">
                                <input type="radio" id="customRadio1" name="jenis_kelamin" class="custom-control-input"
                                    value="laki-laki">
                                <label class="custom-control-label" for="customRadio1">Laki-Laki</label>
                            </div>
                            <div class="custom-control custom-radio">
                                <input type="radio" id="customRadio2" name="jenis_kelamin" class="custom-control-input"
                                    checked="" value="perempuan">
                                <label class="custom-control-label" for="customRadio2">Perempuan</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-4">
                        <label class="font-weight-bold" for="nik">No KTP</label>
                        <input type="text" id="nik" name="nik" class="form-control" placeholder="Masukan 16 digit nik">
                    </div>
                    <div class="form-group mb-4">
                        <label class="font-weight-bold" for="alamat">Alamat Lengkap</label>
                        <textarea name="alamat" id="alamat" cols="30" rows="6" class="form-control"></textarea>
                    </div>
                    <div class="form-group mb-4">
                        <label class="font-weight-bold" for="nik">No Telepon</label>
                        <input type="text" id="telepon" name="telepon" class="form-control" placeholder="Masukan nomor telepon">
                    </div>
                    <div class="form-group float-right">
                        <button class="btn btn-primary" type="submit">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
