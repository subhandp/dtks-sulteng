@extends('layouts.master')
@section('content')
    <section class="content card" style="padding: 10px 10px 10px 10px ">
        <div class="box">
                @if(session('sukses'))
                <div class="alert alert-success" role="alert">
                    File berhasil di upload, Import data sedang berlangsung. <strong>Klik refresh</strong> untuk melihat progress.<a href="{{session('sukses')}}"></a>
                </div>
                @endif

                @if(session('sukseshapus'))
                <div class="alert alert-success" role="alert">
                    {{session('sukseshapus')}}
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
                        <a class="btn btn-warning btn-sm my-1 mr-sm-1" href="create" role="button"><i class="fas fa-sync"></i> Refresh</a>
                    </span>
                </h5>
                

                <hr>
            </div>
            </div>
        
            <div class="row table-responsive">
                <div class="col">
                <table class="table table-hover table-head-fixed" id="tabel-import-data" style="width:100%">
                    <thead>
                        <tr class="bg-light">
                        <th>NO.</th>
                        <th>NAMA FILE</th>
                        <th>TANGGAL UPLOAD</th>
                        <th>STATUS IMPORT</th>
                        <th>STATUS POSTING</th>
                        <th>(%) POSTING</th>
                        <th>AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $no = 0;
                        @endphp
                       
                        @foreach($data_pmks_import as $key => $import)
                        @php
                             $no++ ;

                             if (isset($data_pmks_import_status[$key])) {
                                $total_rows = $data_pmks_import_status[$key]['total_rows'];
                                $current_rows = $data_pmks_import_status[$key]['current_row'];
                                $persentase =  $data_pmks_import_status[$key]['persentase'];
                            }
                            else{
                                $total_rows = '-';
                                $current_rows = '-';
                                $persentase =  'proses belum mulai';
                            }

                        @endphp
                        <tr>
                        
                            <td>{{$no}}</td>
                            <td>{{ $import->filename }}</td>
                            <td>{{ $import->created_at }}</td>
                            <td><strong>{{ $import->status_import }}</strong></td>
                            <td> <strong>{{ $total_rows }}</strong> (Total)  => <strong>{{ $current_rows }} </strong> (selesai)</td>
                            <td>                          
                                <strong>{{ $persentase }} %</strong>
                            </td>
                            <td>
                                @if ($import->status_import === 'SUKSES IMPORT')
                                    <span class="badge badge-warning">Belum Posting</span><br><a href="#" class="btn btn-default btn-sm my-1 mr-sm-1 btn-block">Validasi x Posting</a>
                                @elseif ($import->status_import == 'SUKSES POSTING')
                                    <span class="badge badge-info">Sudah Posting</span><a href="#" class="btn btn-default btn-sm my-1 mr-sm-1 btn-block"><i class="fas fa-eye"></i></a>
                                @else
                                    #    
                                @endif
                                
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
                                        <select class="form-control" id="jenis_pmks" name="jenis_pmks">
                                            <option value="Keluarga fakir miskin">Keluarga fakir miskin</option>
                                            <option value="Korban bencana alam">Korban bencana alam</option>
                                            <option value="Korban bencana sosial">Korban bencana sosial</option>
                                        </select>
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

    </section>
 @endsection


 @section('file-pond-import')
 <script>
     FilePond.setOptions({
         server: {
             process:  '/filepond/process',
             revert: '/filepond/revert',
             headers: {
                 'X-CSRF-TOKEN' : '{{ csrf_token() }}'
             }
         },
         
     });
 </script>
 @endsection