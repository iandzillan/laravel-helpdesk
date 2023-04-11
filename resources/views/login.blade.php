<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{$title}}</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico" />

    <!-- Library / Plugin Css Build -->
    <link rel="stylesheet" href="assets/css/core/libs.min.css" />

    <!-- Hope Ui Design System Css -->
    <link rel="stylesheet" href="assets/css/hope-ui.min.css?v=1.2.0" />

    <!-- Custom Css -->
    <link rel="stylesheet" href="assets/css/custom.min.css?v=1.2.0" />

    <!-- Dark Css -->
    <link rel="stylesheet" href="assets/css/dark.min.css"/>

    <!-- Customizer Css -->
    <link rel="stylesheet" href="assets/css/customizer.min.css" />

    <!-- RTL Css -->
    <link rel="stylesheet" href="assets/css/rtl.min.css"/>

    {{-- Fontawasome --}}
    <link href="assets/vendor/fontawesome-free/css/fontawesome.min.css" rel="stylesheet">
    <link href="assets/vendor/fontawesome-free/css/brands.min.css" rel="stylesheet">
    <link href="assets/vendor/fontawesome-free/css/solid.min.css" rel="stylesheet">

    {{-- Sweetalert --}}
    <script src="{{asset('assets/vendor/sweetalert/dist/sweetalert2.all.min.js')}}"></script>
    <link rel="stylesheet" href="{{asset('assets/vendor/sweetalert/dist/sweetalert2.min.css')}}">
</head>
<body data-bs-spy="scroll" data-bs-target="#elements-section" data-bs-offset="0" tabindex="0">
    {{-- Loader start --}}
    <div id="loading">
        <div class="loader simple-loader">
            <div class="loader-body"></div>
        </div>
    </div>
    {{-- Loader end --}}

    <div class="wrapper">
        <section class="login-content">
            <div class="row m-0 align-items-center bg-white vh-100"> 
                <div class="col-md">
                    <div class="row justify-content-center">
                        <div class="col-md-5">
                            <div class="card card-transparent shadow-none d-flex justify-content-center mb-0 auth-card">
                                <div class="card-body bg-soft-primary">
                                    <div class="navbar-brand d-flex align-items-center mb-3">
                                        <i class="fa-solid fa-screwdriver-wrench"></i>
                                        <h4 class="logo-title ms-3">Helpdesk Ticketing System</h4>
                                    </div>
                                    <h2 class="mb-2 text-center">Sign In</h2>
                                    <form>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label for="username" class="form-label">Username</label>
                                                    <input type="text" class="form-control @error('username') is-invalid @enderror" name="username" id="username" placeholder="Enter your username" value="{{old('username')}}">
                                                    <div class="invalid-feedback d-none" role="alert" id="alert-username"></div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label for="password" class="form-label">Password</label>
                                                    <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="password" placeholder="Enter your password">
                                                    <div class="invalid-feedback d-none" role="alert" id="alert-password"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-center">
                                            <button type="submit" class="btn btn-primary" id="login">Sign In</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>


    <!-- Library Bundle Script -->
    <script src="assets/js/core/libs.min.js"></script>
    
    <!-- External Library Bundle Script -->
    <script src="assets/js/core/external.min.js"></script>
    
    <!-- Widgetchart Script -->
    <script src="assets/js/charts/widgetcharts.js"></script>
    
    <!-- mapchart Script -->
    <script src="assets/js/charts/vectore-chart.js"></script>
    <script src="assets/js/charts/dashboard.js" ></script>
    
    <!-- fslightbox Script -->
    <script src="assets/js/plugins/fslightbox.js"></script>
    
    <!-- Settings Script -->
    <script src="assets/js/plugins/setting.js"></script>
    
    <!-- Slider-tab Script -->
    <script src="assets/js/plugins/slider-tabs.js"></script>
    
    <!-- Form Wizard Script -->
    <script src="assets/js/plugins/form-wizard.js"></script>
    
    <!-- AOS Animation Plugin-->
    
    <!-- App Script -->
    <script src="assets/js/hope-ui.js" defer></script>

    <script>
        $('body').on('click', '#login', function(e){
            e.preventDefault();

            let username = $('#username').val();
            let password = $('#password').val();
            let token    = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: "{{route('loginProcess')}}",
                type: "post",
                cache: false,
                data: {
                    'username': username,
                    'password': password, 
                    '_token'  : token
                },
                success:function(response){
                    if (response.success) {
                        swal.fire({
                            icon: 'success',
                            title: response.message,
                            showConfirmButton: false,
                            timer: 2000
                        }).then(function(){
                            window.location.href = response.link
                        });
                    } else {
                        swal.fire({
                            icon: 'warning',
                            title: response.message,
                            text: 'Username or password wrong',
                            showConfirmButton: false, 
                            timer: 2000
                        });
                    }
                }, 
                error: function(error){
                    console.log(error.responseJSON.message);
                    if (error.responseJSON.username) {
                        $('#username').addClass('is-invalid');
                        $('#alert-username').addClass('d-block');
                        $('#alert-username').removeClass('d-none');
                        $('#alert-username').html(error.responseJSON.username);
                    } else {
                        $('#username').removeClass('is-invalid');
                        $('#alert-username').removeClass('d-block');
                        $('#alert-username').addClass('d-none');
                    }

                    if (error.responseJSON.password) {
                        $('#password').addClass('is-invalid');
                        $('#alert-password').addClass('d-block');
                        $('#alert-password').removeClass('d-none');
                        $('#alert-password').html(error.responseJSON.password);
                    } else {
                        $('#password').removeClass('is-invalid');
                        $('#alert-password').removeClass('d-block');
                        $('#alert-password').addClass('d-none');
                    }
                }
            });
        });
    </script>
</body>
</html>