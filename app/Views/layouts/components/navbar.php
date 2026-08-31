<nav class="app-header navbar navbar-expand bg-body">
    <div class="container-fluid">
        <!-- Start Navbar Links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                    <i class="bi bi-list"></i>
                </a>
            </li>
            <li class="nav-item d-none d-md-block">
                <a href="<?= base_url('facturacion') ?>" class="nav-link">Inicio</a>
            </li>
        </ul>

        <!-- Right Navbar Links -->
        <ul class="navbar-nav ms-auto">
            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle"></i>
                    <!-- Muestra el nombre real almacenado en la sesión -->
                    <span class="d-none d-md-inline ms-1"><?= session()->get('name') ?? 'Usuario' ?></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                    <li class="user-footer text-center">
                        <!-- Enlace dinámico para cerrar sesión -->
                        <a href="<?= base_url('logout') ?>" class="btn btn-default btn-flat text-danger">
                            <i class="bi bi-box-arrow-right me-2"></i> Cerrar Sesión
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>