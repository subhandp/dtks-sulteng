@extends('layouts.master')
@section('content')
    <section class="content card" style="padding: 10px 10px 10px 10px ">
        <div class="box">
                @if(session('sukses'))
                <div class="alert alert-success" role="alert">
                    Data berhasil ditambahkan <a href="{{session('sukses')}}">Lihat</a>
                </div>
                @endif

                @if(session('sukseshapus'))
                <div class="alert alert-success" role="alert">
                    {{session('sukseshapus')}}
                </div>
                @endif
            <div class="row">
                <div class="col">
                <h5>
                    <strong>Daftar PMKS </strong>
                    <span class="float-right">
                        <a class="btn btn-warning btn-sm my-1 mr-sm-1" href="create" role="button"><i class="fas fa-edit"></i> Rekam</a>
                    </span>
                </h5>
                

                <hr>
            </div>
            </div>
        
            <div class="row table-responsive">
                <div class="col">
                <table class="table table-hover table-head-fixed" id="tabelSuratkeluar" style="width:100%">
                    <thead>
                        <tr class="bg-light">
                        <th>NO.</th>
                        <th>IDDTKS</th>
                        <th>KAB/KOTA</th>
                        <th>KECAMATAN</th>
                        <th>DESA KELURAHAN</th>
                        <th>NIK</th>
                        <th>NAMA</th>
                        <th>TGL LAHIR</th>
                        <th>JK</th>


                        </tr>
                    </thead>
                    <tbody>

                        <?php $no = 0;?>
                        @foreach($data_daftar_pmks as $daftar)
                        <?php $no++ ;?>
                        <tr>
                            <td>{{$no}}</td>
                            <td>{{ $daftar->iddtks }}</td>
                            <td>{{ $daftar->kab_kota }}</td>
                            <td>{{ $daftar->kecamatan }}</td>
                            <td>{{ $daftar->desa_kelurahan }}</td>
                            <td>{{ $daftar->nik }}</td>
                            <td>{{ $daftar->nama }}</td>
                            <td>{{ $daftar->tgl_lahir }}</td>
                            <td>{{ $daftar->jenis_kelamin }}</td>

                            <td>
                                <a href="#" class="btn btn-default btn-sm my-1 mr-sm-1 btn-block"><i class="fas fa-eye"></i></a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </section>
 @endsection

