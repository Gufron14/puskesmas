<aside class="sidebar-left border-right bg-success shadow" id="leftSidebar" data-simplebar>
    <a href="#" class="btn collapseSidebar toggle-btn d-lg-none text-muted ml-2 mt-3" data-toggle="toggle">
        <i class="fe fe-x"><span class="sr-only"></span></i>
    </a>
    <nav class="vertnav navbar navbar-light">
        <!-- Navbar Logo -->
        <div class="w-100 mb-4 d-flex">
            <a class="navbar-brand mx-auto mt-2 flex-fill text-center" href="">
                <img src="{{ asset('backend/assets/logo/logo.png') }}" alt="..." height="50" />
            </a>
        </div>
        <ul class="navbar-nav flex-fill w-100 mb-2">
            <li class="nav-item w-100 {{ Route::currentRouteNamed('home') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('home') }}">
                    <i class="fe fe-home fe-16"></i>
                    <span class="ml-3 item-text font-weight-bold">Dashboard</span>
                </a>
            </li>
        </ul>

        <p class="text-white font-weight-bold nav-heading mt-2 mb-1">
            <span>Menu</span>
        </p>
        <ul class="navbar-nav flex-fill w-100 mb-2">
            @if (auth()->check() && auth()->user()->role == 'Admin')
                <li class="nav-item w-100 mb-2 {{ Route::currentRouteNamed('pasien.*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('pasien.index') }}">
                        <i class="fe fe-list fe-16"></i>
                        <span class="ml-3 item-text font-weight-bold">Data Pasien</span>
                    </a>
                </li>
                <li class="nav-item w-100 mb-2 {{ Route::currentRouteNamed('antrian.*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('antrian.index') }}">
                        <i class="fe fe-folder-plus fe-16"></i>
                        <span class="ml-3 item-text font-weight-bold">Data Antrian</span>
                    </a>
                </li>
            @endif
            @if (auth()->check() && auth()->user()->role == 'Mantri')                
                <li class="nav-item w-100 mb-2 {{ Route::currentRouteNamed('pemeriksaan') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('pemeriksaan') }}">
                        <i class="fe fe-file-text fe-16"></i>
                        <span class="ml-3 item-text font-weight-bold">Pemeriksaan</span>
                    </a>
                </li>
            @endif
            <li class="nav-item w-100 mb-2 {{ Route::currentRouteNamed('rekamedis') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('rekamedis') }}">
                    <i class="fe fe-inbox fe-16"></i>
                    <span class="ml-3 item-text font-weight-bold">Rekam Medis</span>
                </a>
            </li>
            @if (auth()->check() && auth()->user()->role == 'Mantri')  
                <li class="nav-item w-100 mb-2 {{ Route::currentRouteNamed('obat') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('obat.index') }}">
                        <i class="fe fe-box fe-16"></i>
                        <span class="ml-3 item-text font-weight-bold">Obat</span>
                    </a>
                </li>
            @endif
            @if (auth()->check() && auth()->user()->role == 'Admin')
                <li class="nav-item w-100 mb-2 {{ Route::currentRouteNamed('pembayaran') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('pembayaran') }}">
                        <i class="fe fe-credit-card fe-16"></i>
                        <span class="ml-3 item-text font-weight-bold">Pembayaran</span>
                    </a>
                </li>
                <li class="nav-item w-100 mb-2 {{ Route::currentRouteNamed('datalaporan') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('datalaporan') }}">
                        <i class="fe fe-printer fe-16"></i>
                        <span class="ml-3 item-text font-weight-bold">Data Laporan</span>
                    </a>
                </li>
                <li class="nav-item w-100 mb-2 {{ Route::currentRouteNamed('pengaturan.antrian') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('pengaturan.antrian') }}">
                        <i class="fe fe-settings fe-16"></i>
                        <span class="ml-3 item-text font-weight-bold">Antrian</span>
                    </a>
                </li>
            @endif
        </ul>
    </nav>
</aside>
