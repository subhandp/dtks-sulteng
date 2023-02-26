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
                <strong>TAMBAH DATA LKS - LEMBAGA KESEJAHTERAAN SOSIAL</strong>
            </h5>
            
            <hr>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-10">
            <form method="post" action="{{ route('psks.lks.store-create') }}" class="form" role="form">
                @csrf
                
                @isset($myhashid)
                    <input type="hidden" name="id" value="{{ $myhashid }}">
                @endisset
    

                    
                <div class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label">Nama LKS</label>
                <div class="col-lg-9">
                    @php
                        if(isset($lksData->nama_lks))
                            $nama_lks = $lksData->nama_lks;
                        else
                            $nama_lks = old('nama_lks');
                    @endphp 

                    <input name="nama_lks" class="form-control @error('nama_lks') is-invalid @enderror" type="text" value="{{ $nama_lks }}">
                    @error('nama_lks')
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
                            if(isset($lksData->no_hp))
                                $no_hp = $lksData->no_hp;
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
                                if(isset($lksData->email))
                                    $email = $lksData->email;
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
                            <label class="col-lg-3 col-form-label form-control-label">Nama Ketua LKS</label>
                            <div class="col-lg-9">
                                @php
                                    if(isset($lksData->nama_ketua_lks))
                                        $nama_ketua_lks = $lksData->nama_ketua_lks;
                                    else
                                        $nama_ketua_lks = old('nama_ketua_lks');
                                @endphp 
            
                                <input name="nama_ketua_lks" class="form-control @error('nama_ketua_lks') is-invalid @enderror" type="text" value="{{ $nama_ketua_lks }}">
                                @error('nama_ketua_lks')
                                    <div class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">Legalitas LKS</label>
                                <div class="col-lg-3">
                                    @php
                                        if(isset($lksData->legalitas_lks))
                                            $legalitas_lks = $lksData->legalitas_lks;
                                        else
                                            $legalitas_lks = old('legalitas_lks');
                                    @endphp 
                
                                    <input name="legalitas_lks" class="form-control @error('legalitas_lks') is-invalid @enderror" type="text" value="{{ $legalitas_lks }}">
                                    @error('legalitas_lks')
                                        <div class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                </div>
                                </div>


                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label form-control-label">Posisi LKS</label>
                                    <div class="col-lg-3">
                                        @php
                                            if(isset($lksData->posisi_lks))
                                                $posisi_lks = $lksData->posisi_lks;
                                            else
                                                $posisi_lks = old('posisi_lks');
                                        @endphp 
                    
                                        <input name="posisi_lks" class="form-control @error('posisi_lks') is-invalid @enderror" type="text" value="{{ $posisi_lks }}">
                                        @error('posisi_lks')
                                            <div class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label form-control-label">Ruang Lingkup LKS</label>
                                        <div class="col-lg-3">
                                            @php
                                                if(isset($lksData->ruang_lingkup))
                                                    $ruang_lingkup = $lksData->ruang_lingkup;
                                                else
                                                    $ruang_lingkup = old('ruang_lingkup');
                                            @endphp 
                        
                                            <input name="ruang_lingkup" class="form-control @error('ruang_lingkup') is-invalid @enderror" type="text" value="{{ $ruang_lingkup }}">
                                            @error('ruang_lingkup')
                                                <div class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </div>
                                            @enderror
                                        </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label form-control-label">Jenis kegiatan LKS</label>
                                            <div class="col-lg-3">
                                                @php
                                                    if(isset($lksData->jenis_kegiatan))
                                                        $jenis_kegiatan = $lksData->jenis_kegiatan;
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
                        <a href="{{ route('psks.lks.index')}}" class="btn btn-secondary">Batal </a>
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

