<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>DTKS - Sulteng</title>
    
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/adminlte/fontawesome-free/css/all.min.css">
    <!-- Ekko Lightbox -->
    <link rel="stylesheet" href="/adminlte/plugins/ekko-lightbox/ekko-lightbox.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="/filepond/css/ionicons.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="/adminlte/css/adminlte.min.css">
    <!-- DataTable -->
    {{-- <link rel="stylesheet" href="/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.css"> --}}
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    
    <link rel="stylesheet" href="/adminlte/css/sidebar-menu-feature.css">
    <link href="/filepond/css/filepond.css" rel="stylesheet" />

    {{-- <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet"> --}}
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha512-mSYUmp1HYZDFaVKK//63EcZq4iFWFjxSL+Z3T/aCt4IO9Cejm03q3NKKYN6pFQzY0SBOr8h+eCIAZHPXcpZaNw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>

#wrapper-table-errors {
  overflow-x: auto;

}
    .short-text,.full-text {
        font-weight: bold;
        font-size: 1.5em;
    }
    .short-text { 
        display: none; 
    }

    @media (max-width: 1200px) {
        .short-text { display: inline-block; }
        .full-text { display: none; }
    }


        .logo {
            max-width: 100%;
            width: 120px;
        }
        .hide {
            display: none;
        }
		.ex_caption {
			font-weight: 300;
		}
		.th_rotate {
			position: absolute;
			top: 40%;
			left: 50%;
			margin: -16px 0 0 -16px;
			width: 32px;
			height: 32px;
			cursor: pointer;
			background-image: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAMAAABEpIrGAAAABGdBTUEAALGPC/xhBQAAACBjSFJN AAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAAC2VBMVEX////r7+oeHx4dHR0i IiIcHRwlJiUmJiYcHBwbHBsoKSgbGxsZGRkgICAZGhkYGBgfIB8YGRgXGBcfHx8aGhoXFxceHh4W FxYWFhYVFhUVFRUUFRQUFBQTExMTFBMSExISEhIREREQERAREhEQEBAgICAeHx4wMDAqKiogICBA QEAuLi4jJCMeHh5ERUQnKCcfHx9DREMrLCshISEdHh0gISAjIyMlJSUkJSQeHh4dHh0iIiIqKiog ICAfIB8oKSgiIiIgISCipKEfIB/U2NPr7+orKyvj5+Lr7+owMTDO0s3r7+rr7+o/QD/r7+rr7+oe Hh4tLi3o7Ofr7+o7PDvFycTr7+o8PTzZ3djr7+o6Ozrc4Nvr7+pAQT+EhoPr7+o5Ojno7Oc1NjWl p6RBQkHr7+rQ1M/R1dDr7+odHh2ZnJg2NzZXWFZAQUAWFxYaGhoZGhl1d3QrLSsXGBcnJydjZWO4 u7ciIyIYGBifoZ4YGRgTFBMWFhYjJCPj5+LFyMQaGhoUFRQTFBMXFxerrqrr7+qWmZYYGRgXFxcX FxcXGBfr7+rn6+br7+rr7+rr7+rJzcjr7+rr7+rr7+rr7+rr7+rr7+rr7+rr7+rr7+o4OThCQ0I8 PTwxMjFERUQ/QD82NzYlJiUtLS07PDtDRENBQUE5OjkvLy8oKSg0NTQ3ODdAQUBBQkEzMzMxMTE9 Pj0+Pz43NzcsLCwrKytFRUUrLCs/Pz9fYF+Pko/DxsLr7+pFR0V+gH4zNDNfYV+jpaI4ODhmZ2VN Tk06Ozo/QT9naWeOkY41NjW0t7MsLSwwMTAtLi0uLy45OjgyMzIbHBsvMC8qKyopKSkuLi4nJyci IyJhY2FJSkkwMC8gISAdHh0mJyYjIyMcHRwbGxt8fnuDhYIpKikkJSQiIiIjJCNqbGqgo6CHiode YF5RUlC8v7uFh4VKTEpERUN5fHm1uLTi5eHd4dz///81j6aBAAAAmnRSTlMAAAAAAAAAAAAAAAAA AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAigz44Dn+/YQG/tAn/vdyKn7D9robJKjyWlf3pXi+VO9I +c4eufeEOf6cCR7+yif++GaDwgP+4zPc+RL+u/um/YFD5UsDDyn7fhX85OHvD6H1maVFvocGSO1K v9M8GJnhJPznzLrSbJGHqGD6KrfJPNLbk0UMUbL0YwAAAAFiS0dEAIgFHUgAAAAHdElNRQfjBQMG IweiEX1iAAACVElEQVQ4y2NgYGBgRAZMqmrMMMAABSgKWNQ1NJlZIQCrAjatWdo6ungUsOvNnjNX 34CVAwiwm2A4b978BUbGzFgUmJiamS9ctHjJvCVLly23sOTkRFVgZb1i+cpVyxavXgIEa+astbFF NcHOft2s9fPXzIaCNRs2bnJAVuC4ecH61WuQwfwtTkgKnLeuWj8fBWzb7uKKUGDltmD9hg0bluzY uWvX7g0gsH6PuwdSSHruXbx+/fp9Xt4+jFy+QNb6Nfv9/Ll54AoCAg8sXrz4YFAw0DLeECDzUGgY Ex8/P1xB+Nxlhw8fiYgEuVYg6vDho9ExHIJCQkJwBbErly2bFRcP9q5wwqpjifEiQqJAAFeQdHzV qr3JkPASSzmRGskvLgECcAUnjy9YcCoNooAzPYNbVEICRUHm6ePHj5/JgigQ8uCRkIQCmILIs3v3 7j2ZDVEgJSUlDQNwK3Lmnjt3MheiQAYEpPPyz58/XwBXUHhh7tzTRcUIBVIlCy9evFQKV1C29cKF y1fKwQpkgUCu4urJCxdOVsIVBF87febM1qtV1UAF8vLysjW1K4AC1+sQcVF/4/TpkytuNjQyMirI NzXfur319OmtLZEIBa1tW0+e3Lrizt32js6uezdvg3j3XWWQ0kP3g4tbt259ePvR1cdPFq4Asi8+ 7ZFQQE5Rvc/OXASChw8vPQTTz/siFRRR0mT/hBcP4eDlq1QJBSUl1FQ9cdLO129Asm/fvZ88RVpZ CV0BI2OM99RdHz7umjZ9hqSCkgoQYMtZM+PFpeQg0ggFeAEAzmwM75REXZ4AAAAldEVYdGRhdGU6 Y3JlYXRlADIwMTktMDUtMDNUMTM6MzU6MDctMDc6MDASd+GQAAAAJXRFWHRkYXRlOm1vZGlmeQAy MDE5LTA1LTAzVDEzOjM1OjA3LTA3OjAwYypZLAAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VS ZWFkeXHJZTwAAAAASUVORK5CYII=");
		}
		.th_remove {
			position: absolute;
			top: .5rem;
			right: .5rem;
			font-size: 2em;
			opacity: 0.8;
		}
		.th_progress {
			position: absolute;
			top: 4rem;
			right: 1rem;
			left: 1rem;
			height: .4rem;
			display: none;
		}
		.th_progress .progress-bar {
			width: 0%;
		}

        /* Style the Image Used to Trigger the Modal */
        #myImg {
        border-radius: 5px;
        cursor: pointer;
        transition: 0.3s;
        }

        #myImg:hover {opacity: 0.7;}

        /* The Modal (background) */
        .modalimage {
        display: none; /* Hidden by default */
        position: fixed; /* Stay in place */
        z-index: 1; /* Sit on top */
        padding-top: 100px; /* Location of the box */
        left: 0;
        top: 0;
        width: 100%; /* Full width */
        height: 100%; /* Full height */
        overflow: auto; /* Enable scroll if needed */
        background-color: rgb(0,0,0); /* Fallback color */
        background-color: rgba(0,0,0,0.9); /* Black w/ opacity */
        }

        /* Modal Content (Image) */
        .modalimage-content {
        margin: auto;
        display: block;
        width: 80%;
        max-width: 700px;
        }

        /* Caption of Modal Image (Image Text) - Same Width as the Image */
        #caption {
        margin: auto;
        display: block;
        width: 80%;
        max-width: 700px;
        text-align: center;
        color: #ccc;
        padding: 10px 0;
        height: 150px;
        }

        /* Add Animation - Zoom in the Modal */
        .modalimage-content, #caption {
        animation-name: zoom;
        animation-duration: 0.6s;
        }

        @keyframes zoom {
        from {transform:scale(0)}
        to {transform:scale(1)}
        }

        /* The Close Button */
        .close {
        position: absolute;
        top: 15px;
        right: 35px;
        color: #f1f1f1;
        font-size: 40px;
        font-weight: bold;
        transition: 0.3s;
        }

        .close:hover,
        .close:focus {
        color: #bbb;
        text-decoration: none;
        cursor: pointer;
        }

        /* 100% Image Width on Smaller Screens */
        @media only screen and (max-width: 700px){
        .modalimage-content {
            width: 100%;
        }
        }

        .mycontainer {
  background: #eee;
  padding: 32px;
  margin: 0 auto;
  max-width: 500px;
  
  .select2 {
    width: 100%!important; /* force fluid responsive */
  }
  
  .select2-container {
    
    .select2-selection--single {
       height: 56px;
       position: relative;
      
      .select2-selection__rendered {
        line-height: 56px;
      }
    
      .select2-selection__arrow {
        top: 16px;
        right: 8px;
      }
  
      .select2-container--default {

        .select2-results>.select2-results__options {
          -webkit-overflow-scrolling: touch; /* use momentum scrolling */
        }
      }
    }      
  }
}


	</style>

