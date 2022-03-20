@extends('layouts.master')
@section('content')
<section class="content card" style="padding: 10px 10px 10px 10px ">
    <div class="box">
        <div class="row">
            <div class="col">
                <center>
                    <h3 class="font-weight-bold">DASHBOARD</h3>
                    <hr />
                </center>
                <br>
            </div>
        </div>

        <div class="row">
            <div class="card-body">
                <!-- Small boxes (Stat box) -->
                <div class="filter-container p-0 row">
                
                    <div class="container px-4 mx-auto">

                        <div class="p-6 m-20 bg-white">
                            
                            <div>
                                {{-- <form action="">
                                    <select class="form-control" id="jenis_pmks" name="jenis_pmks" data-placeholder="Pilih Jenis PMKS" style="width: 100%" >
                                        @if (!empty($jenisPmksSelect))
                                            <option value="{{ $jenisPmksSelect->id }}" selected="selected">{{ $jenisPmksSelect->jenis }}</option>
                                        @endif
                                    </select>
                                </form><br> --}}
                                
                            </div>

                            {!! $chart->container() !!}
                        </div>
                        @php
                            // dd($chartData);
                        @endphp
                        <table class="table table-bordered table-chart">
                            <thead>
                              <tr>
                                <th>NO</th>
                                <th>KABUPATEN/KOTA</th>
                                <th>TOTAL</th>
                              </tr>
                            </thead>
                            <tbody>
                                @php
                                    $no = 1;
                                @endphp
                                @foreach ($chartData as $k => $data)
                                    
                                    <tr>
                                        <td>{{ $no }}</td>
                                        <td>{{ $data->kabupaten_kota }}</td>
                                        <td>{{ $data->total }} </td>
                                    </tr>
                                    @php
                                        $no++;
                                    @endphp
                                @endforeach
                              
                            </tbody>
                          </table>
                    
                    </div>
                    
                    <!-- ./col -->
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('js-create-pmks')


<script src="{{ $chart->cdn() }}"></script>

{{ $chart->script() }}

<script>
    var table = $('.table-chart').DataTable( {
        "lengthMenu": [[5, 10, -1], [5, 10, "All"]]
    } );
    // $.ajaxSetup({
    //     headers: {
    //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //     }
    // });

    // $.fn.select2.defaults.set( "theme", "bootstrap" );
    // $('#jenis_pmks').select2({
    //         allowClear: true,
    //         ajax: {
    //            url: "{{ route('dashboard.get-jenis-pmks') }}",
    //            dataType: 'json',
    //            processResults: function(data) {
    //               return {
    //                  results: $.map(data, function(item) {
    //                     return {
    //                        text: item.jenis,
    //                        id: item.id
    //                     }
    //                  })
    //               };
    //            }
    //         }
    //     });

    //     $('#jenis_pmks').change(function() {
            
    //         let jenisPmksIdSelect2 = $(this).val();

    //         $.ajax({
    //             type: 'POST',
    //             url: "{{ route('dashboard.set-session') }}",
    //             data: {jenisPmksId: jenisPmksIdSelect2},
    //             dataType: 'json',
    //             success: function (data) {
    //                 location.reload();
    //             },
    //             error: function (data) {
    //                 console.log(data);
    //             }
    //         });
         
            
            
    //      });

</script>

@endsection
