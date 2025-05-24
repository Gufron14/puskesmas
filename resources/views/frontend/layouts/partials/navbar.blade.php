<!-- Navigation-->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="mainNav">
    <div class="container">
        <a class="navbar-brand text-white" href="#page-top">SIM RM</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive"
            aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            Menu
            <i class="fas fa-bars ms-1"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav text-uppercase ms-auto py-4 py-lg-0 d-flex align-items-center">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('frontend') }}">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('daftar') }}">Pendaftaran</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('masukan') }}">Masukan</a>
                </li>
                @guest
                    <!-- Belum login: tampilkan tombol login & register -->
                    <li class="nav-item">
                        <a class="nav-link btn btn-warning fw-bold me-2 text-dark px-3"
                            href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-warning fw-bold text-dark px-3"
                            href="{{ route('register') }}">Register</a>
                    </li>
                @else
                    <!-- Sudah login: tampilkan foto profil -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('profil') }}">
                            <img src="{{ Auth::user()->foto ? asset('storage/' . Auth::user()->foto) : asset('backend/assets/avatars/Profiledefault.png') }}"
                                alt="Profil" height="40" class="rounded-circle">
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-warning fw-bold text-dark px-3" href="{{ route('logout') }}"
                            onclick="Logout();">Logout</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
