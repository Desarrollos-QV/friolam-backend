<!--start header -->
<header>
    <div class="topbar d-flex align-items-center">
        <nav class="navbar navbar-expand">
            <div class="mobile-toggle-menu"><i class='bx bx-menu'></i></div>
            <div class="search-bar flex-grow-1">
                <div class="position-relative search-bar-box">
                    Panel administrativo
                </div>
            </div>
            
            <div class="user-box dropdown top-menu ms-auto">
                <a class="d-flex align-items-center nav-link dropdown-toggle dropdown-toggle-nocaret" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    @if(Auth::guard('admin')->user()->logo)
                    <img src="{{ asset('upload/admin/'.Auth::guard('admin')->user()->logo) }}" class="user-img" alt="user avatar">
                    @else 
                    <img src="{{ asset('assets/images/avats/avatar-14.png') }}" class="user-img" alt="user avatar">
                    @endif
                    <div class="user-info ps-3">
                        <p class="user-name mb-0">{{ Auth::guard('admin')->user()->name }}</p>
                        <p class="designattion mb-0">
                            @if(Auth::guard('admin')->user()->id == 1)
                                Administrador General
                            @else 
                                SubAdmin
                            @endif
                        </p>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="{{ Asset(env('admin').'/home') }}"><i class="bx bx-tachometer"></i><span>Inicio</span></a>
                    </li>
                    <li><a class="dropdown-item" href="{{ Asset(env('admin').'/setting') }}"><i class="bx bx-cog"></i><span>Configuraciones</span></a>
                    </li>
                    <li><a class="dropdown-item" href="{{ Asset(env('admin').'/adminUser') }}"><i class='bx bx-dollar-circle'></i><span>Sub-Cuentas</span></a>
                    </li>
                    <li><a class="dropdown-item" href="{{ Asset(env('admin').'/appUser') }}"><i class='bx bx-user'></i><span>Usuarios</span></a>
                    </li>
                    <li><a class="dropdown-item" href="{{ Asset(env('admin').'/delivery') }}"><i class='bx bx-car'></i><span>Conductores</span></a>
                    </li>
                    <li>
                        <div class="dropdown-divider mb-0"></div>
                    </li>
                    <li><a class="dropdown-item" href="{{ Asset(env('admin').'/logout') }}"><i class='bx bx-log-out-circle'></i><span>Cerrar Sesión</span></a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</header>
<!--end header -->