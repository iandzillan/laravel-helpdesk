<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
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
                                    @if (session()->has('error'))
                                    <div class="alert bg-danger d-flex align-items-center" role="alert">
                                            <i class="fa-solid fa-triangle-exclamation text-white"> </i>
                                            <div class="text-white px-2">
                                                {{session()->get('error')}}
                                            </div>
                                        </div>
                                    @endif
                                    <form action="{{route('loginProcess')}}" method="POST">
                                        @csrf
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label for="username" class="form-label">Username</label>
                                                    <input type="text" class="form-control @error('username') is-invalid @enderror" name="username" id="username" placeholder="Enter your username" value="{{old('username')}}">
                                                    @error('username')
                                                        <div class="invalid-feedback">
                                                            {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label for="password" class="form-label">Password</label>
                                                    <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="password" placeholder="Enter your password">
                                                    @error('password')
                                                        <div class="invalid-feedback">
                                                            {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-center">
                                            <button type="submit" class="btn btn-primary">Sign In</button>
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
</body>
</html>