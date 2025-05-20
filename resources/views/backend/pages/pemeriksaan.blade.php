@extends('backend.layouts.app')

@section('content')
    <div class="container-fluid">
        <h4 class="font-weight-bold mb-4">Pemeriksaan Pasien</h4>
        <div class="card shadow rounded-lg">
            <div class="card-body">
                <form action="">
                    <div class="row">
                        <div class="form-group col-md-6 mb-4">
                            <label for="simple-select2">Pilih Pasien</label>
                            <select class="form-control select2" id="simple-select2">
                                <optgroup>
                                    <option value="CA">California</option>
                                    <option value="OR">Oregon</option>
                                    <option value="WA">Washington</option>
                                </optgroup>
                            </select>
                        </div> <!-- form-group -->
                        <div class="form-group col-md-6 mb-4">
                            <label class="font-weight-bold" for="tanggal_pemeriksaan">Tanggal Pemeriksaan</label>
                            <input type="date" id="tanggal_pemeriksaan" name="tanggal_pemeriksaan" class="form-control">
                        </div>
                    </div>
                    <div class="form-group mb-4">
                        <label class="font-weight-bold" for="tensi">Tensi Darah</label>
                        <input type="text" id="tensi" name="tensi" class="form-control"
                            placeholder="Masukan tensi darah">
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
                        <label class="font-weight-bold" for="resep">Resep Obat</label>
                        <input type="text" id="resep" name="resep" class="form-control"
                            placeholder="Masukan resep obat">
                    </div>
                    <div class="form-group mb-4">
                        <label class="font-weight-bold" for="biaya">Biaya Pemeriksaan</label>
                        <input type="text" id="biaya" name="biaya" class="form-control"
                            placeholder="Masukan biaya obat">
                    </div>
                    <div class="form-group float-right">
                        <button class="btn btn-primary" type="submit">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
