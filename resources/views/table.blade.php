@extends('layouts.template')

@section('content')
<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Data Mahasiswa Bermasalah</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Nomor</th>
                            <th scope="col">Nama</th>
                            <th scope="col">Kelas</th>
                            <th scope="col">NIM</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Agus</td>
                            <td>A</td>
                            <td>23/519160/SV/23KON</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>fufufafa</td>
                            <td>A</td>
                            <td>23/ME0/SV/23KON</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Gus Miftah</td>
                            <td>A</td>
                            <td>23/52926/SV/23GOB</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer text-center">
            <small class="text-muted">Data diperbarui secara berkala</small>
        </div>
    </div>
</div>

@endsection
