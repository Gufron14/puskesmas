<!DOCTYPE html>
<html lang="en">

<head>
    @include('frontend.layouts.script.topscript')
</head>

<body id="page-top">
    @include('frontend.layouts.partials.navbar')
    @yield('content')
    @include('frontend.layouts.partials.footer')
    @include('frontend.layouts.script.botscript')
</body>

</html>
