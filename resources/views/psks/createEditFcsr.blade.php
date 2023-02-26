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
                <strong>TAMBAH DATA FCSR - FORUM CSR KESEJAHTERAAN SOSIAL</strong>
            </h5>
            
            <hr>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-10">
            <form method="post" action="{{ route('psks.fcsr.store-create') }}" class="form" role="form">
                @csrf
                
                @isset($myhashid)
                    <input type="hidden" name="id" value="{{ $myhashid }}">
                @endisset
    

                    
                <div class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label">Nama FCSR</label>
                <div class="col-lg-9">
                    @php
                        if(isset($fcsrData->nama_fcsr))
                            $nama_fcsr = $fcsrData->nama_fcsr;
                        else
                            $nama_fcsr = old('nama_fcsr');
                    @endphp 

                    <input name="nama_fcsr" class="form-control @error('nama_fcsr') is-invalid @enderror" type="text" value="{{ $nama_fcsr }}">
                    @error('nama_fcsr')
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
                            if(isset($fcsrData->no_hp))
                                $no_hp = $fcsrData->no_hp;
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
                                if(isset($fcsrData->email))
                                    $email = $fcsrData->email;
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
                            <label class="col-lg-3 col-form-label form-control-label">Nama Ketua FCSR</label>
                            <div class="col-lg-9">
                                @php
                                    if(isset($fcsrData->nama_ketua_pengurus_fcsr))
                                        $nama_ketua_pengurus_fcsr = $fcsrData->nama_ketua_pengurus_fcsr;
                                    else
                                        $nama_ketua_pengurus_fcsr = old('nama_ketua_pengurus_fcsr');
                                @endphp 
            
                                <input name="nama_ketua_pengurus_fcsr" class="form-control @error('nama_ketua_pengurus_fcsr') is-invalid @enderror" type="text" value="{{ $nama_ketua_pengurus_fcsr }}">
                                @error('nama_ketua_pengurus_fcsr')
                                    <div class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">Legalitas FCSR</label>
                                <div class="col-lg-3">
                                    @php
                                        if(isset($fcsrData->legalitas_fcsr))
                                            $legalitas_fcsr = $fcsrData->legalitas_fcsr;
                                        else
                                            $legalitas_fcsr = old('legalitas_fcsr');
                                    @endphp 
                
                                    <input name="legalitas_fcsr" class="form-control @error('legalitas_fcsr') is-invalid @enderror" type="text" value="{{ $legalitas_fcsr }}">
                                    @error('legalitas_fcsr')
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
                                            if(isset($fcsrData->jumlah_pengurus))
                                                $jumlah_pengurus = $fcsrData->jumlah_pengurus;
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
                                                if(isset($fcsrData->jumlah_anggota))
                                                    $jumlah_anggota = $fcsrData->jumlah_anggota;
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
                                            <label class="col-lg-3 col-form-label form-control-label">Jumlah CSR Kesos Perusahaan</label>
                                            <div class="col-lg-3">
                                                @php
                                                    if(isset($fcsrData->jumlah_csr_kesos_perusahaan))
                                                        $jumlah_csr_kesos_perusahaan = $fcsrData->jumlah_csr_kesos_perusahaan;
                                                    else
                                                        $jumlah_csr_kesos_perusahaan = old('jumlah_csr_kesos_perusahaan');
                                                @endphp 
                            
                                                <input name="jumlah_csr_kesos_perusahaan" class="form-control @error('jumlah_csr_kesos_perusahaan') is-invalid @enderror" type="text" value="{{ $jumlah_csr_kesos_perusahaan }}">
                                                @error('jumlah_csr_kesos_perusahaan')
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

