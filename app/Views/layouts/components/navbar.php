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
                <a href="<?= base_url('facturacion') ?>" class="nav-link">
                    <i class="bi bi-house-door me-1"></i>Inicio
                </a>
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
                        <!-- Abre el modal de confirmación en vez de cerrar sesión directamente -->
                        <a href="#" class="btn btn-default btn-flat text-danger" data-bs-toggle="modal" data-bs-target="#logoutModal">
                            <i class="bi bi-box-arrow-right me-2"></i> Cerrar Sesión
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>

<!-- Modal de confirmación para cerrar sesión -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center p-4">
                <i class="bi bi-box-arrow-right text-danger" style="font-size: 2.5rem;"></i>
                <h5 class="modal-title mt-3 mb-2" id="logoutModalLabel">¿Cerrar sesión?</h5>
                <p class="text-muted mb-0">¿Estás seguro de que deseas salir del sistema?</p>
            </div>
            <div class="modal-footer justify-content-center border-top-0 pb-4">
                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Cancelar</button>
                <a href="<?= base_url('logout') ?>" class="btn btn-danger px-4">
                    <i class="bi bi-box-arrow-right me-1"></i> Cerrar Sesión
                </a>
            </div>
        </div>
    </div>
</div>