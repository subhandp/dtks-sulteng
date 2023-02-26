@extends('layouts.master')

@section('content')
@php
    // dd(old())
@endphp

@if(Session::has('success'))
<div class="alert alert-success text-center">
    {{Session::get('success')}}
</div>
@endif   

<section class="content card" style="padding: 10px 10px 10px 10px ">
    <div class="row">
        <div class="col">
            <h5>
                <strong>TAMBAH DATA FCU - FAMILY CARE UNIT</strong>
            </h5>
            
            <hr>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-10">
            <form method="post" action="{{ route('psks.fcu.store-create') }}" class="form" role="form">
                @csrf
                
                @isset($myhashid)
                    <input type="hidden" name="id" value="{{ $myhashid }}">
                @endisset
    

                    
                <div class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label">Nama FCU</label>
                <div class="col-lg-9">
                    @php
                        if(isset($fcuData->nama_fcu))
                            $nama_fcu = $fcuData->nama_fcu;
                        else
                            $nama_fcu = old('nama_fcu');
                    @endphp 

                    <input name="nama_fcu" class="form-control @error('nama_fcu') is-invalid @enderror" type="text" value="{{ $nama_fcu }}">
                    @error('nama_fcu')
                        <div class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </div>
                    @enderror
                </div>
                </div>


                <div class="form-group row">
                    <label class="col-lg-3 col-form-label form-control-label"><strong>Kabupaten/Kota :</strong></label>
                    <div class="col-lg-9">
                        <select class="form-control" id="kabupaten_kota" name="kabupaten_kota" data-placeholder="Semua Kabupaten/Kota" style="width: 100%" >
                            @if($kabupatenKotaEdit)
                                <option value="{{ $kabupatenKotaEdit->id }}" selected="selected"> {{ $kabupatenKotaEdit->name }} </option>
                            @endif
                        </select>
                    </div>
                    
                </div>

                <div class="form-group row">
                    <label class="col-lg-3 col-form-label form-control-label"><strong>Kecamatan :</strong></label>
                    <div class="col-lg-9">
                        <select class="form-control" id="kecamatan" name="kecamatan" data-placeholder="Semua Kecamatan" style="width: 100%" >
                            @if($kecamatanEdit)
                                <option value="{{ $kecamatanEdit->id }}" selected="selected"> {{ $kecamatanEdit->name }} </option>
                            @endif
                        </select>
                    </div>
                    
                </div>

                <div class="form-group row">
                    <label class="col-lg-3 col-form-label form-control-label"><strong>Desa/Kelurahan :</strong></label>
                    <div class="col-lg-9">
                        <select  class="form-control" id="desa_kelurahan" name="desa_kelurahan" data-placeholder="Semua Desa/kelurahan" style="width: 100%" >
                            @if($desaKelurahanEdit)
                                <option value="{{ $desaKelurahanEdit->id }}" selected="selected"> {{ $desaKelurahanEdit->name }} </option>
                            @endif
                        </select>
                    </div>
                    
                </div>



               

                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">Email</label>
                        <div class="col-lg-9">
                            @php
                                if(isset($fcuData->email))
                                    $email = $fcuData->email;
                                else
                                    $email = old('email');
                            @endphp 
        
                            <input name="email" class="form-control @error('email') is-invalid @enderror" type="text" value="{{ $email }}">
                            @error('email')
                                <div class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>
                        </div>
                    
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label form-control-label">Nama Ketua FCU</label>
                            <div class="col-lg-9">
                                @php
                                    if(isset($fcuData->nama_ketua_fcu))
                                        $nama_ketua_fcu = $fcuData->nama_ketua_fcu;
                                    else
                                        $nama_ketua_fcu = old('nama_ketua_fcu');
                                @endphp 
            
                                <input name="nama_ketua_fcu" class="form-control @error('nama_ketua_fcu') is-invalid @enderror" type="text" value="{{ $nama_ketua_fcu }}">
                                @error('nama_ketua_fcu')
                                    <div class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">No HP Ketua FCU</label>
                                <div class="col-lg-9">
                                    @php
                                        if(isset($fcuData->no_hp_ketua_fcu))
                                            $no_hp_ketua_fcu = $fcuData->no_hp_ketua_fcu;
                                        else
                                            $no_hp_ketua_fcu = old('no_hp_ketua_fcu');
                                    @endphp 
            
                                    <input name="no_hp_ketua_fcu" class="form-control @error('no_hp_ketua_fcu') is-invalid @enderror" type="text" value="{{ $no_hp_ketua_fcu }}">
                                    @error('no_hp_ketua_fcu')
                                        <div class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                </div>
                                </div>

                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">Legalitas FCU</label>
                                <div class="col-lg-3">
                                    @php
                                        if(isset($fcuData->legalitas_fcu))
                                            $legalitas_fcu = $fcuData->legalitas_fcu;
                                        else
                                            $legalitas_fcu = old('legalitas_fcu');
                                    @endphp 
                
                                    <input name="legalitas_fcu" class="form-control @error('legalitas_fcu') is-invalid @enderror" type="text" value="{{ $legalitas_fcu }}">
                                    @error('legalitas_fcu')
                                        <div class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                </div>
                                </div>


                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label form-control-label">Jumlah Keluarga Pionir</label>
                                    <div class="col-lg-3">
                                        @php
                                            if(isset($fcuData->jumlah_keluarga_pionir))
                                                $jumlah_keluarga_pionir = $fcuData->jumlah_keluarga_pionir;
                                            else
                                                $jumlah_keluarga_pionir = old('jumlah_keluarga_pionir');
                                        @endphp 
                    
                                        <input name="jumlah_keluarga_pionir" class="form-control @error('jumlah_keluarga_pionir') is-invalid @enderror" type="text" value="{{ $jumlah_keluarga_pionir }}">
                                        @error('jumlah_keluarga_pionir')
                                            <div class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label form-control-label">Jumlah Keluarga Plasma</label>
                                        <div class="col-lg-3">
                                            @php
                                                if(isset($fcuData->jumlah_keluarga_plasma))
                                                    $jumlah_keluarga_plasma = $fcuData->jumlah_keluarga_plasma;
                                                else
                                                    $jumlah_keluarga_plasma = old('jumlah_keluarga_plasma');
                                            @endphp 
                        
                                            <input name="jumlah_keluarga_plasma" class="form-control @error('jumlah_keluarga_plasma') is-invalid @enderror" type="text" value="{{ $jumlah_keluarga_plasma }}">
                                            @error('jumlah_keluarga_plasma')
                                                <div class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </div>
                                            @enderror
                                        </div>
                                        </div>


                    <div class="form-group row">
                    <label class="col-lg-3 col-form-label form-control-label"></label>
                    <div class="col-lg-9">
                        <a href="{{ route('psks.fcsr.index')}}" class="btn btn-secondary">Batal </a>
                        <input class="btn btn-primary" type="submit" value="Rekam">
                    </div>
                    </div>

                    
                </form>
               
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
    
                    </ul>
                </div>
                @endif

        </div>
    </div>

</section>
@endsection

@section('js-create-pmks')
<script>
    $.fn.select2.defaults.set( "theme", "bootstrap" );
    
    $("#kecamatan").select2();
    $("#desa_kelurahan").select2();


    $('#kabupaten_kota').select2({
            allowClear: true,
            ajax: {
               url: "{{ route('cities') }}?provinceID=72",
               dataType: 'json',
               processResults: function(data) {
                    $("#kecamatan").empty();
                    $("#desa_kelurahan").empty();
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

        let regencyID = $('#kabupaten_kota').val();
        if (regencyID) {
                $('#kecamatan').select2({
                allowClear: true,
                ajax: {
                url: "{{ route('districts') }}?regencyID=" + regencyID,
                dataType: 'json',
                processResults: function(data) {
                    $("#desa_kelurahan").empty();
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
        
        let districtID = $('#kecamatan').val();
        if (districtID) {
            $('#desa_kelurahan').select2({
                allowClear: true,
                ajax: {
                url: "{{ route('villages') }}?districtID=" + districtID,
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
        }


        


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

