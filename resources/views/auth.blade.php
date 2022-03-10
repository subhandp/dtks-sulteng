<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        {{-- <link rel="stylesheet" href="/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.css"> --}}
        <link rel="stylesheet" href="/adminlte/fontawesome-free/css/all.min.css">
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!------ Include the above in your HEAD tag ---------->

        <title>DTKS - Sulawesi tengah</title>
       
        <link rel="shortcut icon" href="/img/logo-sulteng.png">
        
    <style type="text/css">
    .logo {
                max-width: 100%;
                width: 60px;
            }

    body {
        margin: 0;
        font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol";
        font-size: 1rem;
        font-weight: 400;
        line-height: 1.5;
        color: #212529;
        text-align: left;
        background-color: #1f708e;
    }
        .user_card {
                height: 400px;
                width: 350px;
                margin-top: auto;
                margin-bottom: auto;
                background: #9acadc;
                position: relative;
                display: flex;
                justify-content: center;
                flex-direction: column;
                padding: 10px;
                box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
                -webkit-box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
                -moz-box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
                border-radius: 5px;
    
            }
            .brand_logo_container {
                position: absolute;
                height: 170px;
                width: 170px;
                top: -75px;
                border-radius: 50%;
                background: #9acadc;
                padding: 10px;
                text-align: center;
            }
            .brand_logo {
                height: 150px;
                width: 150px;
                border-radius: 50%;
                border: 2px solid white;
            }
            .form_container {
                margin-top: 100px;
            }
            .login_btn {
                width: 100%;
                background: #c0392b !important;
                color: white !important;
            }
            .login_btn:focus {
                box-shadow: none !important;
                outline: 0px !important;
            }
            .login_container {
                padding: 0 2rem;
            }
            .input-group-text {
                background: #c0392b !important;
                color: white !important;
                border: 0 !important;
                border-radius: 0.25rem 0 0 0.25rem !important;
            }
            .input_user,
            .input_pass:focus {
                box-shadow: none !important;
                outline: 0px !important;
            }
            .custom-checkbox .custom-control-input:checked~.custom-control-label::before {
                background-color: #c0392b !important;
            }
    
            .judullogin {
                color: #363079;
            }
    
    </style>
    
    
    </head>
    <!--Coded with love by Mutiullah Samim-->
    <body>
        <div class="container h-100">
            <div class="d-flex justify-content-center h-100">
                <div class="user_card">
                    <div class="d-flex justify-content-center">
                        
                        <div class="brand_logo_container">
                            <img src="/img/logo-sulteng.png" alt="Logo" class="logo" >
                            <div >
                                <h3 class="judullogin">DTKS</h3>

                                <span>Data Terpadu Kesejahteraan Sosial</span>
                            </div>
                            
                        </div>
                        
                    </div>
                    <div class="d-flex justify-content-center form_container">
                        <form method="POST" action="postlogin">
                            @csrf
                            <div class="input-group mb-3">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                </div>
                                <input id="username" type="text" class="form-control" name="username" value="" placeholder="Username" required="" autofocus="">
                            </div>
                            <div class="input-group mb-2">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-key"></i></span>
                                </div>
                                <input id="password" type="password" class="form-control " name="password" required="" autocomplete="on" placeholder="Password">
                            </div>
                           
                        <div class="d-flex justify-content-center mt-3 login_container">
                        <button type="submit" class="btn login_btn">Masuk</button>
                       </div>
                        </form>
                    </div>
            
                 
                </div>
            </div>
        </div>
    </body>
    {{-- <script src="/adminlte/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="/adminlte/bootstrap/js/bootstrap.bundle.min.js"></script> --}}
    </html>