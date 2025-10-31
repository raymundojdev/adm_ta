<div class="vertical-menu">
    <div data-simplebar class="h-100">
        <div id="sidebar-menu">
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title">Menu</li>

                <?php
                $role = $_SESSION['perfil']; // rol desde la sesión
                ?>

                <?php if ($role == 'Administrador'): ?>
                <!-- Deja "Inicio" accesible al primer nivel -->
                <li>
                    <a href="inicio" class="waves-effect">
                        <i class="ri-map-pin-line"></i>
                        <span>Inicio</span>
                    </a>
                </li>

                <!-- Usa "Layouts" como contenedor para ANIDAR módulos -->
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="ri-layout-3-line"></i>
                        <span>Layouts</span>
                    </a>

                    <ul class="sub-menu" aria-expanded="false">
                        <!-- Grupo: Ventas -->
                        <li>
                            <a href="javascript: void(0);" class="has-arrow"><i class="ri-shopping-bag-line"></i>
                                Ventas</a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li><a href="pedidos"><i class="ri-shopping-bag-line"></i> Pedidos</a></li>
                                <li><a href="pagos"><i class="ri-money-dollar-box-line"></i> Pagos</a></li>
                            </ul>
                        </li>

                        <!-- Grupo: Caja -->
                        <li>
                            <a href="javascript: void(0);" class="has-arrow"><i class="ri-wallet-3-line"></i> Caja</a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li><a href="cortes_caja"><i class="ri-wallet-3-line"></i> Cortes de caja</a></li>
                                <li><a href="gastos"><i class="ri-file-dollar-line"></i> Gastos</a></li>
                            </ul>
                        </li>

                        <!-- Grupo: Compras -->
                        <li>
                            <a href="javascript: void(0);" class="has-arrow"><i class="ri-shopping-cart-line"></i>
                                Compras</a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li><a href="compras"><i class="ri-shopping-cart-line"></i> Compras</a></li>
                                <li><a href="proveedores"><i class="ri-government-line"></i> Proveedores</a></li>
                            </ul>
                        </li>

                        <!-- Grupo: Catálogo -->
                        <li>
                            <a href="javascript: void(0);" class="has-arrow"><i class="ri-folder-2-line"></i>
                                Catálogo</a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li><a href="productos"><i class="ri-government-line"></i> Productos</a></li>
                                <li><a href="categorias"><i class="ri-folder-2-line"></i> Categorías</a></li>
                                <li><a href="promociones"><i class="ri-price-tag-2-line"></i> Promociones</a></li>
                                <li><a href="clientes"><i class="ri-user-3-line"></i> Clientes</a></li>
                                <li><a href="sucursales"><i class="ri-government-line"></i> Sucursales</a></li>
                            </ul>
                        </li>

                        <!-- Grupo: Metas -->
                        <li>
                            <a href="javascript: void(0);" class="has-arrow"><i class="fas fa-bullseye"></i> Metas</a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li><a href="metas_tacos"><i class="fas fa-bullseye"></i> Metas de Tacos</a></li>
                            </ul>
                        </li>

                        <!-- Grupo: Administración -->
                        <li>
                            <a href="javascript: void(0);" class="has-arrow"><i class="ri-user-line"></i>
                                Administración</a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li><a href="usuarios"><i class="ri-user-line"></i> Usuarios</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <?php endif; ?>

                <!-- Acceso Operativo para Admin / Jefe de cuartel / Jefe de manzana EN “Layouts” también -->
                <?php if ($role == 'Administrador' || $role == 'Jefe de cuartel' || $role == 'Jefe de manzana'): ?>
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="ri-layout-3-line"></i>
                        <span>Layouts</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li>
                            <a href="javascript: void(0);" class="has-arrow"><i class="ri-file-list-line"></i>
                                Operación</a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li><a href="reportes"><i class="ri-file-list-line"></i> Reportes</a></li>
                                <li><a href="captura"><i class="ri-file-list-line"></i> Captura</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <?php endif; ?>

            </ul>
        </div>
    </div>
</div>