</head>

<body class="hold-transition sidebar-mini">
    <!-- Site wrapper -->
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
                </li>
                {{-- <h3 class="font-weight-bold">[DTKS] Data Terpadu Kesejahteraan Sosial - Sulawesi Tengah</h3> --}}
                <span class="full-text">[DTKS] DATA TERPADU KESEJAHTERAAN SOSIAL - SULAWESI TENGAH</span>
                <span class="short-text">DTKS - SULTENG</span>

                {{-- <p class="font-weight-bold">
                    <abbr title="[DTKS] Data Terpadu Kesejahteraan Sosial"> DTKS </abbr> - Sulawesi Tengah
                  </p> --}}
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- Messages Dropdown Menu -->
                <!-- Notifications Dropdown Menu -->
                <li class="nav-item dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fas fa-user mr-2"></i> &nbsp;<span>{{auth()->user()->name}}</span> &nbsp;<i
                            class="icon-submenu lnr lnr-chevron-down"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <span class="dropdown-item dropdown-header">Profil</span>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" data-toggle="modal" data-target="#lihatprofile">
                            <i class="fas fa-user mr-2"></i> Lihat Profil
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="/logout" class="dropdown-item">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </a>
                    </div>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-light-primary elevation-1">
            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user (optional) -->
                <div class="mt-3 pb-3 mb-1">
                    <div></div><br>
                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->

                        <li class="nav-item 
                        @isset($class_menu_data_dashboard)
                            {{ $class_menu_data_dashboard}}
                        @endisset 
                        ">
                            <a href="/dashboard" class="nav-link">
                                <i class="nav-icon fas fa-home"></i>
                                <p>
                                    Beranda
                                </p>
                            </a>
                        </li>
                        <li class="nav-item
                        @isset($class_menu_data_pmks)
                            {{ $class_menu_data_pmks }}
                        @endisset 
                        ">
                            <a href="/pmks/data" class="nav-link">
                                <i class="nav-icon fas fa-book"></i>
                                <p>
                                    Data PMKS
                                </p>
                            </a>
                        </li>
                        <li class="nav-item has-treeview 
                        @isset($class_menu_pmks)
                            {{ $class_menu_pmks }}
                        @endisset
                        ">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-mail-bulk"></i>
                                <p>
                                    Import Data PMKS
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="/pmks/import-data" class="nav-link 
                                    @isset($class_menu_pmks_import)
                                        {{ $class_menu_pmks_import }}
                                    @endisset
                                    ">
                                        <i class="far fa-envelope nav-icon"></i>
                                        <p>Import Data</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="/pmks/daftar-pmks" class="nav-link 
                                    @isset($class_menu_daftar_pmks)
                                        {{ $class_menu_daftar_pmks }}
                                    @endisset
                                    ">
                                        <i class="far fa-envelope-open nav-icon"></i>
                                        <p>Daftar Data</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="/dtks/posting" class="nav-link 
                                    @isset($class_menu_posting)
                                        {{ $class_menu_posting }}
                                    @endisset
                                    ">
                                        <i class="fas fa-plane"></i>
                                        <p>Posting</p>
                                    </a>
                                </li>

                            </ul>
                        </li>
                        
                        @if (auth()->user()->role == 'admin')
                        <li class="nav-item has-treeview 
                        @isset($class_menu_pengaturan)
                            {{ $class_menu_pengaturan }}
                        @endisset 
                        ">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-cogs"></i>
                                <p>
                                    Master
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="" class="nav-link
                                    @isset($class_menu_pengaturan_instansi)
                                        {{ $class_menu_pengaturan_instansi }}
                                    @endisset 
                                    ">
                                        <i class="fas fa-warehouse nav-icon"></i>
                                        <p>Master data</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="" class="nav-link 
                                    @isset($class_menu_pengaturan_pengguna)
                                        {{ $class_menu_pengaturan_pengguna }}
                                    @endisset 
                                    ">
                                        <i class="fas fa-users-cog nav-icon"></i>
                                        <p>Master pengguna </p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
                <br>
                {{-- <center><h4><strong>DTKS</strong></h4></center> --}}
                <center><img src="/img/logo-sulteng.png" alt="Logo" class="logo"><center>
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper" style="background: #192192192; padding: 15px 15px 15px 15px ">

            @yield('content')



        </div>
        <!-- /.content-wrapper -->

        <footer class="main-footer">
            <div class="float-right d-none d-sm-block">
                <b>TEMPORARY APP</b>
            </div>
            DTKS PROVINSI SULAWESI TENGAH
        </footer>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="/adminlte/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="/adminlte/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="/adminlte/js/adminlte.min.js"></script>
    <!-- Ekko Lightbox -->
    <script src="/adminlte/plugins/ekko-lightbox/ekko-lightbox.min.js"></script>
    <!-- Filterizr-->
    <script src="/adminlte/plugins/filterizr/jquery.filterizr.min.js"></script>
    <!-- Data Table -->
    {{-- <script src="/adminlte/plugins/datatables/jquery.dataTables.js"></script> --}}
    {{-- <script src="/adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script> --}}

    {{-- <script src="/filepond/js/dataTables.responsive.min.js"></script> --}}

    <script src="/filepond/js/filepond-plugin-file-validate-size.js"></script>
    <script src="/filepond/js/filepond.js"></script>

    {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>   --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    {{-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script> --}}
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>


    <script src="https://unpkg.com/echarts/dist/echarts.min.js"></script>
    <!-- Chartisan -->
    <script src="https://unpkg.com/@chartisan/echarts/dist/chartisan_echarts.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.5.2/bootbox.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.5.2/bootbox.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>



    <script>

        $(function () {

        $.fn.select2.defaults.set( "theme", "bootstrap" );

        $('[data-toggle="tooltip"]').tooltip()
        })

        FilePond.registerPlugin(
        FilePondPluginFileValidateSize
        );

        const pond = FilePond.create(
        document.querySelector('input[id="upload"]'),
        {
            labelMaxTotalFileSizeExceeded: 'Ukuran total keseluruhan file terlampaui',
            labelMaxTotalFileSize: 'Total maksimum seluruh file adalah {filesize}',
            maxTotalFileSize: '50MB'
        });

        
        // const modal = document.getElementById("myModalimage");
        // const modalImg = document.getElementById("img01");
        // const captionText = document.getElementById("caption");

        // pond.on('activatefile', (file) => {
        //     const urlCreator = window.URL || window.webkitURL;
        //     const imageUrl = urlCreator.createObjectURL(file.file);
        //     modal.style.display = "block";
        //     modalImg.src = imageUrl;
        //     captionText.innerHTML = file.filename;
            
        // });

         // Get the <span> element that closes the modal
         const span = document.getElementsByClassName("close")[0];

        // When the user clicks on <span> (x), close the modal
        // span.onclick = function() {
        //     modal.style.display = "none";
        // }


    </script>

    @yield('file-pond-import')
    @yield('file-pond-data-import')
    @yield('js-create-pmks')

    
    <script>


        $(function () {
                        
            $("#tabel-daftar-pmks").DataTable();
        });

    </script>


    <!-- Modal Profile -->
    <div class="modal fade" id="lihatprofile" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLabel"><i class="nav-icon fas fa-user my-1 btn-sm-1"></i>
                        &nbsp;Profil Pengguna</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-3">
                            <h5><label for="nama">Nama </label></h5>
                        </div>
                        <div class="col-9">
                            <h5><label for="nama"> : {{auth()->user()->name}}</label></h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-3">
                            <h5><label for="nama">Email </label></h5>
                        </div>
                        <div class="col-9">
                            <h5><label for="nama"> : {{auth()->user()->email}}</label></h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-3">
                            <h5><label for="nama">Level User </label></h5>
                        </div>
                        <div class="col-9">
                            <h5><label for="nama"> : {{auth()->user()->role}}</label></h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
</body>

</html>
