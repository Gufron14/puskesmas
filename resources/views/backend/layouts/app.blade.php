<!doctype html>
<html lang="en">

<head>
    @include('backend.layouts.script.topscript')
</head>

<body class="vertical  light  ">
    <div class="wrapper">
        @include('backend.layouts.partials.navbar')
        @include('backend.layouts.partials.sidebar')
        <main role="main" class="main-content">
            @yield('content')
        </main> <!-- main -->
        @include('backend.layouts.partials.footer')
    </div> <!-- .wrapper -->
    @include('backend.layouts.script.botscript')
</body>

</html>
