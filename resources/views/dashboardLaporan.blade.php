<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>LAPORAN DTKS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
        integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
</head>

<body>
    <table class="table table-bordered">
        <thead>
            <tr>
            <th>NO</th>
            <th>KABUPATEN/KOTA</th>
            <th>TOTAL</th>
            </tr>
        </thead>
        <tbody>
            @php
                $kolom = ['X','A','B','C','D','E','F','G','H','I','J','K','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','Aa','Bb','Cc','Dd','Ee','Ff','Gg','Hh','Ii','Jj','Kk','Mm','Nn','Oo','Pp','Qq','Rr'.'Ss','Tt','Uu','Vv','Ww','Xx','Yy','Zz'];
                $no = 1;
            @endphp
            @foreach ($chartData as $k => $data)
                
                <tr>
                    <td>{{ $no }}</td>
                    <td>{{ $data }}</td>
                    <td>{{ number_format($chartDatatotalDtks[$k],0,',','.') }} </td>
                </tr>
                @php
                    $no++;
                @endphp
            @endforeach
            
        </tbody>
    </table>


    


        <table class="table table-bordered">
            <thead>
                <tr>
                  <th>NO</th>
                  <th>KAB/KOTA</th>
                  @php

                    $i = 1;
                      foreach ($jenisPmks as $key => $pmks) {
                          echo "<th data-priority=".$i.">".$kolom[$i]."</th>";
                          $i++;
                        }
                    
                  @endphp
   
                </tr>
              </thead>
              <tbody>
                  @php
                      $no = 1;
                  @endphp
                 
                  @foreach ($kabupatenKotaTotalPerJenisPmks as $k => $data)
                      
                      <tr>
                          <td>{{ $no }}</td>
                          <td>{{ $data[0]->name }}</td>
                          @php
                              foreach ($jenisPmks as $key => $pmks) {
                                  $totalJenis = 0;
                                  foreach ($data[1] as $key => $totalJenisPmks) {
                                      if($pmks->id == $totalJenisPmks->jenis_pmks_id){
                                          $totalJenis = $totalJenisPmks->total;
                                      }
                                  }
                                  echo "<td>".$totalJenis."</td>";
                                  
                              }
                          @endphp
                         
                      </tr>
                      @php
                          $no++;
                      @endphp
                  @endforeach
                
              </tbody>
        </table>
  


        <table class="table table-bordered">
            <thead>
                <tr>
                <th>NO</th>
                <th>KODE</th>
                <th>JENIS PMKS</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $kolom = ['X','A','B','C','D','E','F','G','H','I','J','K','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','Aa','Bb','Cc','Dd','Ee','Ff','Gg','Hh','Ii','Jj','Kk','Mm','Nn','Oo','Pp','Qq','Rr'.'Ss','Tt','Uu','Vv','Ww','Xx','Yy','Zz'];
                    $no = 1;
                @endphp
                @foreach ($jenisPmks as $k => $pmks)
                    <tr>
                        <td>{{ $no }}</td>
                        <td>{{ $kolom[$no] }}</td>
                        <td>{{ $pmks->jenis }}</td>
                        
                    </tr>
                    @php
                        $no++;
                    @endphp
                @endforeach
                
                
            </tbody>
        </table>
</body>

</html>