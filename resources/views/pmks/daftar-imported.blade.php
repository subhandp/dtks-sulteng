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
                    <strong>DATA IMPORTED CSV </strong>
                </h5>
                
                <hr>
            </div>
            </div>
        
            <div class="row table-responsive">
                <div class="col">

                <table id="tabel-import-data" class="table table-bordered data-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NO TIKET</th>
                            <th>ID DTKS</th>
                            <th>KABUPATEN/KOTA</th>
                            <th>NOMOR NIK</th>
                            <th>NAMA</th>
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

     var table = $('#tabel-import-data').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('pmks.dataimport') }}",
        columns: [
            {data: 'id', name: 'id'},
            {data: 'no_tiket', name: 'no_tiket'},
            {data: 'iddtks', name: 'iddtks'},
            {data: 'kabupaten_kota', name: 'kabupaten_kota'},
            {data: 'nomor_nik', name: 'nomor_nik'},
            {data: 'nama', name: 'nama'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });

 </script>
 @endsection