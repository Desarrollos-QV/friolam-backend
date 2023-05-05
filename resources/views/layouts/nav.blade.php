<!--sidebar wrapper -->
<div class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
        <div>
            <h4 class="logo-text">{{ Auth::guard('admin')->user()->name }}</h4>
        </div>
        <div class="toggle-icon ms-auto"><i class='bx bx-arrow-to-left'></i>
        </div>
    </div>
    <!--navigation-->
    <ul class="metismenu" id="menu">
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-home-circle'></i>
                </div>
                <div class="menu-title">Dashboard</div>
            </a>
            <ul>
                @if($admin->hasPerm('Dashboard - Inicio'))
                <li> 
                    <a href="{{ Asset(env('admin').'/home') }}">
                        <i class="bx bx-right-arrow-alt"></i>
                        Inicio
                        </a>
                </li>
                @endif
                @if($admin->hasPerm('Dashboard - Configuraciones'))
                <li>
                    <a href="{{ Asset(env('admin').'/setting') }}">
                        <i class="bx bx-right-arrow-alt"></i>
                        Configuraciones
                    </a>
                </li>
                @endif
            </ul>
        </li>

        
        @if($admin->hasPerm('workers'))
        <li>
            <a href="{{ Asset(env('admin').'/appUser') }}">
                <div class="parent-icon">
                    <i class='lni lni-users'></i>
                </div>
                <div class="menu-title">Trabajadores</div>
            </a>
        </li>
        @endif 

        

        @if($admin->hasPerm('Subaccount'))
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon">
                    <i class='lni lni-users'></i>
                </div>
                <div class="menu-title">Clientes</div>
            </a>
            <ul>
                <li>
                    <a href="{{ Asset(env('admin').'/user') }}">
                        <i class="bx bx-right-arrow-alt"></i>
                        Listado de Clientes
                    </a>
                </li>
                <li>
                    <a href="{{ Asset(env('admin').'/subusers') }}">
                        <i class="bx bx-right-arrow-alt"></i>
                        Sub Clientes
                    </a>
                </li>
                <li> 
                    <a href="{{ Asset(env('admin').'/branchs') }}">
                        <i class="bx bx-right-arrow-alt"></i>
                        Sucursales
                    </a>
                </li>
            </ul>
        </li>
        @endif

        @if($admin->hasPerm('Servicios'))
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon">
                    <i class='fadeIn animated bx bx-mail-send'></i>
                </div>
                <div class="menu-title">Servicios</div>
            </a>
            <ul>
                <li>
                    <a href="{{ Asset(env('admin').'/Services/add') }}">
                        <i class="bx bx-right-arrow-alt"></i>
                        Agregar Servicio
                    </a>
                </li>  
                <li> 
                    <a href="{{ Asset(env('admin').'/Services?status=0') }}">
                        <i class="bx bx-right-arrow-alt"></i>
                        Listado de Servicios
                        </a>
                </li>
                
                <li> 
                    <a href="{{ Asset(env('admin').'/Services?status=5') }}">
                        <i class="bx bx-right-arrow-alt"></i>
                        Servicio Finalizados
                    </a>
                </li>
                <li> 
                    <a href="{{ Asset(env('admin').'/Services?status=2') }}">
                        <i class="bx bx-right-arrow-alt"></i>
                        Servicio Cancelados
                    </a>
                </li>
                @if($admin->hasPerm('Reportes de ventas'))
                <hr />
                <li>
                    <a href="{{ Asset(env('admin').'/report') }}">
                        <i class='bx bx-file'></i>
                        Reporte de servicios
                    </a>
                </li>
                @endif
            </ul>
        </li>
        @endif 

        @if($admin->hasPerm('vehicles'))
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-taxi'></i>
                </div>
                <div class="menu-title">Vehiculos</div>
            </a>
            <ul>
                <li> 
                    <a href="{{ Asset(env('admin').'/vehicles') }}">
                        <i class="bx bx-right-arrow-alt"></i>
                        Listado
                        </a>
                </li>               
            </ul>
        </li>
        @endif

        @if($admin->hasPerm('Repartidores'))
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-taxi'></i>
                </div>
                <div class="menu-title">Conductores</div>
            </a>
            <ul>
                <li> 
                    <a href="{{ Asset(env('admin').'/delivery') }}">
                        <i class="bx bx-right-arrow-alt"></i>
                        Listado
                        </a>
                </li>
                <li>
                    <a href="{{ Asset(env('admin').'/report_staff') }}">
                        <i class="bx bx-right-arrow-alt"></i>
                        Reportes
                    </a>
                </li>                
            </ul>
        </li>
        @endif 

        <li>
            <a href="{{ Asset(env('admin').'/logout') }}">
                <div class="parent-icon"><i class='fadeIn animated bx bx-log-out-circle'></i>
                </div>
                <div class="menu-title">Cerrar Sesion</div>
            </a>
        </li>
    </ul>
    <!--end navigation-->
</div>
<!--end sidebar wrapper -->