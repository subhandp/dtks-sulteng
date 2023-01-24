@extends('layouts.master')
@section('content')
    <section class="content card" style="padding: 10px 10px 10px 10px ">
        <div class="box">
                @if(session('sukses'))
                <div class="alert alert-success" role="alert">
                    File berhasil di upload, Import data sedang berlangsung. <strong>Klik refresh</strong> untuk melihat progress.<a href="{{session('sukses')}}"></a>
                </div>
                @endif

                @if(session('sukses-posting'))
                <div class="alert alert-success" role="alert">
                    Data masuk proses Posting, Validasi data sedang berlangsung. <strong></strong> untuk melihat progress.<a href="{{session('sukses')}}"></a>
                </div>
                @endif

                @if(session('sukseshapus'))
                <div class="alert alert-success" role="alert">
                    {{session('sukseshapus')}}
                </div>
                @endif

                @if(session('gagal-jobs'))
                <div class="alert alert-danger" role="alert">
                    <strong>Masih ada proses Import/Posting yang berlangsung.</strong> Pastikan tidak ada proses yang sedang berlangsung.<a href="{{session('sukses')}}"></a>
                </div>
                @endif

                @if (isset($errors) && $errors->any())
                @foreach ($errors->all() as $error)
                    {{ $error }}
                @endforeach
            @endif
        
            @if (session()->has('failures'))
                <table class="danger">
                    <th>Row</th>
                    <th>Attribute</th>
                    <th>Errors</th>
                    <th>Value</th>
                    @foreach (session()->get('failures') as $validation)
                        <tr>
                            <td>{{ $validation->row() }}</td>
                            <td>{{ $validation->attribute() }}</td>
                            <td>
                                <ul>
                                    @foreach ($validation->errors() as $e)
                                        <li>{{ $e }}</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>
                                {{ $validation->values()[$validation->attribute()] }}
                            </td>
                        </tr>
                    @endforeach
                </table>
            @endif

            <div class="row">
                <div class="col">
                <h5>
                    {{-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
                        Open modal
                    </button> --}}

                    <strong>Import Data PMKS </strong>
                    <span class="float-right">
                        <a data-toggle="modal" data-target="#myModal" class="btn btn-warning btn-sm my-1 mr-sm-1" href="create" role="button"><i class="fas fa-upload"></i> Import Data</a>
                        <a onclick="location.reload()" class="btn btn-warning btn-sm my-1 mr-sm-1" href="#" role="button"><i class="fas fa-sync"></i> Refresh</a><span id="timer-refresh">00.00</span> 
                        <input onclick="setAutoRefresh()" type="checkbox" id="autoRefresh" name="autoRefresh" value="autoRefresh"> <label for="autoRefresh"> Auto</label><br>
                    </span>
                </h5>
                
                <br>
                <hr>
                
            </div>
            </div>
        
            <div class="row table-responsive ">
            <div class="col">
                <table class="table table-hover table-head-fixed " id="tabel-import-data" style="width:100%">
                    <thead>
                        <tr class="bg-light">
                        <th>NO.</th>
                        <th>NO TIKET</th>
                        <th>NAMA FILE</th>
                        <th>UKURAN</th>
                        <th>JUMLAH BARIS</th>
                        <th>TANGGAL UPLOAD</th>
                        <th>STATUS IMPORT</th>
                        <th>AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $no = 0;
                        @endphp
                       
                        @foreach($data_pmks_import as $key => $import)
                        @php
                             $no++;

                             $precision = 2;
                             $size = '-';
                            if (is_numeric($import->keterangan)) {
                                $units = array('B', 'KB', 'MB', 'GB', 'TB'); 

                                $bytes = max($import->keterangan, 0); 
                                $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
                                $pow = min($pow, count($units) - 1); 

                                // Uncomment one of the following alternatives
                                $bytes /= pow(1024, $pow);
                                // $bytes /= (1 << (10 * $pow)); 

                                $size = round($bytes, $precision) . ' ' . $units[$pow]; 
                                 
                            } 

                        @endphp
                        <tr>
                        
                            <td>{{$no}}</td>
                            <td>{{ $import->no_tiket }}</td>
                            <td>{{ $import->filename }}</td>
                            <td>{{ $size }}</td>
                            <td>{{ $import->jumlah_baris }}</td>
                            <td>{{ $import->updated_at }}</td>
                            <td>
                                {{ $import->status_import }}
                            </td>
                            <td>
                            
                              
                            <a href="#" data-toggle="modal" id="smallButton" data-target="#smallModal"
                                data-attr="{{ route('pmks.dataerrors',  ['id' => $import->id]) }}" class="btn btn-default btn-sm my-1 mr-sm-1 btn-block" data-toggle="tooltip" data-placement="left" title="Detail">
                                
                                <i class="fas fa-eye"></i>
                            </a>
                                
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
            </div>
        </div>

        <!-- The Modal -->
        <div class="modal" id="myModal">
            <div class="modal-dialog">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <strong><h5 class="modal-title w-100 text-center">Import Data</h5></strong>
                    </div>


                    <!-- Modal body -->
                    <div class="modal-body">
                        <!-- adding Bootstrap Form here -->

                        <form action="/pmks/store-import" method="POST" enctype="multipart/form-data" id="myForm" class="needs-validation" novalidate>
                            {{csrf_field()}}
                            <div class="container">
                                <div class="form-group row">
                                    <label for="tahun_data" class="col-sm-3 col-form-label">Tahun data</label>
                                    <div class="input-group col-sm-9">
                                        <select class="form-control" id="tahun_data" name="tahun_data">
                                            <option value="2022">2022</option>
                                            <option value="2021">2021</option>
                                            <option value="2020">2020</option>
                                            <option value="2019">2019</option>
                                            <option value="2018">2018</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="jenis_pmks" class="col-sm-3 col-form-label">Jenis PMKS</label>
                                    <div class="input-group col-sm-9">

                                        <select class="@error('jenis_pmks') is-invalid @enderror" id="jenis_pmks" name="jenis_pmks"  data-placeholder="Pilih Jenis PMKS" style="width: 100%"  >
                                            @foreach ($jenisPmks as $pmks)
                                            @if ('KELUARGA FAKIR MISKIN' == $pmks->jenis )
                                                <option value="{{ $pmks->jenis }}" selected="selected">
                                                    {{ $pmks->jenis }}
                                                </option>
                                            {{-- @elseif ($pmks->jenis == 'KELUARGA FAKIR MISKIN' && empty(old('jenis_pmks')))
                                                <option value="{{ $pmks->jenis }}" selected="selected">
                                                    {{ $pmks->jenis }}
                                                </option>
                                            @else
                                            
                                                <option value="{{ $pmks->jenis }}">
                                                    {{ $pmks->jenis }}
                                                </option> --}}
                                            @endif
                                            
                                            @endforeach
                                        </select>


                                        {{-- <select class="form-control" id="jenis_pmks" name="jenis_pmks">
                                            <option value="KELUARGA FAKIR MISKIN">KELUARGA FAKIR MISKIN</option>
                                            <option value="KORBAN BENCANA ALAM">KORBAN BENCANA ALAM</option>
                                            <option value="KORBAN BENCANA SOSIAL">KORBAN BENCANA SOSIAL</option>
                                            
                                            <option value="KELOMPOK MINORITAS">KELOMPOK MINORITAS</option>
                                            <option value="BEKAS WARGA BINAAN LEMBAGA PEMASYARAKATAN  (BWBLP)">BEKAS WARGA BINAAN LEMBAGA PEMASYARAKATAN  (BWBLP)</option>
                                            <option value="KORBAN BENCANA SOSIAL">ORANG DENGAN HIV AIDS</option>
                                       
                                        </select> --}}
                                    </div>
                                </div>

                                <input name="upload[]" type="file" id="upload"  multiple/>
                            </div>

                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button class="btn btn-default" type="submit"> <i class="fas fa-upload" style="color:blue;"></i> Unggah</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>

                </form>

                </div>
            </div>
        </div>

    <div class="modal fade" id="smallModal" tabindex="-1" role="dialog" aria-labelledby="smallModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="smallBody">
                    
                    <div id="wrapper-table-pmks">
                        <table id="tabel-data-pmks" class="table table-bordered data-table display compact">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>ID DTKS</th>
                                    <th>KABUPATEN/KOTA</th>
                                    {{-- <th>KECAMATAN</th> --}}
                                    {{-- <th>DESA/KELURAHAN</th> --}}
                                    {{-- <th>ALAMAT</th> --}}
                                    {{-- <th>NOMOR KK</th> --}}
                                    <th>NOMOR NIK</th>
                                    <th>NAMA</th>
                                    <th>TANGGAL LAHIR</th>
                                    {{-- <th>JENIS KELAMIN</th> --}}
                                    <th>TAHUN DATA</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                       
                    </div>

                    <div id="wrapper-table-errors">
                        <table class="table table-bordered" id="table-errors">
                            <thead>
                              <tr>
                                <th>No</th>
                                <th>Baris</th>
                                <th>Nilai</th>
                                <th>Error</th>
                              </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                          </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

    </section>
 @endsection


 @section('file-pond-import')
 <script>

    $.fn.select2.defaults.set( "theme", "bootstrap" );

    
    $("#jenis_pmks").select2();
    $("#tahun_data").select2();

    
    var tableErrros = $('#table-errors').DataTable();
    var tablePmks = $('#table-pmks').DataTable();

    
    
     FilePond.setOptions({
         server: {
             process:  '/filepond/process',
             revert: '/filepond/revert',
             headers: {
                 'X-CSRF-TOKEN' : '{{ csrf_token() }}'
             }
         },
         
     });



