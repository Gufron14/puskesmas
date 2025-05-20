<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">
<meta name="author" content="">
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="icon" href="{{ asset('backend/assets/logo/logo.png') }}">
<title>SIM RM</title>
<!-- Simple bar CSS -->
<link rel="stylesheet" href="{{ asset('backend/assets/css/simplebar.css') }}">
<!-- Fonts CSS -->
<link
    href="https://fonts.googleapis.com/css2?family=Overpass:ital,wght@0,100;0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,600;1,700;1,800;1,900&display=swap"
    rel="stylesheet">
<!-- Icons CSS -->
<link rel="stylesheet" href="{{ asset('backend/assets/css/feather.css') }}">
<link rel="stylesheet" href="{{ asset('backend/assets/css/select2.css') }}">
<!-- App CSS -->
<link rel="stylesheet" href="{{ asset('backend/assets/css/app-light.css') }}" id="lightTheme">
<link rel="stylesheet" href="{{ asset('backend/assets/css/app-dark.css') }}" id="darkTheme" disabled>
<style>
    #reader {
        width: 100%;
        max-width: 500px;
        margin: auto;
        border: 1px solid #ccc;
        padding: 10px;
        border-radius: 8px;
        background-color: #f9f9f9;
    }

    #result {
        margin-top: 20px;
        font-size: 18px;
        text-align: center;
        font-weight: bold;
    }
</style>
