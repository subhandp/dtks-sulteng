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
                <strong>TAMBAH DATA PSM - PEKERJA SOSIAL MASYARAKAT</strong>
            </h5>
            
            <hr>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-10">
             

                <form method="post" action="{{ route('psks.psm.store-create') }}" class="form" role="form">
                    @csrf
                    
                    @isset($myhashid)
                        <input type="hidden" name="id" value="{{ $myhashid }}">
                    @endisset
                    

                    <div class="form-group row">
                    <label class="col-lg-3 col-form-label form-control-label">Kabupaten/Kota</label>
                    <div class="col-lg-9">

                        <select class="form-control @error('kabupaten_kota') is-invalid @enderror" id="kabupaten_kota" name="kabupaten_kota" data-placeholder="Pilih Kabupaten/Kota" style="width: 100%" >
                           
                            @foreach ( $kabupatenKotaCreate as $kabupatenKota)
                            @isset($kabupatenKotaCreateSelect)
                                @if (strtolower($kabupatenKotaCreateSelect->name) == strtolower($kabupatenKota->name))
                                    <option value="{{ $kabupatenKota->id }}" selected="selected">
                                        {{ $kabupatenKota->name }}
                                    </option>
                                @endif
                            @endisset
                                <option value="{{ $kabupatenKota->id }}">
                                    {{ $kabupatenKota->name }}
                                </option>
                            @endforeach
        
                        </select>
                        @error('kabupaten_kota')
                            <div class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>
                    </div>

                        
                    <div class="form-group row">
                    <label class="col-lg-3 col-form-label form-control-label">Nama PSM</label>
                    <div class="col-lg-9">
                        @php
                            if(isset($psmData->nama_psm))
                                $nama_psm = $psmData->nama_psm;
                            else
                                $nama_psm = old('nama_psm');
                        @endphp 
   
                        <input name="nama_psm" class="form-control @error('nama_psm') is-invalid @enderror" type="text" value="{{ $nama_psm }}">
                        @error('nama_psm')
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
                                if(isset($psmData->jenis_kelamin))
                                    $jenis_kelamin = $psmData->jenis_kelamin;
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
                        <label class="col-lg-3 col-form-label form-control-label">Pendidikan Terakhir</label>
                        <div class="col-lg-5">

                            @php
                                if(isset($psmData->pendidikan_terakhir))
                                    $pendidikan_terakhir = $psmData->pendidikan_terakhir;
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
                        <label class="col-lg-3 col-form-label form-control-label">NIK</label>
                        <div class="col-lg-9">
                            @php
                                if(isset($psmData->nik_no_ktp))
                                    $nik_no_ktp = $psmData->nik_no_ktp;
                                else
                                    $nik_no_ktp = old('nik_no_ktp');
                            @endphp 
                            <input name="nik_no_ktp" class="form-control @error('nik_no_ktp') is-invalid @enderror" type="text" value="{{ $nik_no_ktp }}">
                            @error('nik_no_ktp')
                                <div class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">Alamat Rumah</label>
                        <div class="col-lg-9">
                            @php
                                if(isset($psmData->alamat_rumah))
                                    $alamat_rumah = $psmData->alamat_rumah;
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
                        <label class="col-lg-3 col-form-label form-control-label">Nomor HP</label>
                        <div class="col-lg-9">
                            @php
                                if(isset($psmData->no_hp))
                                    $no_hp = $psmData->no_hp;
                                else
                                    $no_hp = old('no_hp');
                            @endphp 

                            <input name="no_hp" class="form-control @error('no_hp') is-invalid @enderror" type="text" value="{{ $no_hp }}"> 
                            <small class="form-text text-muted " id="passwordHelpBlock"></small>
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
                                if(isset($psmData->email))
                                    $email = $psmData->email;
                                else
                                    $email = old('email');
                            @endphp 
                            <input name="email" class="form-control  @error('email') is-invalid @enderror" type="text" value="{{ $email }}"> 
                            <small class="form-text text-muted" id="passwordHelpBlock"></small>
                            @error('email')
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
                                if(isset($psmData->mulai_aktif))
                                    $mulai_aktif = $psmData->mulai_aktif;
                                else
                                    $mulai_aktif = old('mulai_aktif');
                            @endphp 

                            <select name="mulai_aktif" class="form-control select2 @error('mulai_aktif') is-invalid @enderror" id="mulai_aktif" size="0">

                                <option value="2008" {{ $mulai_aktif == "2008" ? "selected='selected'" : '' }}> 2008 </option> 
                                <option value="2022" {{ $mulai_aktif == "2022" ? "selected='selected'" : '' }}> 2022 </option>  
                                
                            </select>
                            @error('mulai_aktif')
                                <div class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>
                    </div>
                    

                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">Legalitas/sertifikat</label>
                        <div class="col-lg-5">
                            @php
                                if(isset($psmData->legalitas_sertifikat))
                                    $legalitas_sertifikat = $psmData->legalitas_sertifikat;
                                else
                                    $legalitas_sertifikat = old('legalitas_sertifikat');
                            @endphp   

                            <select name="legalitas_sertifikat" class="form-control select2 @error('legalitas_sertifikat') is-invalid @enderror" id="legalitas_sertifikat" size="0">

                                <option value="1" {{ $legalitas_sertifikat == "1" ? "selected='selected'" : '' }}> [ 1 ]  Sertifikat PSM = Dasar </option> 
                                <option value="2" {{ $legalitas_sertifikat == "2" ? "selected='selected'" : '' }}> [ 2 ]  Setifikat PSM = Lanjutan </option>  
                                <option value="3" {{ $legalitas_sertifikat == "3" ? "selected='selected'" : '' }}> [ 3 ]   Setifikat PSM = Khusus </option>  
                                <option value="0" {{ $legalitas_sertifikat == "0" ? "selected='selected'" : '' }}> [ 0 ]  Tidak ada </option>  
                                               
                            </select>
                            @error('legalitas_sertifikat')
                                <div class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>
                    </div>

                    
                    
        <div class="form-group row">
            <label class="col-lg-3 col-form-label form-control-label">Jenis Diklat yg Diikuti</label>
            <div class="col-lg-5">
                @php
                    if(isset($psmData->jenis_diklat_yg_diikuti))
                        $jenis_diklat_yg_diikuti = $psmData->jenis_diklat_yg_diikuti;
                    else
                        $jenis_diklat_yg_diikuti = old('jenis_diklat_yg_diikuti');
                @endphp   
                
                <select name="jenis_diklat_yg_diikuti" class="form-control select2 @error('jenis_diklat_yg_diikuti') is-invalid @enderror" id="jenis_diklat_yg_diikuti" size="0">

                    <option value="1" {{ $jenis_diklat_yg_diikuti == "1" ? "selected='selected'" : '' }}> [ 1 ]  KUBE </option> 
                    <option value="2" {{ $jenis_diklat_yg_diikuti == "2" ? "selected='selected'" : '' }}> [ 2 ]  PKH </option>  
                    <option value="3" {{ $jenis_diklat_yg_diikuti == "3" ? "selected='selected'" : '' }}> [ 3 ]  Askesos </option>  
                    <option value="4" {{ $jenis_diklat_yg_diikuti == "4" ? "selected='selected'" : '' }}> [ 4 ] Paca </option>  
                    <option value="5" {{ $jenis_diklat_yg_diikuti == "5" ? "selected='selected'" : '' }}> [ 5 ]  Aslut </option>  
                    <option value="6" {{ $jenis_diklat_yg_diikuti == "6" ? "selected='selected'" : '' }}> [ 6 ]  Raskin</option>  
                    <option value="7" {{ $jenis_diklat_yg_diikuti == "7" ? "selected='selected'" : '' }}> [ 7 ]  Lain-lain</option>  
                    
                    <option value="0" {{ $jenis_diklat_yg_diikuti == "0" ? "selected='selected'" : '' }}> [ 0 ]  Tidak ada </option>  
                                   
                </select>
                @error('jenis_diklat_yg_diikuti')
                    <div class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </div>
                @enderror
            </div>
        </div>


    <div class="form-group row">
        <label class="col-lg-3 col-form-label form-control-label">Peran Pendampingan</label>
        <div class="col-lg-5">
            <select name="pendampingan" class="form-control select2 @error('pendampingan') is-invalid @enderror" id="pendampingan" size="0">

                @php
                    if(isset($psmData->pendampingan))
                        $pendampingan = $psmData->pendampingan;
                    else
                        $pendampingan = old('pendampingan');
                @endphp                                                                                   
                <option value="1" {{ $pendampingan == "1" ? "selected='selected'" : '' }}> [ 1 ]  KUBE </option> 
                <option value="2" {{ $pendampingan == "2" ? "selected='selected'" : '' }}> [ 2 ]  PKH </option>  
                <option value="3" {{ $pendampingan == "3" ? "selected='selected'" : '' }}> [ 3 ]  Askesos </option>  
                <option value="4" {{ $pendampingan == "4" ? "selected='selected'" : '' }}> [ 4 ] Paca </option>  
                <option value="5" {{ $pendampingan == "5" ? "selected='selected'" : '' }}> [ 5 ]  Aslut </option>  
                <option value="6" {{ $pendampingan == "6" ? "selected='selected'" : '' }}> [ 6 ]  Raskin</option>  
                <option value="7" {{ $pendampingan == "7" ? "selected='selected'" : '' }}> [ 7 ]  Lain-lain</option>  
                <option value="0" {{ $pendampingan == "0" ? "selected='selected'" : '' }}> [ 0 ]  Tidak ada </option>  
                               
            </select>
            @error('pendampingan')
                <div class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </div>
            @enderror
        </div>
    </div>


                    <div class="form-group row">
                    <label class="col-lg-3 col-form-label form-control-label"></label>
                    <div class="col-lg-9">
                        <a href="{{ route('psks.psm.index')}}" class="btn btn-secondary">Batal </a>
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
    
    $(".select2").select2();

    $("#kabupaten_kota").select2();
        
</script>
@endsection

