@extends('layouts.master')
@section('content')
    <section class="content card" style="padding: 10px 10px 10px 10px ">


        <div class="box">
                @if(session('sukses'))
                <div class="alert alert-success" role="alert">
                    Data berhasil diposting <a href="{{session('sukses')}}"></a>
                </div>
                @endif

                @if(session('sukseshapus'))
                <div class="alert alert-success" role="alert">
                    {{session('sukseshapus')}}
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
        
            <div class="row table-responsive">
                <form class="form-inline">
                   
                    <div class="form-group mb-12">
                        <label class="form-label">
                           No Tiket
                        </label>
                        <select id="selectDtksImport" name="selectDtksImport" data-placeholder="Pilih No Tiket" class="custom-select w-100">
                        </select>
                     </div>

                    <button type="submit" class="btn btn-primary mb-2">Confirm identity</button>
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

 </script>
 @endsection