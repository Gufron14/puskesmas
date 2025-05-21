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

        <!-- Dashboard -->
        <ul class="navbar-nav flex-fill w-100 mb-2">
            <li class="nav-item w-100 mb-2 {{ Route::currentRouteNamed('home') ? 'active' : '' }}">
                <a class="nav-link d-flex align-items-center" href="{{ route('home') }}">
                    <div>
                        <i class="fe fe-home fe-16"></i>
                    </div>
                    <div>
                        <span class="ml-3 item-text font-weight-bold">Dashboard</span>
                    </div>
                </a>
            </li>
            <li class="nav-item w-100 mb-2 ">
                <a class="nav-link d-flex align-items-center" href="/pendaftaran">
                    <div>
                        <i class="fe fe-list fe-16"></i>
                    </div>
                    <div>
                        <span class="ml-3 item-text font-weight-bold">Pendaftaran</span>
                    </div>
                </a>
            </li>
            <li class="nav-item w-100 mb-2 ">
                <a class="nav-link d-flex align-items-center" href="/pemeriksaan">
                    <div>
                        <i class="fe fe-file-text fe-16"></i>
                    </div>
                    <div>
                        <span class="ml-3 item-text font-weight-bold">Pemeriksaan</span>
                    </div>
                </a>
            </li>
            <li class="nav-item w-100 mb-2 ">
                <a class="nav-link d-flex align-items-center" href="/pembayaran">
                    <div>
                        <i class="fe fe-credit-card fe-16"></i>
                    </div>
                    <div>
                        <span class="ml-3 item-text font-weight-bold">Pembayaran</span>
                    </div>
                </a>
            </li>
            <li class="nav-item w-100 mb-2 ">
                <a class="nav-link d-flex align-items-center" href="/mantri">
                    <div>
                        <i class="fe fe-layers fe-16"></i>
                    </div>
                    <div>
                        <span class="ml-3 item-text font-weight-bold">Data Mantri</span>
                    </div>
                </a>
            </li>
            <li class="nav-item w-100 mb-2 ">
                <a class="nav-link d-flex align-items-center" href="/pasien">
                    <div>
                        <i class="fe fe-folder-plus fe-16"></i>
                    </div>
                    <div>
                        <span class="ml-3 item-text font-weight-bold">Data Pasien</span>
                    </div>
                </a>
            </li>
            <li class="nav-item w-100 mb-2 ">
                <a class="nav-link d-flex align-items-center" href="/rekamedis">
                    <div>
                        <i class="fe fe-inbox fe-16"></i>
                    </div>
                    <div>
                        <span class="ml-3 item-text font-weight-bold">Rekam Medis</span>
                    </div>
                </a>
            </li>
            <li class="nav-item w-100 mb-2 ">
                <a class="nav-link d-flex align-items-center" href="/data-laporan">
                    <div>
                        <i class="fe fe-printer fe-16"></i>
                    </div>
                    <div>
                        <span class="ml-3 item-text font-weight-bold">Data Laporan</span>
                    </div>
                </a>
            </li>
        </ul>
    </nav>
</aside>
