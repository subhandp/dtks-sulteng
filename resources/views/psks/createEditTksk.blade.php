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
                <strong>TAMBAH DATA TKSK - TENAGA KESEJAHTERAAN MASYARAKAT</strong>
            </h5>
            
            <hr>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-10">
            <form method="post" action="{{ route('psks.tksk.store-create') }}" class="form" role="form">
                @csrf
                
                @isset($myhashid)
                    <input type="hidden" name="id" value="{{ $myhashid }}">
                @endisset
    

                    
                <div class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label">No Induk TKSK (a)</label>
                <div class="col-lg-9">
                    @php
                        if(isset($tkskData->no_induk_tksk_a))
                            $no_induk_tksk_a = $tkskData->no_induk_tksk_a;
                        else
                            $no_induk_tksk_a = old('no_induk_tksk_a');
                    @endphp 

                    <input name="no_induk_tksk_a" class="form-control @error('no_induk_tksk_a') is-invalid @enderror" type="text" value="{{ $no_induk_tksk_a }}">
                    @error('no_induk_tksk_a')
                        <div class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </div>
                    @enderror
                </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-3 col-form-label form-control-label">No Induk TKSK (b)</label>
                    <div class="col-lg-9">
                        @php
                            if(isset($tkskData->no_induk_tksk_b))
                                $no_induk_tksk_b = $tkskData->no_induk_tksk_b;
                            else
                                $no_induk_tksk_b = old('no_induk_tksk_b');
                        @endphp 
    
                        <input name="no_induk_tksk_b" class="form-control @error('no_induk_tksk_b') is-invalid @enderror" type="text" value="{{ $no_induk_tksk_b }}">
                        @error('no_induk_tksk_b')
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
                    <label class="col-lg-3 col-form-label form-control-label">Nama</label>
                    <div class="col-lg-9">
                        @php
                            if(isset($tkskData->nama))
                                $nama = $tkskData->nama;
                            else
                                $nama = old('nama');
                        @endphp 

                        <input name="nama" class="form-control @error('nama') is-invalid @enderror" type="text" value="{{ $nama }}">
                        @error('nama')
                            <div class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">Nama Ibu Kandung</label>
                        <div class="col-lg-9">
                            @php
                                if(isset($tkskData->nama_ibu_kandung))
                                    $nama_ibu_kandung = $tkskData->nama_ibu_kandung;
                                else
                                    $nama_ibu_kandung = old('nama_ibu_kandung');
                            @endphp 
        
                            <input name="nama_ibu_kandung" class="form-control @error('nama_ibu_kandung') is-invalid @enderror" type="text" value="{{ $nama_ibu_kandung }}">
                            @error('nama_ibu_kandung')
                                <div class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>
                        </div>
                    
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label form-control-label">No NIk</label>
                            <div class="col-lg-9">
                                @php
                                    if(isset($tkskData->nomor_nik))
                                        $nomor_nik = $tkskData->nomor_nik;
                                    else
                                        $nomor_nik = old('nomor_nik');
                                @endphp 
            
                                <input name="nomor_nik" class="form-control @error('nomor_nik') is-invalid @enderror" type="text" value="{{ $nomor_nik }}">
                                @error('nomor_nik')
                                    <div class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">Tempat lahir</label>
                                <div class="col-lg-3">
                                    @php
                                        if(isset($tkskData->tempat_lahir))
                                            $tempat_lahir = $tkskData->tempat_lahir;
                                        else
                                            $tempat_lahir = old('tempat_lahir');
                                    @endphp 
                
                                    <input name="tempat_lahir" class="form-control @error('tempat_lahir') is-invalid @enderror" type="text" value="{{ $tempat_lahir }}">
                                    @error('tempat_lahir')
                                        <div class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                </div>
                                </div>


                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label form-control-label">Tanggal Lahir</label>
                                    <div class="col-lg-5">
                                        
                                        @php
                                            if(isset($tkskData->tanggal_lahir))
                                                $tanggal_lahir = $tkskData->tanggal_lahir;
                                            else
                                                $tanggal_lahir = old('tanggal_lahir');
                                        @endphp 

                                            <input  type="date" class="form-control bg-light @error('tanggal_lahir') is-invalid @enderror" name="tanggal_lahir" value="{{ $tanggal_lahir }}">
                                            @error('tanggal_lahir')
                                                <div class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </div>
                                            @enderror
                                    </div>
                                </div>


                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label form-control-label">Jenis Kelamin</label>
                                    <div class="col-lg-5">
            
                                        @php
                                            if(isset($tkskData->jenis_kelamin))
                                                $jenis_kelamin = $tkskData->jenis_kelamin;
                                            else
                                                $jenis_kelamin = old('jenis_kelamin');
                                        @endphp 
            
                                        <select name="jenis_kelamin" class="form-control select2 @error('jenis_kelamin') is-invalid @enderror" id="jenis_kelamin" size="0">
            
                                            <option value="L" {{ $jenis_kelamin == "L" ? "selected='selected'" : '' }}> [ 1 ] Laki-laki </option> 
                                            <option value="P" {{ $jenis_kelamin == "P" ? "selected='selected'" : '' }}> [ 2 ] Perempuan </option>  
                                            
                                                                  
                                        </select>
                                        @error('jenis_kelamin')
                                            <div class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label form-control-label">Alamat Rumah</label>
                                    <div class="col-lg-3">
                                        @php
                                            if(isset($tkskData->alamat_rumah))
                                                $alamat_rumah = $tkskData->alamat_rumah;
                                            else
                                                $alamat_rumah = old('alamat_rumah');
                                        @endphp 
                    
                                        <input name="alamat_rumah" class="form-control @error('alamat_rumah') is-invalid @enderror" type="text" value="{{ $alamat_rumah }}">
                                        @error('alamat_rumah')
                                            <div class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>
                                    </div>

           
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label form-control-label">No HP</label>
                    <div class="col-lg-9">
                        @php
                            if(isset($tkskData->no_hp))
                                $no_hp = $tkskData->no_hp;
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
                        <label class="col-lg-3 col-form-label form-control-label">Pendidikan Terakhir</label>
                        <div class="col-lg-5">

                            @php
                                if(isset($tkskData->pendidikan_terakhir))
                                    $pendidikan_terakhir = $tkskData->pendidikan_terakhir;
                                else
                                    $pendidikan_terakhir = old('pendidikan_terakhir');
                            @endphp 

                            <select name="pendidikan_terakhir" class="form-control select2 @error('pendidikan_terakhir') is-invalid @enderror" id="pendidikan_terakhir" size="0">

                                <option value="SD" {{ $pendidikan_terakhir == "SD" ? "selected='selected'" : '' }}> [ 1 ]  SD/sederajat	 </option> 
                                <option value="SMP" {{ $pendidikan_terakhir == "SMP" ? "selected='selected'" : '' }}> [ 2 ]  SMP / sederajat </option>  
                                <option value="SLTA" {{ $pendidikan_terakhir == "SLTA" ? "selected='selected'" : '' }}> [ 3 ]  SLTA sederajat </option>  
                                <option value="Diplomat I" {{ $pendidikan_terakhir == "Diplomat I" ? "selected='selected'" : '' }}> [ 4 ]  Diplomat I </option>  
                                <option value="Diplomat II" {{ $pendidikan_terakhir == "Diplomat II" ? "selected='selected'" : '' }}> [ 4 ] Diplomat II </option>  
                                <option value="Diplomat III" {{ $pendidikan_terakhir == "Diplomat III" ? "selected='selected'" : '' }}> [ 4 ] Diplomat III </option>  
                                <option value="Diplomat IV" {{ $pendidikan_terakhir == "Diplomat IV" ? "selected='selected'" : '' }}> [ 4 ] Diplomat IV </option>  
                                <option value="Diplomat V" {{ $pendidikan_terakhir == "Diplomat V" ? "selected='selected'" : '' }}> [ 4 ] Diplomat V </option>  

                                <option value="S1" {{ $pendidikan_terakhir == "S 1" ? "selected='selected'" : '' }}> [ 5 ] S 1 </option>  
                                <option value="S2" {{ $pendidikan_terakhir == "S 2" ? "selected='selected'" : '' }}> [ 5 ] S 2 </option>  
                                <option value="S3" {{ $pendidikan_terakhir == "S 3" ? "selected='selected'" : '' }}> [ 5 ] S 3 </option>  
                                <option value="Lain-lain" {{ $pendidikan_terakhir == "Lain-lain" ? "selected='selected'" : '' }}> [ 6 ] Lain-lain </option>  
                                <option value="Tidak diketahui" {{ $pendidikan_terakhir == "Tidak diketahui" ? "selected='selected'" : '' }}> [ 0 ] Tidak diketahui </option>  
                                

                                
                                                      
                            </select>
                            @error('pendidikan_terakhir')
                                <div class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>
                    </div>


                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">Mulai Aktif</label>
                        <div class="col-lg-5">

                            @php
                                if(isset($tkskData->tahun_pengangkatan_tksk))
                                    $tahun_pengangkatan_tksk = $tkskData->tahun_pengangkatan_tksk;
                                else
                                    $tahun_pengangkatan_tksk = old('tahun_pengangkatan_tksk');
                            @endphp 

                            <select name="tahun_pengangkatan_tksk" class="form-control select2 @error('tahun_pengangkatan_tksk') is-invalid @enderror" id="tahun_pengangkatan_tksk" size="0">

                                <option value="2008" {{ $tahun_pengangkatan_tksk == "2008" ? "selected='selected'" : '' }}> 2008 </option> 
                                <option value="2022" {{ $tahun_pengangkatan_tksk == "2022" ? "selected='selected'" : '' }}> 2022 </option>  
                                
                            </select>
                            @error('tahun_pengangkatan_tksk')
                                <div class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>
                    </div>


                    <div class="form-group row">
                    <label class="col-lg-3 col-form-label form-control-label"></label>
                    <div class="col-lg-9">
                        <a href="{{ route('psks.tksk.index')}}" class="btn btn-secondary">Batal </a>
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

