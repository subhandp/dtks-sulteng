@extends('layouts.master')

@section('content')

<section class="content card" style="padding: 10px 10px 10px 10px ">
    <div class="row">
        <div class="col">
            <h5>
                <strong>EDIT DATA PMKS </strong>
            </h5>
            
            <hr>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-10">
            
            @if(Session::has('success'))
                <div class="alert alert-success text-center">
                    {{Session::get('success')}}
                </div>
            @endif   
            
            
            

                <form method="post" action="{{ route('pmks.store-edit') }}" class="form" role="form">
                    @csrf

                    <input type="hidden" name="id" value="{{ $myhashid }}">
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">Tahun Data</label>
                        <div class="col-lg-5">
                            
                                <input placeholder="tttt" type="text" class="form-control bg-light @error('tahun_data') is-invalid @enderror" name="tahun_data" id="input-tahun-data" 
                                    value="{{ $pmksData->tahun_data }}"
                                >

                                @error('tahun_data')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">Jenis PMKS</label>
                        <div class="col-lg-9">
                            <select class="@error('jenis_pmks') is-invalid @enderror" id="jenis_pmks" name="jenis_pmks"  data-placeholder="Pilih Jenis PMKS" style="width: 100%"  >
                                @foreach ($jenisPmks as $pmks)
                                @if ($pmksData->jenis_pmks == $pmks->jenis )
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
                            @error('jenis_pmks')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">ID DTKS</label>
                        <div class="col-lg-9">
                            <input name="iddtks" class="form-control @error('iddtks') is-invalid @enderror" type="text" value="{{ $pmksData->iddtks }}">
                            @error('iddtks')
                                <div class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>
                    </div>

                
                  
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">Provinsi</label>
                        <div class="col-lg-9">
                            <select class="form-control @error('provinsi') is-invalid @enderror" id="provinsi" name="provinsi" data-placeholder="Pilih Provinsi" style="width: 100%" >
                                @isset($provinces)
                                    <option value="{{ $provinces->id }}" selected="selected"> {{ $provinces->name }}</option>
                                @endisset
                            </select>
                            @error('provinsi')
                                <div class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>
                        </div>

                    <div class="form-group row">
                    <label class="col-lg-3 col-form-label form-control-label">Kabupaten/Kota</label>
                    <div class="col-lg-9">
                        <select class="form-control @error('kabupaten_kota') is-invalid @enderror" id="kabupaten_kota" name="kabupaten_kota" data-placeholder="Pilih Kabupaten/Kota" style="width: 100%" >
                        @isset($kabupatenKotaCreate)
                            @foreach ( $kabupatenKotaCreate as $kabupatenKota)
                                @if ($pmksData->kabupaten_kota == $kabupatenKota->name)
                                    <option value="{{ $kabupatenKota->id }}" selected="selected">
                                        {{ $kabupatenKota->name }}
                                    </option>
                                @endif
                                <option value="{{ $kabupatenKota->id }}">
                                    {{ $kabupatenKota->name }}
                                </option>
                            @endforeach
                        @endisset
                        </select>
                        @error('kabupaten_kota')
                            <div class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">Kecamatan</label>
                        <div class="col-lg-9">
                            <select class="form-control @error('kecamatan') is-invalid @enderror" id="kecamatan" name="kecamatan" data-placeholder="Pilih Kecamatan" style="width: 100%" >
                                @isset($kecamatanCreate)
                                    @foreach ( $kecamatanCreate as $kecamatan)
                                        @if ($pmksData->kecamatan == $kecamatan->name)
                                            <option value="{{ $kecamatan->id }}" selected="selected">
                                                {{ $kecamatan->name }}
                                            </option>
                                        @endif
                                        <option value="{{ $kecamatan->id }}">
                                            {{ $kecamatan->name }}
                                        </option>
                                    @endforeach
                                @endisset
                            </select>
                            @error('kecamatan')
                                <div class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">Desa/kelurahan</label>
                        <div class="col-lg-9">
                            <select  class="form-control @error('desa_kelurahan') is-invalid @enderror" id="desa_kelurahan" name="desa_kelurahan" data-placeholder="Pilih Desa/kelurahan" style="width: 100%" >
                                @isset($desaKelurahanCreate)
                                @foreach ( $desaKelurahanCreate as $desaKelurahan)
                                    @if ($pmksData->desa_kelurahan == $desaKelurahan->name)
                                        <option value="{{ $desaKelurahan->id }}" selected="selected">
                                            {{ $desaKelurahan->name }}
                                        </option>
                                    @endif
                                    <option value="{{ $desaKelurahan->id }}">
                                        {{ $desaKelurahan->name }}
                                    </option>
                                @endforeach
                            @endisset
                            </select>
                            @error('desa_kelurahan')
                                <div class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>
                        </div>
                    <div class="form-group row">
                    <label class="col-lg-3 col-form-label form-control-label">Alamat</label>
                    <div class="col-lg-9">
                        <input name="alamat" class="form-control @error('alamat') is-invalid @enderror" type="text" value="{{ $pmksData->alamat }}">
                        @error('alamat')
                            <div class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">Dusun</label>
                        <div class="col-lg-9">
                            <input name="dusun" class="form-control @error('dusun') is-invalid @enderror" type="text" value="{{ $pmksData->dusun }}">
                            @error('dusun')
                                <div class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">RT</label>
                        <div class="col-lg-5">
                            <select name="rt" class="form-control select2 @error('rt') is-invalid @enderror" id="rt" size="0">
                            <option value="-">
                                -
                            </option>
                            
                            @for ($i = 0; $i < 21; $i++)
                                @if ($pmksData->rt == $i && $i != "-" )
                                        <option value="{{ $i }}" selected="selected">
                                            {{ $i }}
                                        </option>
                                @else
                                    <option value="{{ $i }}">
                                        {{ $i }}
                                    </option>
                                @endif
                            @endfor
                        
                            </select>
                            @error('rt')
                                <div class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">RW</label>
                        <div class="col-lg-5">
                            <select name="rw" class="form-control select2 @error('rw') is-invalid @enderror" id="rw" size="0">
                            <option value="-">
                                -
                            </option>
                            
                            @for ($i = 0; $i < 21; $i++)
                                @if ($pmksData->rw == $i && $i != "-" )
                                        <option value="{{ $i }}" selected="selected">
                                            {{ $i }}
                                        </option>
                                @else
                                    <option value="{{ $i }}">
                                        {{ $i }}
                                    </option>
                                @endif
                            @endfor
                            </select>

                            @error('rw')
                                <div class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                            
                        </div>
                    </div>


                  
                    <div class="form-group row">
                    <label class="col-lg-3 col-form-label form-control-label">Nomor KK</label>
                    <div class="col-lg-9">
                        <input name="nomor_kk" class="form-control @error('nomor_kk') is-invalid @enderror" type="text" value="{{ $pmksData->nomor_kk }}"> 
                        <small class="form-text text-muted " id="passwordHelpBlock"></small>
                        @error('nomor_kk')
                            <div class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>
                    </div>
                    @php
                        $pmksData->nomor_nik;
                    @endphp
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">Nomor NIK</label>
                        <div class="col-lg-9">
                            <input name="nomor_nik" class="form-control  @error('nomor_nik') is-invalid @enderror" type="text" value="{{ $pmksData->nomor_nik }}"> 
                            <small class="form-text text-muted" id="passwordHelpBlock"></small>
                            @error('nomor_nik')
                            <div class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">Nama</label>
                        <div class="col-lg-9">
                            <input name="nama" class="form-control @error('nama') is-invalid @enderror" type="text" value="{{ $pmksData->nama }}"> 
                            <small class="form-text text-muted" id="passwordHelpBlock"></small>
                            @error('nama')
                                <div class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">Tanggal Lahir</label>
                        <div class="col-lg-5">
                            
                                <input  type="date" class="form-control bg-light @error('tanggal_lahir') is-invalid @enderror" name="tanggal_lahir" value="{{ $pmksData->tanggal_lahir }}">
                                @error('tanggal_lahir')
                                    <div class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">Tempat Lahir</label>
                        <div class="col-lg-9">
                            <input name="tempat_lahir" class="form-control @error('tempat_lahir') is-invalid @enderror" type="text" value="{{ $pmksData->tempat_lahir  }}"> 
                            @error('tempat_lahir')
                                <div class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>
                    </div>


                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">Jenis Kelamin</label>
                        <div class="col-lg-5">
                            <select name="jenis_kelamin" class="form-control select2 @error('jenis_kelamin') is-invalid @enderror" id="jenis_kelamin" size="0">
                                
                                <option value="PRIA" {{ $pmksData->jenis_kelamin == "PRIA" ? "selected='selected'" : '' }}> PRIA </option> 
                                <option value="WANITA" {{ $pmksData->jenis_kelamin == "WANITA" ? "selected='selected'" : '' }}> WANITA </option>  
                                
                                                      
                            </select>
                            @error('jenis_kelamin')
                                <div class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">Nama Ibu Kandung</label>
                        <div class="col-lg-9">
                            <input name="nama_ibu_kandung" class="form-control @error('nama_ibu_kandung') is-invalid @enderror" type="text" value="{{ $pmksData->nama_ibu_kandung }}"> 
                            @error('nama_ibu_kandung')
                                <div class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label"> Hubungan Keluarga</label>
                        <div class="col-lg-5">
                            <select name="hubungan_keluarga" class="form-control select2 @error('hubungan_keluarga') is-invalid @enderror" id="hubungan_keluarga" >
                                <option value="ANAK" {{ $pmksData->hubungan_keluarga == "ANAK" ? "selected='selected'" : '' }}> ANAK </option> 
                                <option value="ISTRI" {{ $pmksData->hubungan_keluarga == "ISTRI" ? "selected='selected'" : '' }}> ISTRI </option> 
                                <option value="KEPALA KELUARGA" {{ $pmksData->hubungan_keluarga == "KEPALA KELUARGA" ? "selected='selected'" : '' }}> KEPALA KELUARGA </option>   
                                <option value="blm ditentukan" {{ $pmksData->hubungan_keluarga == "blm ditentukan" ? "selected='selected'" : '' }}> blm ditentukan </option>                       
                            </select>
                            @error('hubungan_keluarga')
                                <div class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>
                    </div>

                    

                    <div class="form-group row">
                    <label class="col-lg-3 col-form-label form-control-label"></label>
                    <div class="col-lg-9">
                        <a href="{{ route('pmks.data') }}" class="btn btn-secondary">Batal </a>
                        
                        <input class="btn btn-primary" type="submit" value="Update">
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

    $("#input-tahun-data").datepicker({
        format: "yyyy",
        viewMode: "years", 
        minViewMode: "years",
        autoclose:true
    });

    
    
    $(".select2").select2();
    $("#kabupaten_kota").select2();
    $("#kecamatan").select2();
    $("#desa_kelurahan").select2();
    $("#jenis_pmks").select2();



    $('#provinsi').select2({
            allowClear: true,
            ajax: {
               url: "{{ route('provinces') }}",
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


        //  Event on change select province:start
        $('#provinsi').change(function() {
            //clear select
            $('#kabupaten_kota').empty();
            $("#kecamatan").empty();
            $("#desa_kelurahan").empty();
            //set id
            let provinceID = $(this).val();
            if (provinceID) {
               $('#kabupaten_kota').select2({
                  allowClear: true,
                  ajax: {
                     url: "{{ route('cities') }}?provinceID=" + provinceID,
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
               $('#kabupaten_kota').empty();
               $("#kecamatan").empty();
               $("#desa_kelurahan").empty();
            }
         });

    
         //  Event on change select regency:start
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
         //  Event on change select regency:end

         //  Event on change select district:Start
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
         $('#provinsi').on('select2:clear', function(e) {
            $("#kabupaten_kota").select2();
            $("#kecamatan").select2();
            $("#desa_kelurahan").select2();
         });

         $('#kabupaten_kota').on('select2:clear', function(e) {
            $("#kecamatan").select2();
            $("#desa_kelurahan").select2();
         });

         $('#kecamatan').on('select2:clear', function(e) {
            $("#desa_kelurahan").select2();
         });

</script>
@endsection

