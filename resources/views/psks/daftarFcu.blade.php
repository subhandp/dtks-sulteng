@extends('layouts.master')

@section('head')
<meta name="csrf_token" id="csrf-token" content="{{ csrf_token() }}" />
@endsection


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
                   
                    <strong>Daftar FCU</strong>
                    <span class="float-right">
                        
                        <a class="btn btn-warning btn-sm my-1 mr-sm-1" href="{{ route('psks.fcu.create') }}" role="button" data-toggle="tooltip" title="Rekam data baru" data-offset="50%, 3"><i class="fas fa-edit"></i> Rekam</a>
                    </span>
                </h5>
                

                <hr>
            </div>
            </div>

            
            
            <form role="form"  id="form_tambah_kriteria" class="margin-bottom-0">
                <div class="form-row d-flex align-items-end">

                    <div class="form-group col-md-4">
                        <label><strong>Kabupaten/Kota :</strong></label>
                        <select class="form-control" id="kabupaten_kota" name="kabupaten_kota" data-placeholder="Semua Kabupaten/Kota" style="width: 100%" >
                        </select>
                    </div>


                   
                    <div class="form-group col-md-4">
                      <button class="btn btn-warning " id="btn-search" data-toggle="tooltip" title="Filter table" data-offset="50%, 3"><i class="fas fa-search"></i></button>
                      <button class="btn btn-warning " id="btn-download" data-toggle="tooltip" title="Download excel" data-offset="50%, 3"><i class="fas fa-download"></i></button>
                    </div>

                </div>
            </form>

            <div class="row table-responsive">
                <div class="col">
                
                <table id="tabel-data-psks" class="table table-bordered data-table display compact">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th width="100px">Action</th>
                            <th>KABUPATEN KOTA</th>
                            <th>NAMA</th>
                            <th>DESA/KELURAHAN</th>
                            <th>KECAMATAN</th>
                            <th>NO HP KETUA</th>
                            <th>EMAIL</th>
                            <th>NAMA KETUA</th>
                            <th>LEGALITAS</th>
                            <th>JMLH KEL. PIONIR</th>
                            <th>JMLH KEL. PLASMA</th>
                        
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>

                </div>
            </div>
        </div>
    </section>

    <div class="modal" id="modal-export-excel">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
    
                <!-- Modal Header -->
                <div class="modal-header">
                    <strong><h5 class="modal-title w-100 text-center">Download Excel</h5></strong>
                    
                    <i id="loading-spinner" class="fa fa-spinner fa-pulse fa-2x" style="color: orange"></i>
                    
                </div>
    
    
                <!-- Modal body -->
                <div class="modal-body" id="modal-filter">

                    <center>
                        <div id="download-link-container">

                        </div>
                    </center><br>

                    {{-- <h5>FILTER</h5> --}}
                    <table class="table table-bordered" id="table-filter">
                        <tr>
                          <th>Jenis PSKS</th>
                          <th>Kabupaten/Kota</th>
                          <th>Total Data</th>
                        </tr>
                        <tr>
                          <td id="td-jenis-psks">-</td>
                          <td id="td-kabupaten-kota">-</td>
                          <td id="td-total-data">-</td>

                        </tr>
                      </table>
                   
                </div>
    
            </div>
        </div>
    </div>


 @endsection


 @section('file-pond-data-import')
 <script>

    $('#load2').button('loading');

    var loadingSpinner = document.getElementById("loading-spinner");

   

    $.fn.select2.defaults.set( "theme", "bootstrap" );


    $('#kabupaten_kota').select2({
            allowClear: true,
            ajax: {
               url: "{{ route('cities') }}",
               dataType: 'json',
               processResults: function(data) {
                   console.log(data);
                  return {
                     results: $.map(data, function(item) {
                        return {
                           text: item.name,
                           id: item.id
                        }
                     })
                  };
               }
            }
        });

     var table = $('#tabel-data-psks').DataTable({
        lengthMenu: [[5, 10, -1], [5, 10, "All"]],
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('psks.fcu.datatables') }}",
            data: function (d) {
                d.kabupaten_kota = $('#kabupaten_kota').val(),
                d.search = $('input[type="search"]').val()
            }
            
        },
        columns: [
            {data: 'id', name: 'id'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
            {data: 'kabupaten_kota', name: 'kabupaten_kota'},
            {data: 'nama_fcu', name: 'nama_fcu'},
            {data: 'desa_kelurahan', name: 'desa_kelurahan'},
            {data: 'kecamatan', name: 'kecamatan'},
            {data: 'no_hp_ketua_fcu', name: 'no_hp_ketua_fcu'},
            {data: 'email', name: 'email'},
            {data: 'nama_ketua_fcu', name: 'nama_ketua_fcu'},
            {data: 'legalitas_fcu', name: 'legalitas_fcu'},
            {data: 'jumlah_keluarga_pionir', name: 'jumlah_keluarga_pionir'},
            {data: 'jumlah_keluarga_plasma', name: 'jumlah_keluarga_plasma'},

        ]
    });

    $('#btn-search').click(function($event){
        event.preventDefault();
        table.draw();
    });

    

    $('#btn-download').click(function($event){
        event.preventDefault();
        let tdKabupatenKota = document.getElementById("td-kabupaten-kota");
        let tdTotalData = document.getElementById("td-total-data");
        let tdJenisPsks = document.getElementById("td-jenis-psks");
        let downloadLinkContainer = document.getElementById("download-link-container");

        $('#modal-export-excel').modal('show');

        $.ajax({
            type: "GET",
            // responseType: 'blob', // important
            url:"{{ route('psks.download.excel.list') }}",
            data: {
                _token: $('#csrf-token')[0].content,
                kabupaten_kota: $('#kabupaten_kota').text(),
                jenis_psks: 'fcu',
                
            },
            beforeSend: function() {
                tdKabupatenKota.innerHTML = "";
                tdJenisPsks.innerHTML = "";
                tdTotalData.innerHTML = "";
                downloadLinkContainer.innerHTML = "";

                tdKabupatenKota.innerHTML = $('#kabupaten_kota').text();
                tdJenisPsks.innerHTML = 'FCU',
                loadingSpinner.style.display = "block";
            },
            success: function(data){
                console.log(data);
                tdTotalData.innerHTML = data.total;
                loadingSpinner.style.display = "none";

                let buttonLink = ""; 
                for (let index = 1; index <= data.last_page; index++) {
                    buttonLink += "<button onclick='downloadExcel("+index+")' class='btn btn-warning btn-sm btn-download-excel'><i class='fas fa-download'></i> <strong>"+index+".xlsx </strong></button> ";
                    downloadLinkContainer.innerHTML = buttonLink;
                    
                }
            },
            error: function(xhr) { // if error occured
                console.log(xhr.statusText + xhr.responseText,'Error Console');
            },   
        });

   
    });


    function downloadExcel(page=1){
        
        $.ajax({
            type: "GET",
            url:"{{ route('psks.download.excel') }}",
            data: {
                page : page,
                kabupaten_kota: $('#kabupaten_kota').text(),
            },
            beforeSend : function(){
                loadingSpinner.style.display = "block";
                let elems = document.getElementsByClassName("btn-download-excel");
                for(var i = 0; i < elems.length; i++) {
                    elems[i].disabled = true;
                }
            },
            success: function(data){
                console.log(data);
                download(data);
                loadingSpinner.style.display = "none";
                let elems = document.getElementsByClassName("btn-download-excel");
                for(var i = 0; i < elems.length; i++) {
                    elems[i].disabled = false;
                }
            }   
        });
    }

    


 </script>
 @endsection