<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <div class="sidebar-brand">
        <a href="<?= base_url() ?>" class="brand-link">
            <img src="<?= base_url('assets/img/logo.svg') ?>" alt="Logotipo" class="brand-image">
            <span class="brand-text fw-light">Facturación App</span>
        </a>
    </div>

    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
                
                <!-- Opción Simple: Dashboard -->
                <li class="nav-item">
                    <a href="<?= base_url('dashboard') ?>" class="nav-link <?= url_is('dashboard') ? 'active' : '' ?>">
                        <i class="nav-icon bi bi-speedometer"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- Opción con Desplegable: Facturación -->
                <!-- url_is('facturas*') detecta 'facturas', 'facturas/nueva', 'facturas/editar/1', etc. -->
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

                <!-- Opción con Desplegable: Inventario -->
                <li class="nav-item <?= url_is('categorias*') ? 'menu-open' : '' ?>">
                    <a href="#" class="nav-link <?= url_is('categorias*') ? 'active' : '' ?>">
                        <i class="nav-icon bi bi-box-seam"></i>
                        <p>
                            Inventario
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('categorias') ?>" class="nav-link <?= url_is('categorias') ? 'active' : '' ?>">
                                <i class="nav-icon bi bi-tags"></i>
                                <p>Categorías</p>
                            </a>
                        </li>
                        <!-- Aquí podrás añadir Marcas y Productos después -->
                    </ul>
                </li>

            </ul>
        </nav>
    </div>
</aside>