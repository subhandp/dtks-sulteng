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
                        <a class="btn btn-warning btn-sm my-1 mr-sm-1" href="{{ route('pmks.create') }}" role="button"><i class="fas fa-edit"></i> Rekam</a>
                    </span>
                </h5>
                

                <hr>
            </div>
            </div>
        
            <div class="row table-responsive">
                <div class="col">
                
                <table id="tabel-data-pmks" class="table table-bordered data-table display compact">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>ID DTKS</th>
                            <th>JENIS PMKS</th>
                            <th>KABUPATEN/KOTA</th>
                            <th>KECAMATAN</th>
                            <th>DESA/KELURAHAN</th>
                            <th>ALAMAT</th>
                            <th>NOMOR KK</th>
                            <th>NOMOR NIK</th>
                            <th>NAMA</th>
                            <th>TANGGAL LAHIR</th>
                            <th>JENIS KELAMIN</th>
                            <th>TAHUN DATA</th>
                            
                            <th width="100px">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>

                </div>
            </div>
        </div>
    </section>
 @endsection


 @section('file-pond-data-import')
 <script>
    //  $("#tabel-import-data").DataTable();

  
     var table = $('#tabel-data-pmks').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('pmks.datapmks') }}",
        columns: [
            {data: 'id', name: 'id'},
            {data: 'iddtks', name: 'iddtks'},
            {data: 'jenis_pmks', name: 'jenis_pmks'},
            {data: 'kabupaten_kota', name: 'kabupaten_kota'},
            {data: 'kecamatan', name: 'kecamatan'},
            {data: 'desa_kelurahan', name: 'desa_kelurahan'},
            {data: 'alamat', name: 'alamat'},
            {data: 'nomor_kk', name: 'nomor_kk'},
            {data: 'nomor_nik', name: 'nomor_nik'},
            {data: 'nama', name: 'nama'},
            {data: 'tanggal_lahir', name: 'tanggal_lahir'},
            {data: 'jenis_kelamin', name: 'jenis_kelamin'},
            {data: 'tahun_data', name: 'tahun_data'},
           

            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });

 </script>
 @endsection