@extends('layouts.master')
@section('content')
    <section class="content card" style="padding: 10px 10px 10px 10px ">


        <div class="box">
                @if(session('sukses-posting'))
                <div class="alert alert-success" role="alert">
                    <strong>Data berhasil masuk Posting.</strong> Proses akan berjalan.<a href="{{session('sukses-posting')}}"> lihat</a>
                </div>
                @endif

                @if(session('gagal-jobs'))
                <div class="alert alert-danger" role="alert">
                    <strong>Masih ada proses Import/Posting yang berlangsung.</strong> Pastikan tidak ada proses yang sedang berlangsung.<a href="{{session('gagal-jobs')}}"> lihat</a>
                </div>
                @endif

            <div class="row">
                <div class="col">
                <h5>
                    <strong>POSTING DATA </strong>
                </h5>
                
                <hr>
            </div>
            </div>
            <div class="m-4 d-flex justify-content-center">
                <form class="form-inline" action="/pmks/store-posting" method="POST"  id="postingForm" >
                    @csrf
                    <div class="row align-items-center g-3 mycontainer">
                        <div class="col-auto ">
                            
                            <select id="selectDtksImport" name="selectDtksImport" data-placeholder="Pilih No Tiket" style='width: 300px;height: 100px;'>
                            </select>

                        </div>
                        
                        <div class="col-auto">
                            <button id="btn-posting" type="button" class="btn btn-warning btn-sm my-1 mr-sm-1">Posting</button>
                        </div>
                    </div>
                </form>
            </div>
        
        </div>
    </section>
 @endsection


 @section('file-pond-data-import')
 <script>
    

    $('#selectDtksImport').select2({
            allowClear: true,
            ajax: {
               url: "{{ route('dtks.selectdtksimport') }}",
               dataType: 'json',
               processResults: function(data) {
                  return {
                     results: $.map(data, function(item) {
                        return {
                           text: item.no_tiket,
                           id: item.id
                        }
                     })
                  };
               }
            }
         });

         $(document).on('click', '#btn-posting', function(event) {
            bootbox.confirm({ 
                buttons: {
                    confirm: {
                        label: 'Posting',
                        className: 'btn-primary'
                    }
                },
                size: "small",
                message: "Lanjutkan Posting data ?",
                callback: function(result){ 
                    document.getElementById("postingForm").submit(); 
                }
            })
         });
         

 </script>
 @endsection