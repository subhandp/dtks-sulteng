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
                <strong>TAMBAH DATA WKSBM - WAHANA KESEJAHTERAAN SOSIAL BERBASIS MASYARAKAT</strong>
            </h5>
            
            <hr>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-10">
            <form method="post" action="{{ route('psks.wkskbm.store-create') }}" class="form" role="form">
                @csrf
                
                @isset($myhashid)
                    <input type="hidden" name="id" value="{{ $myhashid }}">
                @endisset
    

                    
                <div class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label">Nama WKSKBM</label>
                <div class="col-lg-9">
                    @php
                        if(isset($wkskbmData->nama_wksb))
                            $nama_wksb = $wkskbmData->nama_wksb;
                        else
                            $nama_wksb = old('nama_wksb');
                    @endphp 

                    <input name="nama_wksb" class="form-control @error('nama_wksb') is-invalid @enderror" type="text" value="{{ $nama_wksb }}">
                    @error('nama_wksb')
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
                    <label class="col-lg-3 col-form-label form-control-label">No HP</label>
                    <div class="col-lg-9">
                        @php
                            if(isset($wkskbmData->no_hp))
                                $no_hp = $wkskbmData->no_hp;
                            else
                                $no_hp = old('no_hp');
                        @endphp 

                        <input name="no_hp" class="form-control @error('no_hp') is-invalid @enderror" type="text" value="{{ $no_hp }}">
                        @error('no_hp')
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
                                if(isset($wkskbmData->email))
                                    $email = $wkskbmData->email;
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
                            <label class="col-lg-3 col-form-label form-control-label">Nama Ketua WKSBM</label>
                            <div class="col-lg-9">
                                @php
                                    if(isset($wkskbmData->nama_ketua_wksbm))
                                        $nama_ketua_wksbm = $wkskbmData->nama_ketua_wksbm;
                                    else
                                        $nama_ketua_wksbm = old('nama_ketua_wksbm');
                                @endphp 
            
                                <input name="nama_ketua_wksbm" class="form-control @error('nama_ketua_wksbm') is-invalid @enderror" type="text" value="{{ $nama_ketua_wksbm }}">
                                @error('nama_ketua_wksbm')
                                    <div class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">Legalitas WKSKBM</label>
                                <div class="col-lg-3">
                                    @php
                                        if(isset($wkskbmData->legalitas_wksbm))
                                            $legalitas_wksbm = $wkskbmData->legalitas_wksbm;
                                        else
                                            $legalitas_wksbm = old('legalitas_wksbm');
                                    @endphp 
                
                                    <input name="legalitas_wksbm" class="form-control @error('legalitas_wksbm') is-invalid @enderror" type="text" value="{{ $legalitas_wksbm }}">
                                    @error('legalitas_wksbm')
                                        <div class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                </div>
                                </div>


                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label form-control-label">Jumlah Pengurus</label>
                                    <div class="col-lg-3">
                                        @php
                                            if(isset($wkskbmData->jumlah_pengurus))
                                                $jumlah_pengurus = $wkskbmData->jumlah_pengurus;
                                            else
                                                $jumlah_pengurus = old('jumlah_pengurus');
                                        @endphp 
                    
                                        <input name="jumlah_pengurus" class="form-control @error('jumlah_pengurus') is-invalid @enderror" type="text" value="{{ $jumlah_pengurus }}">
                                        @error('jumlah_pengurus')
                                            <div class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label form-control-label">Jumlah Anggota</label>
                                        <div class="col-lg-3">
                                            @php
                                                if(isset($wkskbmData->jumlah_anggota))
                                                    $jumlah_anggota = $wkskbmData->jumlah_anggota;
                                                else
                                                    $jumlah_anggota = old('jumlah_anggota');
                                            @endphp 
                        
                                            <input name="jumlah_anggota" class="form-control @error('jumlah_anggota') is-invalid @enderror" type="text" value="{{ $jumlah_anggota }}">
                                            @error('jumlah_anggota')
                                                <div class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </div>
                                            @enderror
                                        </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label form-control-label">Jenis kegiatan WKSKBM</label>
                                            <div class="col-lg-3">
                                                @php
                                                    if(isset($wkskbmData->jenis_kegiatan))
                                                        $jenis_kegiatan = $wkskbmData->jenis_kegiatan;
                                                    else
                                                        $jenis_kegiatan = old('jenis_kegiatan');
                                                @endphp 
                            
                                                <input name="jenis_kegiatan" class="form-control @error('jenis_kegiatan') is-invalid @enderror" type="text" value="{{ $jenis_kegiatan }}">
                                                @error('jenis_kegiatan')
                                                    <div class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </div>
                                                @enderror
                                            </div>
                                            </div>


                    <div class="form-group row">
                    <label class="col-lg-3 col-form-label form-control-label"></label>
                    <div class="col-lg-9">
                        <a href="{{ route('psks.wkskbm.index')}}" class="btn btn-secondary">Batal </a>
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

