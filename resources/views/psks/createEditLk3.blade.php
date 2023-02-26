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
                <strong>TAMBAH DATA LK3 - LEMBAGA KONSULTASI KESEJAHTERAAN KELUARGA</strong>
            </h5>
            
            <hr>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-10">
            <form method="post" action="{{ route('psks.lk3.store-create') }}" class="form" role="form">
                @csrf
                
                @isset($myhashid)
                    <input type="hidden" name="id" value="{{ $myhashid }}">
                @endisset
    

                    
                <div class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label">Nama LK3</label>
                <div class="col-lg-9">
                    @php
                        if(isset($lk3Data->nama_lk3))
                            $nama_lk3 = $lk3Data->nama_lk3;
                        else
                            $nama_lk3 = old('nama_lk3');
                    @endphp 

                    <input name="nama_lk3" class="form-control @error('nama_lk3') is-invalid @enderror" type="text" value="{{ $nama_lk3 }}">
                    @error('nama_lk3')
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
                    <label class="col-lg-3 col-form-label form-control-label">Nama Ketua LK3</label>
                    <div class="col-lg-9">
                        @php
                            if(isset($lk3Data->nama_ketua_lk3))
                                $nama_ketua_lk3 = $lk3Data->nama_ketua_lk3;
                            else
                                $nama_ketua_lk3 = old('nama_ketua_lk3');
                        @endphp 
    
                        <input name="nama_ketua_lk3" class="form-control @error('nama_ketua_lk3') is-invalid @enderror" type="text" value="{{ $nama_ketua_lk3 }}">
                        @error('nama_ketua_lk3')
                            <div class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">Email</label>
                        <div class="col-lg-9">
                            @php
                                if(isset($lk3Data->email))
                                    $email = $lk3Data->email;
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
                    <label class="col-lg-3 col-form-label form-control-label">No HP Ketua LK3</label>
                    <div class="col-lg-9">
                        @php
                            if(isset($lk3Data->no_hp_ketua_lk3))
                                $no_hp_ketua_lk3 = $lk3Data->no_hp_ketua_lk3;
                            else
                                $no_hp_ketua_lk3 = old('no_hp_ketua_lk3');
                        @endphp 

                        <input name="no_hp_ketua_lk3" class="form-control @error('no_hp_ketua_lk3') is-invalid @enderror" type="text" value="{{ $no_hp_ketua_lk3 }}">
                        @error('no_hp_ketua_lk3')
                            <div class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>
                    </div>


                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">Alamat Kantor</label>
                        <div class="col-lg-9">
                            @php
                                if(isset($lk3Data->alamat_kantor))
                                    $alamat_kantor = $lk3Data->alamat_kantor;
                                else
                                    $alamat_kantor = old('alamat_kantor');
                            @endphp 
                            <input name="alamat_kantor" class="form-control @error('alamat_kantor') is-invalid @enderror" type="text" value="{{ $alamat_kantor }}">
                            @error('alamat_kantor')
                                <div class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>
                    </div>
                    
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">Legalitas LK3</label>
                                <div class="col-lg-3">
                                    @php
                                        if(isset($lk3Data->legalitas_lk3))
                                            $legalitas_lk3 = $lk3Data->legalitas_lk3;
                                        else
                                            $legalitas_lk3 = old('legalitas_lk3');
                                    @endphp 
                
                                    <input name="legalitas_lk3" class="form-control @error('legalitas_lk3') is-invalid @enderror" type="text" value="{{ $legalitas_lk3 }}">
                                    @error('legalitas_lk3')
                                        <div class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label form-control-label">Jenis LK3</label>
                                    <div class="col-lg-3">
                                        @php
                                            if(isset($lk3Data->jenis_lk3))
                                                $jenis_lk3 = $lk3Data->jenis_lk3;
                                            else
                                                $jenis_lk3 = old('jenis_lk3');
                                        @endphp 
                    
                                        <input name="jenis_lk3" class="form-control @error('jenis_lk3') is-invalid @enderror" type="text" value="{{ $jenis_lk3 }}">
                                        @error('jenis_lk3')
                                            <div class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>
                                    </div>


                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label form-control-label">Jumlah Tenaga Professional</label>
                                    <div class="col-lg-3">
                                        @php
                                            if(isset($lk3Data->jumlah_tenaga_professional))
                                                $jumlah_tenaga_professional = $lk3Data->jumlah_tenaga_professional;
                                            else
                                                $jumlah_tenaga_professional = old('jumlah_tenaga_professional');
                                        @endphp 
                    
                                        <input name="jumlah_tenaga_professional" class="form-control @error('jumlah_tenaga_professional') is-invalid @enderror" type="text" value="{{ $jumlah_tenaga_professional }}">
                                        @error('jumlah_tenaga_professional')
                                            <div class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label form-control-label">Jumlah Klien</label>
                                        <div class="col-lg-3">
                                            @php
                                                if(isset($lk3Data->jumlah_klien))
                                                    $jumlah_klien = $lk3Data->jumlah_klien;
                                                else
                                                    $jumlah_klien = old('jumlah_klien');
                                            @endphp 
                        
                                            <input name="jumlah_klien" class="form-control @error('jumlah_klien') is-invalid @enderror" type="text" value="{{ $jumlah_klien }}">
                                            @error('jumlah_klien')
                                                <div class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </div>
                                            @enderror
                                        </div>
                                        </div>


                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label form-control-label">Jumlah Masalah kasus</label>
                                            <div class="col-lg-3">
                                                @php
                                                    if(isset($lk3Data->jumlah_masalah_kasus))
                                                        $jumlah_masalah_kasus = $lk3Data->jumlah_masalah_kasus;
                                                    else
                                                        $jumlah_masalah_kasus = old('jumlah_masalah_kasus');
                                                @endphp 
                            
                                                <input name="jumlah_masalah_kasus" class="form-control @error('jumlah_masalah_kasus') is-invalid @enderror" type="text" value="{{ $jumlah_masalah_kasus }}">
                                                @error('jumlah_masalah_kasus')
                                                    <div class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </div>
                                                @enderror
                                            </div>
                                            </div>


                    <div class="form-group row">
                    <label class="col-lg-3 col-form-label form-control-label"></label>
                    <div class="col-lg-9">
                        <a href="{{ route('psks.lk3.index')}}" class="btn btn-secondary">Batal </a>
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