function checklength(i) {
    'use strict';
    if (i < 10) {
        i = "0" + i;
    }
    return i;
}

let minutes, seconds, count, counter;
count = 60; //seconds
if (localStorage.autorefresh == 'enable') {
    document.getElementById("autoRefresh").checked = true;
    counter = setInterval(timer, 1000);
}
else{
    document.getElementById("autoRefresh").checked = false;
}

function timer() {
    'use strict';
    count = count - 1;
    minutes = checklength(Math.floor(count / 60));
    seconds = checklength(count - minutes * 60);
    if (count < 0) {
        clearInterval(counter);
        return;
    }
    document.getElementById("timer-refresh").innerHTML = minutes + ':' + seconds + ' ';
    if (count === 0) {
        location.reload();
    }
}

function setAutoRefresh() {
  if (localStorage.autorefresh == 'enable') {
    localStorage.autorefresh = 'disable';
    document.getElementById("autoRefresh").checked = false;
  } 
  else if (localStorage.autorefresh == 'disable') {
    localStorage.autorefresh = 'enable';
    document.getElementById("autoRefresh").checked = true;
    counter = setInterval(timer, 1000);
  }
  else {
    localStorage.autorefresh = 'disable';
    document.getElementById("autoRefresh").checked = false;
  }

  
}

$(document).on('click', '#smallButton', function(event) {
            event.preventDefault();
            tableErrros.clear().draw();
            let href = $(this).attr('data-attr');
            $.ajax({
                url: href,
                beforeSend: function() {
                    $('#loader').show();
                },
                // return the result
                success: function(result) {
                    let errorsResult = JSON.parse(result);
                    for (var i = 0; i < errorsResult.length; i++) {
                        tableErrros.row.add( [
                            (i+1),
                            errorsResult[i].row,
                            errorsResult[i].values,
                            errorsResult[i].errors,
                        ] ).draw( true );
                    }

                    
                    
                },
                complete: function() {
                    $('#loader').hide();
                },
                error: function(jqXHR, testStatus, error) {
                    console.log(error);
                    alert("Page " + href + " cannot open. Error:" + error);
                    $('#loader').hide();
                }
            })
        });


 </script>
 @endsection