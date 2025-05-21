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
            <h1 class="display-1 text-white font-weight-bold">404</h1>
            <h2 class="text-white font-weight-bold">Oops! Halaman tidak ditemukan.</h2>
            <h6 class="lh-base text-white">
                Error 404 Not Found terjadi ketika halaman yang diminta tidak dapat ditemukan di server situs web.
                Masalah ini dapat terjadi karena beberapa alasan: halaman mungkin telah dihapus atau dipindahkan, tautan
                yang Anda ikuti mungkin sudah usang atau salah, atau mungkin ada kesalahan pengetikan pada URL yang Anda
                masukkan. Bisa juga halaman tersebut memang tidak pernah ada. Silakan periksa kembali URL untuk
                memastikan tidak ada kesalahan, lalu coba lagi.
            </h6>
            <p>
                <a href="{{ url('/') }}" class="btn btn-lg btn-light px-5 mt-4 font-weight-bold text-bd-primary">Kembali Ke
                    Beranda</a>
            </p>
        </div>
    </div>

</body>

</html>
