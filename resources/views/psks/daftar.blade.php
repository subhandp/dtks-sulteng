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
                   
                    <strong>Daftar PMKS </strong>
                    <span class="float-right">
                        
                        <a class="btn btn-warning btn-sm my-1 mr-sm-1" href="{{ route('pmks.create') }}" role="button"><i class="fas fa-edit"></i> Rekam</a>
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
                            {{-- <option value="">Semua Kab/Kota</option>
                            @foreach ($kabupatenKota as $kk)
                                <option value="{{ $kk->name }}"> {{ $kk->name }} </option>
                            @endforeach --}}
                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label><strong>Kecamatan :</strong></label>
                        <select class="form-control" id="kecamatan" name="kecamatan" data-placeholder="Semua Kecamatan" style="width: 100%" >
                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label><strong>Desa/Kelurahan :</strong></label>
                        <select  class="form-control" id="desa_kelurahan" name="desa_kelurahan" data-placeholder="Semua Desa/kelurahan" style="width: 100%" >
                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label><strong>Jenis PMKS :</strong></label>
                        
                        <select class="@error('jenis_pmks') is-invalid @enderror" id="jenis_pmks" name="jenis_pmks"  data-placeholder="Pilih Jenis PMKS" style="width: 100%"  >
                            <option value="">
                                Semua Jenis PMKS
                            </option>
                            @foreach ($jenisPmks as $pmks)
                            @if (old('jenis_pmks') == $pmks->jenis )
                                <option value="{{ $pmks->jenis }}" selected="selected">
                                    {{ $pmks->jenis }}
                                </option>
                            @else
                            
                                <option value="{{ $pmks->jenis }}">
                                    {{ $pmks->jenis }}
                                </option>
                            @endif
                            
                            @endforeach
                        </select>
                        
                    </div>

                    <div class="form-group col-md-4">
                        <label><strong>Tahun data :</strong></label>
                        <select class="form-control" id="tahun_data" name="tahun_data" data-placeholder="Semua Tahun Data" style="width: 100%" >
                            <option value=""></option>


                            @for ($i = date('Y'); $i >= 2010; $i--)
                                <option value="{{ $i }}"> {{ $i }} </option>
                            @endfor

                                
                        </select>
                    </div>

                   
                    <div class="form-group col-md-4">
                      <button class="btn btn-warning " id="btn-search"><i class="fas fa-search"></i></button>
                      <button class="btn btn-warning " id="btn-download"><i class="fas fa-download"></i></button>
                    </div>

                </div>
            </form>

            <div class="row table-responsive">
                <div class="col">
                
                <table id="tabel-data-pmks" class="table table-bordered data-table display compact">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>ID DTKS</th>
                            <th>JENIS PMKS</th>
                            <th>KABUPATEN/KOTA</th>
                            {{-- <th>KECAMATAN</th> --}}
                            {{-- <th>DESA/KELURAHAN</th> --}}
                            {{-- <th>ALAMAT</th> --}}
                            {{-- <th>NOMOR KK</th> --}}
                            <th>NOMOR NIK</th>
                            <th>NAMA</th>
                            {{-- <th>TANGGAL LAHIR</th> --}}
                            {{-- <th>JENIS KELAMIN</th> --}}
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
                    {{-- <div class="spinner-grow" role="status">
                        <span class="sr-only">Loading...</span>
                      </div> --}}
                    
                    {{-- <button class="btn btn-primary" type="button" disabled>
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Loading...
                    </button> --}}

                    <center>
                        <div id="download-link-container">

                        </div>
                    </center><br>

                    {{-- <h5>FILTER</h5> --}}
                    <table class="table table-bordered" id="table-filter">
                        <tr>
                          <th>Total Data</th>
                          <th>Kabupaten/Kota</th>
                          <th>Kecamatan </th>
                          <th>Desa/Kelurahan</th>
                          <th>Jenis PMKS</th>
                          <th>Tahun data</th>
                        </tr>
                        <tr>
                          <td id="td-total-data">-</td>
                          <td id="td-kabupaten-kota">-</td>
                          <td id="td-kecamatan">-</td>
                          <td id="td-desa-kelurahan">-</td>
                          <td id="td-jenis-pmks">-</td>
                          <td id="td-tahun-data">-</td>

                        </tr>
                      </table>

                   
                   
                </div>
    
            </div>
        </div>
    </div>


 @endsection


 @section('file-pond-data-import')
 <script>
    //  $("#tabel-import-data").DataTable();
    $('#load2').button('loading');
    // $('#load2').on('click', function() {
    //     console.log('masuk');
    //     var $this = $(this);
    //     $this.button('loading');
    //     //     setTimeout(function() {
    //     //     $this.button('reset');
    //     // }, 8000);
    // });
    
    var loadingSpinner = document.getElementById("loading-spinner");

    function downloadExcel(page=1){
        
        $.ajax({
            type: "GET",
            url:"{{ route('download-excel') }}",
            data: {
                page : page,
                kabupaten_kota: $('#kabupaten_kota').val(),
                kecamatan: $('#kecamatan').val(),
                desa_kelurahan: $('#desa_kelurahan').val(),
                jenis_pmks: $('#jenis_pmks').val(),
                tahun_data: $('#tahun_data').val(),
            },
            beforeSend : function(){
                loadingSpinner.style.display = "block";
                let elems = document.getElementsByClassName("btn-download-excel");
                for(var i = 0; i < elems.length; i++) {
                    elems[i].disabled = true;
                }
            },
            success: function(data){
                // a.href = response.file; 
                // a.download = response.name;
                // document.body.appendChild(a);
                // a.click();
                // a.remove(); 
                // const url = window.URL.createObjectURL(new Blob([data]));
                // const link = document.createElement('a');
                // link.setAttribute('download', 'file.xlsx');
                // document.body.appendChild(link);
                // link.click();         
                // console.log(data);
                download(data);
                loadingSpinner.style.display = "none";
                let elems = document.getElementsByClassName("btn-download-excel");
                for(var i = 0; i < elems.length; i++) {
                    elems[i].disabled = false;
                }
            }   
        });
    }

    $.fn.select2.defaults.set( "theme", "bootstrap" );

    $("#tahun_data").select2();
    $("#kecamatan").select2();
    $("#desa_kelurahan").select2();
    $("#jenis_pmks").select2();

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

    // $("#kabupaten_kota").select2();
     var table = $('#tabel-data-pmks').DataTable({
        lengthMenu: [[5, 10, -1], [5, 10, "All"]],
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('pmks.datapmks') }}",
            data: function (d) {
                d.kabupaten_kota = $('#kabupaten_kota').val(),
                d.kecamatan = $('#kecamatan').val(),
                d.desa_kelurahan = $('#desa_kelurahan').val(),
                d.jenis_pmks = $('#jenis_pmks').val(),
                d.tahun_data = $('#tahun_data').val(),
                d.search = $('input[type="search"]').val()
            }
            
        },
        columns: [
            {data: 'id', name: 'id'},
            {data: 'iddtks', name: 'iddtks'},
            {data: 'jenis_pmks', name: 'jenis_pmks'},
            {data: 'kabupaten_kota', name: 'kabupaten_kota'},
            // {data: 'kecamatan', name: 'kecamatan'},
            // {data: 'desa_kelurahan', name: 'desa_kelurahan'},
            // {data: 'alamat', name: 'alamat'},
            // {data: 'nomor_kk', name: 'nomor_kk'},
            {data: 'nomor_nik', name: 'nomor_nik'},
            {data: 'nama', name: 'nama'},
            // {data: 'tanggal_lahir', name: 'tanggal_lahir'},
            // {data: 'jenis_kelamin', name: 'jenis_kelamin'},
            {data: 'tahun_data', name: 'tahun_data'},
           

            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });

  
    $('#btn-search').click(function($event){
        event.preventDefault();
        table.draw();
    });

    

    // $('#btn-download-excel').on('click',function(){
    //     var query = {
    //         kabupaten_kota: $('#kabupaten_kota').val(),
    //         kecamatan: $('#kecamatan').val(),
    //         kelurahan_desa: $('#kelurahan_desa').val(),
    //         jenis_pmks: $('#jenis_pmks').val(),
    //         tahun_data: $('#tahun_data').val(),

    //     }


    //     var url = "{{ route('download-excel') }}?" + $.param(query)
    //     window.open(url,"_blank");
    // });
    
 

    $('#btn-download').click(function($event){
        event.preventDefault();
        let tdKabupatenKota = document.getElementById("td-kabupaten-kota");
        let tdKecamatan = document.getElementById("td-kecamatan");
        let tdDesaKelurahan = document.getElementById("td-desa-kelurahan");
        let tdJenisPmks = document.getElementById("td-jenis-pmks");
        let tdTahunData = document.getElementById("td-tahun-data");
        let tdTotalData = document.getElementById("td-total-data");
        let downloadLinkContainer = document.getElementById("download-link-container");

        $('#modal-export-excel').modal('show');

        $.ajax({
            type: "POST",
            // responseType: 'blob', // important
            url:"{{ route('get-download-excel') }}",
            data: {
                _token: $('#csrf-token')[0].content,
                kabupaten_kota: $('#kabupaten_kota').text(),
                kecamatan: $('#kecamatan').text(),
                desa_kelurahan: $('#desa_kelurahan').text(),
                jenis_pmks: $('#jenis_pmks').val(),
                tahun_data: $('#tahun_data').val(),
            },
            beforeSend: function() {
                tdKabupatenKota.innerHTML = "";
                tdKecamatan.innerHTML = "";
                tdDesaKelurahan.innerHTML = "";
                tdJenisPmks.innerHTML = "";
                tdTahunData.innerHTML = "";
                tdTotalData.innerHTML = "";
                downloadLinkContainer.innerHTML = "";

                tdKabupatenKota.innerHTML = $('#kabupaten_kota').text();
                tdKecamatan.innerHTML = $('#kecamatan').text(),
                tdDesaKelurahan.innerHTML = $('#desa_kelurahan').text(),
                tdJenisPmks.innerHTML = $('#jenis_pmks').val(),
                tdTahunData.innerHTML = $('#tahun_data').val(),
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

        // $.ajax({
        //     type: "GET",
        //     // responseType: 'blob', // important
        //     url:"{{ route('download-excel') }}",
        //     data: {
        //         parameter : ['kosong']
        //     },
        //     success: function(data){
        //         // a.href = response.file; 
        //         // a.download = response.name;
        //         // document.body.appendChild(a);
        //         // a.click();
        //         // a.remove(); 
        //         // const url = window.URL.createObjectURL(new Blob([data]));
        //         // const link = document.createElement('a');
        //         // link.setAttribute('download', 'file.xlsx');
        //         // document.body.appendChild(link);
        //         // link.click();         
        //         // console.log(data);
        //         download(data);
        //     }   
        // });
    });

    


         $('#kabupaten_kota').change(function() {
            //clear select
            $("#kecamatan").empty();
            $("#desa_kelurahan").empty();
            //set id
            let regencyID = $(this).val();
            if (regencyID) {
               $('#kecamatan').select2({
                  allowClear: true,
                  ajax: {
                     url: "{{ route('districts') }}?regencyID=" + regencyID,
                     dataType: 'json',
                     processResults: function(data) {
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
            } else {
               $("#kecamatan").empty();
               $("#desa_kelurahan").empty();
            }
         });


         $('#kecamatan').change(function() {
            //clear select
            $("#desa_kelurahan").empty();
            //set id
            let districtID = $(this).val();
            if (districtID) {
               $('#desa_kelurahan').select2({
                  allowClear: true,
                  ajax: {
                     url: "{{ route('villages') }}?districtID=" + districtID,
                     dataType: 'json',
                     delay: 250,
                     processResults: function(data) {
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
            }
         });
         //  Event on change select district:End

         // EVENT ON CLEAR

         $('#kabupaten_kota').on('select2:clear', function(e) {
            $("#kecamatan").select2();
            $("#desa_kelurahan").select2();
         });

         $('#kecamatan').on('select2:clear', function(e) {
            $("#desa_kelurahan").select2();
         });


 </script>
 @endsection