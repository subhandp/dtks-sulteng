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
                
                <table id="tabel-data-pmks" class="table table-bordered data-table">
                    <thead>
                        <tr>
                            <th>No</th>
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

  
     var table = $('#tabel-data-pmks').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('pmks.datapmks') }}",
        columns: [
            {data: 'id', name: 'id'},
            {data: 'iddtks', name: 'iddtks'},
            {data: 'kabupaten_kota', name: 'kabupaten_kota'},
            {data: 'nomor_nik', name: 'nomor_nik'},
            {data: 'nama', name: 'nama'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });
    

 </script>
 @endsection