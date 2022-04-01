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
 @endsection


 @section('file-pond-data-import')
 <script>
    //  $("#tabel-import-data").DataTable();
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