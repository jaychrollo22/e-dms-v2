<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>E-DMS</title>
    <link rel="stylesheet" href="{{asset('skydash/vendors/feather/feather.css')}}">
    <link rel="stylesheet" href="{{asset('skydash/vendors/ti-icons/css/themify-icons.css')}}">
    <link rel="stylesheet" href="{{asset('skydash/vendors/css/vendor.bundle.base.css')}}">
    <link rel="stylesheet" href="{{asset('skydash/css/vertical-layout-light/style.css')}}">
    <link rel="shortcut icon" href="{{asset('img/app_icon.png')}}" />
</head>

<body>
    <div id="app">
        <div class="container-scroller">
            <div class="container-fluid page-body-wrapper full-page-wrapper">
                <div class="content-wrapper d-flex align-items-stretch auth auth-img-bg">
                    <div class="row flex-grow">
                        <div class="col-lg-6 d-flex align-items-center justify-content-center">
                            <div class="auth-form-transparent text-left p-3">
                                <div class="brand-logo">
                                    <img src="{{asset('img/app_logo.png')}}" alt="logo">
                                </div>
                                @yield('content')
                            </div>
                        </div>
                        <div class="col-lg-6 login-half-bg d-flex flex-row">
                            <p class="text-white font-weight-medium text-center flex-grow align-self-end">Copyright
                                &copy; 2022 All rights reserved.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <script src="{{ asset('js/app.js') }}"></script>

    <script src="{{asset('skydash/vendors/js/vendor.bundle.base.js')}}"></script>
    <script src="{{asset('skydash/js/off-canvas.js')}}"></script>
    <script src="{{asset('skydash/js/hoverable-collapse.js')}}"></script>
    <script src="{{asset('skydash/js/template.js')}}"></script>
    <script src="{{asset('skydash/js/settings.js')}}"></script>
    <script src="{{asset('skydash/js/todolist.js')}}"></script>
</body>

</html>
