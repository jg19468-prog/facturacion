<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <div class="sidebar-brand">
        <a href="<?= base_url() ?>" class="brand-link">
            <img src="<?= base_url('assets/img/logo.svg') ?>" alt="Logotipo" class="brand-image">
            <span class="brand-text fw-light">Facturación App</span>
        </a>
    </div>

    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <!-- Obtenemos el rol de la sesión actual -->
            <?php $rol = session()->get('rol'); ?>
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
                
                <!-- Dashboard: Visible para todos -->
                <li class="nav-item">
                    <a href="<?= base_url('dashboard') ?>" class="nav-link <?= (url_is('dashboard') || url_is('facturacion') || url_is('/')) ? 'active' : '' ?>">
                        <i class="nav-icon bi bi-speedometer"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- ========================================== -->
                <!-- SECCIÓN EXCLUSIVA PARA ADMINISTRADORES -->
                <!-- ========================================== -->
                <?php if($rol === 'administrador'): ?>
                    
                    <!-- Usuarios -->
                    <li class="nav-item">
                        <a href="<?= base_url('usuarios') ?>" class="nav-link <?= url_is('usuarios*') ? 'active' : '' ?>">
                            <i class="nav-icon bi bi-person-badge"></i>
                            <p>Usuarios</p>
                        </a>
                    </li>

                    <!-- Clientes -->
                    <li class="nav-item">
                        <a href="<?= base_url('clientes') ?>" class="nav-link <?= url_is('clientes*') ? 'active' : '' ?>">
                            <i class="nav-icon bi bi-people"></i>
                            <p>Clientes</p>
                        </a>
                    </li>

                    <!-- Proveedores -->
                    <li class="nav-item">
                        <a href="<?= base_url('proveedores') ?>" class="nav-link <?= url_is('proveedores*') ? 'active' : '' ?>">
                            <i class="nav-icon bi bi-truck"></i>
                            <p>Proveedores</p>
                        </a>
                    </li>

                    <!-- Compras e Inventario -->
                    <?php 
                        // Verificamos si alguna de las rutas internas está activa para mantener el menú abierto
                        $isComprasOpen = url_is('compras*') || url_is('inventario*') || url_is('categorias*') || url_is('marcas*'); 
                    ?>
                    <li class="nav-item <?= $isComprasOpen ? 'menu-open' : '' ?>">
                        <a href="#" class="nav-link <?= $isComprasOpen ? 'active' : '' ?>">
                            <i class="nav-icon bi bi-bag-check"></i>
                            <p>
                                Compras
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <!-- Registrar Compra -->
                            <li class="nav-item">
                                <a href="<?= base_url('compras/nueva') ?>" class="nav-link <?= url_is('compras/nueva') ? 'active' : '' ?>">
                                    <i class="nav-icon bi bi-cart-plus"></i>
                                    <p>Registrar Compra</p>
                                </a>
                            </li>
                            <!-- Inventario -->
                            <li class="nav-item">
                                <a href="<?= base_url('inventario') ?>" class="nav-link <?= url_is('inventario*') ? 'active' : '' ?>">
                                    <i class="nav-icon bi bi-box"></i>
                                    <p>Inventario</p>
                                </a>
                            </li>
                            <!-- Categorías -->
                            <li class="nav-item">
                                <a href="<?= base_url('categorias') ?>" class="nav-link <?= url_is('categorias*') ? 'active' : '' ?>">
                                    <i class="nav-icon bi bi-tags"></i>
                                    <p>Categorías</p>
                                </a>
                            </li>
                            <!-- Marcas -->
                            <li class="nav-item">
                                <a href="<?= base_url('marcas') ?>" class="nav-link <?= url_is('marcas*') ? 'active' : '' ?>">
                                    <i class="nav-icon bi bi-award"></i>
                                    <p>Marcas</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                <?php endif; ?>
                <!-- FIN SECCIÓN EXCLUSIVA ADMINISTRADORES -->


                <!-- ========================================== -->
                <!-- SECCIÓN FACTURACIÓN: Visible para TODOS -->
                <!-- ========================================== -->
                <li class="nav-item <?= url_is('facturas*') ? 'menu-open' : '' ?>">
                    <a href="#" class="nav-link <?= url_is('facturas*') ? 'active' : '' ?>">
                        <i class="nav-icon bi bi-receipt"></i>
                        <p>
                            Facturación
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('facturas/nueva') ?>" class="nav-link <?= url_is('facturas/nueva') ? 'active' : '' ?>">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Nueva Factura</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('facturas') ?>" class="nav-link <?= url_is('facturas') ? 'active' : '' ?>">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Historial</p>
                            </a>
                        </li>
                    </ul>
                </li>

            </ul>
        </nav>
    </div>
</aside>