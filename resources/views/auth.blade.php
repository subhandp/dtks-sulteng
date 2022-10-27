<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>DTKS</title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400" rel="stylesheet" /> <!-- https://fonts.google.com/ -->
    <!-- <link href="css/bootstrap.min.css" rel="stylesheet" /> https://getbootstrap.com/ -->
    <!-- <link href="fontawesome/css/all.min.css" rel="stylesheet" /> https://fontawesome.com/ -->
    <!-- <link href="css/templatemo-diagoona.css" rel="stylesheet" /> -->
    
    


    <link rel="stylesheet" href="/adminlte/fontawesome-free/css/all.min.css">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link rel="stylesheet" href="/diagoona/css/templatemo-diagoona.css">    
    
    <style type="text/css">
        .logo {
                    max-width: 100%;
                    width: 60px;
                    margin-right: 20px;
                }

    </style>
    <!-- <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> -->

<!--

TemplateMo 550 Diagoona

https://templatemo.com/tm-550-diagoona

-->
</head>

<body>
    <div class="tm-container">        
        <div>
            <div class="tm-row pt-4">
                <div class="tm-col-left">
                    <div class="tm-site-header media">
                        {{-- <i class="fas fa-umbrella-beach fa-3x mt-1 tm-logo"></i>
                         --}}
                         <img src="/img/logo-sulteng.png" alt="Logo" class="logo" >
                         <div class="media-body">
                            <h1 class="tm-sitename text-uppercase">DTKS</h1>
                            <p class="tm-slogon">Data Terpadu Kesejahteraan Sosial Pemprov Sulawesi Tengah</p>

                        </div>        
                    </div>
                </div>
                <div class="tm-col-right">
                    <nav class="navbar navbar-expand-lg" id="tm-main-nav">
                        <button class="navbar-toggler toggler-example mr-0 ml-auto" type="button" 
                            data-toggle="collapse" data-target="#navbar-nav" 
                            aria-controls="navbar-nav" aria-expanded="false" aria-label="Toggle navigation">
                            <span><i class="fas fa-bars"></i></span>
                        </button>
                        <div class="collapse navbar-collapse tm-nav" id="navbar-nav">
                            <ul class="navbar-nav text-uppercase">
                                <li class="nav-item active">
                                    <a class="nav-link tm-nav-link" href="#">Home <span class="sr-only">(current)</span></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link tm-nav-link" href="{{ route('login.pmks') }}">SIMAKS <i class="far fa-arrow-alt-circle-right "></i></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link tm-nav-link" href="{{ route('login.psks') }}">SIMPOTEN <i class="far fa-arrow-alt-circle-right"></i> </a>
                                </li>                            
                              
                            </ul>                            
                        </div>                        
                    </nav>
                </div>
            </div>
            
            <div class="tm-row">
                <div class="tm-col-left"></div>
                <main class="tm-col-right">
                    <section class="tm-content">
                        
                        <h2 class="mb-5 tm-content-title">DATA TERPADU KESEJAHTERAAN SOSIAL</h2>
                        <p class="mb-5"><strong> SIMAKS  (Sistem Infomasi Pemerlu Pelayanan Kesejahteraan Sosial) </strong> adalah seseorang, keluarga atau kelompok masyarakat yang karena suatu hambatan, kesulitan atau gangguan, tidak dapat melaksanakan fungsi sosialnya, sehingga tidak dapat terpenuhi kebutuhan hidupnya(jasmani, rohani, dan sosial) secara memadai dan wajar.</p>
                        <hr class="mb-5">
                        <p class="mb-5"><strong> SIMPOTEN (Sisitem Informasi Potensi Sumber Kesejahteraan Sosial) </strong> adalah perseorangan, keluarga, kelompok, dan/atau masyarakat yang dapat berperan serta untuk menjaga, menciptakan, mendukung, dan memperkuat penyelenggaraan kesejahteraan sosial.</p>                        
                      
                    </section>
                </main>
            </div>
        </div>        

        <div class="tm-row">
            <div class="tm-col-left text-center">            
                <ul class="tm-bg-controls-wrapper">
                    <li class="tm-bg-control active" data-id="0"></li>
                    <li class="tm-bg-control" data-id="1"></li>
                    <li class="tm-bg-control" data-id="2"></li>
                </ul>            
            </div>        
            <div class="tm-col-right tm-col-footer">
                <footer class="tm-site-footer text-right">
                    <p class="mb-0">Hani Prima Sejahtera 2022</p>
                </footer>
            </div>  
        </div>

        <!-- Diagonal background design -->
        <div class="tm-bg">
            <div class="tm-bg-left"></div>
            <div class="tm-bg-right"></div>
        </div>
    </div>

    <!-- <script src="js/jquery-3.4.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.backstretch.min.js"></script>
    <script src="js/templatemo-script.js"></script>

    <link rel="stylesheet" href="/diagoona/css/templatemo-diagoona.css">    
     -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="/diagoona/js/jquery.backstretch.min.js"></script>
    <script src="/diagoona/js/templatemo-script.js"></script>



</body>
</html>