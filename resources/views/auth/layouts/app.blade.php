<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SIM RM</title>
    <link rel="icon" href="{{ asset('backend/assets/logo/logo.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('backend/assets/css/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/css/select2.css') }}">

    <link rel="stylesheet" href="{{ asset('backend/assets/css/app-light.css') }}">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
        }

        label.required::after {
            content: '*';
            color: red;
        }

        body.bg-with-overlay {
            position: relative;
            background-image: url('/backend/assets/images/bg-login.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        body.bg-with-overlay::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            /* 0.4 = tingkat kegelapan */
            z-index: 0;
        }

        .form-control:focus {
            box-shadow: none;
        }

        .wrapper,
        .main-content {
            position: relative;
            z-index: 1;
        }
    </style>
</head>

<body class="light bg-with-overlay">
    <div class="wrapper">
        <main role="main" class="main-content">
            @yield('content')
        </main>
    </div>
</body>


<script src="{{ asset('backend/assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('backend/assets/js/quill.min.js') }}"></script>
<script src="{{ asset('backend/assets/js/popper.min.js') }}"></script>
<script src="{{ asset('backend/assets/js/bootstrap.min.js') }}"></script>
<script src='{{ asset('backend/assets/js/select2.min.js') }}'></script>

</html>
