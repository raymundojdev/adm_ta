<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title">Menu</li>

                <?php
                $role = $_SESSION['perfil']; // Assuming the user role is stored in the session
                ?>

                <?php if ($role == 'Administrador'): ?>
                <li>
                    <a href="usuarios" class="waves-effect">
                        <i class="ri-user-line"></i>
                        <span>Usuarios</span>
                    </a>
                </li>

                <li>
                    <a href="inicio" class="waves-effect">
                        <i class="ri-map-pin-line"></i>
                        <span>Inicio</span>
                    </a>
                </li>
                <li>
                    <a href="sucursales" class="waves-effect">
                        <i class="ri-government-line"></i>
                        <span>Sucursales</span>
                    </a>
                </li>
                <li>
                    <a href="categorias" class="waves-effect">
                        <i class="ri-folder-2-line"></i>
                        <span>Categorías</span>
                    </a>
                </li>

                <li>
                    <a href="promociones" class="waves-effect">
                        <i class="ri-price-tag-2-line"></i>
                        <span>Promociones</span>
                    </a>
                </li>
                <li>
                    <a href="clientes" class="waves-effect">
                        <i class="ri-user-3-line"></i>
                        <span>Clientes</span>
                    </a>
                </li>

                <li>


                    <a href="proveedores" class="waves-effect">
                        <i class="ri-government-line"></i>
                        <span>Proveedores</span>
                    </a>
                </li>

                <li>
                    <a href="productos" class="waves-effect">
                        <i class="ri-government-line"></i>
                        <span>Productos</span>
                    </a>
                </li>
                <li>
                    <a href="pedidos" class="waves-effect">
                        <i class="ri-shopping-bag-line"></i>
                        <span>Pedidos</span>
                    </a>
                </li>
                <li>
                    <a href="pagos" class="waves-effect">
                        <i class="ri-money-dollar-box-line"></i>
                        <span>Pagos</span>
                    </a>
                </li>
                <li>
                    <a href="cortes_caja" class="waves-effect">
                        <i class="ri-wallet-3-line"></i>
                        <span>Cortes de caja</span>
                    </a>
                </li>
                <li>
                    <a href="gastos" class="waves-effect">
                        <i class="ri-file-dollar-line"></i>
                        <span>Gastos</span>
                    </a>
                </li>
                <li>
                    <a href="compras" class="waves-effect">
                        <i class="ri-shopping-cart-line"></i>
                        <span>Compras</span>
                    </a>
                    <?php endif; ?>

                    <?php if ($role == 'Administrador' || $role == 'Jefe de cuartel' || $role == 'Jefe de manzana'): ?>
                <li>
                    <a href="reportes" class="waves-effect">
                        <i class="ri-file-list-line"></i>
                        <span>Reportes</span>
                    </a>
                </li>
                <li>
                    <a href="captura" class="waves-effect">
                        <i class="ri-file-list-line"></i>
                        <span>Captura</span>
                    </a>
                </li>

                <?php endif; ?>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="ri-layout-3-line"></i>
                        <span>Layouts</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">
                        <li>
                            <a href="javascript: void(0);" class="has-arrow">Vertical</a>
                            <ul class="sub-menu" aria-expanded="true">
                                <li><a href="layouts-light-sidebar.html">Light Sidebar</a></li>
                                <li><a href="layouts-compact-sidebar.html">Compact Sidebar</a></li>
                                <li><a href="layouts-icon-sidebar.html">Icon Sidebar</a></li>
                                <li><a href="layouts-boxed.html">Boxed Layout</a></li>
                                <li><a href="layouts-preloader.html">Preloader</a></li>
                                <li><a href="layouts-colored-sidebar.html">Colored Sidebar</a></li>
                            </ul>
                        </li>

                        <li>
                            <a href="javascript: void(0);" class="has-arrow">Horizontal</a>
                            <ul class="sub-menu" aria-expanded="true">
                                <li><a href="layouts-horizontal.html">Horizontal</a></li>
                                <li><a href="layouts-hori-topbar-light.html">Topbar light</a></li>
                                <li><a href="layouts-hori-boxed-width.html">Boxed width</a></li>
                                <li><a href="layouts-hori-preloader.html">Preloader</a></li>
                                <li><a href="layouts-hori-colored-header.html">Colored Header</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>



            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>