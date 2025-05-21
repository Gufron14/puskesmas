<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIM RM</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('backend/assets/logo/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/css/app-light.css') }}">
    <link
        href="https://fonts.googleapis.com/css2?family=Overpass:ital,wght@0,100;0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
</head>

<body class="bg-success">
    <div class="container align-items-center d-flex mx-auto" style="height: 100vh">
        <div class="mx-auto text-center">
            <h1 class="display-1 text-light font-weight-bold">500</h1>
            <h2 class="text-light font-weight-bold">Terjadi kesalahan pada server kami.</h2>
            <h6 class="lh-base text-light">
                Error 500 Internal Server Error menunjukkan bahwa terjadi masalah pada server situs web,
                namun server tidak dapat memberikan rincian lebih lanjut tentang masalah yang terjadi.
                Masalah ini dapat disebabkan oleh beban server yang terlalu tinggi, kesalahan konfigurasi server,
                bug pada perangkat lunak, atau kondisi tak terduga yang membuat server gagal memenuhi permintaan.
                Silakan coba lagi nanti atau hubungi administrator situs jika masalah terus berlanjut.
            </h6>
            <p>
                <a href="{{ url('/') }}" class="btn btn-lg btn-light px-5 mt-4 font-weight-bold text-bd-primary">Kembali Ke
                    Beranda</a>
            </p>
        </div>
    </div>

</body>

</html>
