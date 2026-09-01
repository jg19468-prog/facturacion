<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión | Sistema de Facturación</title>

    <link rel="icon" href="<?= base_url('favicon.ico') ?>">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/adminlte/dist/css/adminlte.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/auth.css') ?>">
</head>
<body class="bg-white">

    <div class="row g-0 auth-wrapper">

        <!-- Panel izquierdo: imagen de fondo / branding (oculto en móvil) -->
        <div class="col-lg-6 d-none d-lg-flex flex-column justify-content-center auth-brand-panel">
            <div class="brand-content mx-auto">
                <img src="<?= base_url('assets/img/logo.svg') ?>" alt="Logotipo Sistema de Facturación" class="brand-logo-badge mb-4">

                <h1 class="h2 fw-bold mb-3">Gestiona tu facturación sin complicaciones</h1>
                <p class="mb-4 opacity-75">Controla tus facturas, clientes y reportes desde un solo lugar, de forma simple y segura.</p>

                <div class="auth-feature-item">
                    <i class="bi bi-lightning-charge-fill"></i>
                    <div>
                        <strong>Facturación rápida</strong>
                        <div class="small opacity-75">Genera y organiza tus facturas en segundos.</div>
                    </div>
                </div>
                <div class="auth-feature-item">
                    <i class="bi bi-graph-up-arrow"></i>
                    <div>
                        <strong>Reportes claros</strong>
                        <div class="small opacity-75">Visualiza el estado de tu negocio en tiempo real.</div>
                    </div>
                </div>
                <div class="auth-feature-item">
                    <i class="bi bi-shield-lock-fill"></i>
                    <div>
                        <strong>Acceso seguro</strong>
                        <div class="small opacity-75">Tu información protegida en todo momento.</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel derecho: formulario de acceso -->
        <div class="col-12 col-lg-6 d-flex align-items-center justify-content-center auth-form-panel px-4">
            <div class="auth-form-card">

                <div class="text-center mb-4">
                    <img src="<?= base_url('assets/img/logo.svg') ?>" alt="Logotipo" width="56" height="56" class="mb-3">
                    <h2 class="h4 fw-bold mb-1">
                        <i class="bi bi-receipt text-primary me-2"></i>Facturación App
                    </h2>
                    <p class="text-muted small mb-0">Ingresa tus credenciales para iniciar sesión</p>
                </div>

                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success d-flex align-items-center alert-dismissible fade show mb-3" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        <div><?= session()->getFlashdata('success') ?></div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger d-flex align-items-center alert-dismissible fade show mb-3" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <div><?= session()->getFlashdata('error') ?></div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('login/authenticate') ?>" method="post" autocomplete="off">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label for="username" class="form-label text-secondary small">Usuario</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-secondary"><i class="bi bi-person"></i></span>
                            <input type="text" name="username" id="username" class="form-control" placeholder="admin" required autofocus>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label text-secondary small">Contraseña</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-secondary"><i class="bi bi-lock"></i></span>
                            <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required>
                            <span class="input-group-text bg-light text-secondary password-toggle-btn" id="togglePassword">
                                <i class="bi bi-eye" id="togglePasswordIcon"></i>
                            </span>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary fw-semibold py-2">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Ingresar
                        </button>
                    </div>
                </form>

                <p class="text-center text-muted small mt-4 mb-0">&copy; <?= date('Y') ?> Sistema de Facturación</p>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url('assets/adminlte/dist/js/adminlte.min.js') ?>"></script>
    <script>
        // Mostrar / ocultar contraseña
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const togglePasswordIcon = document.getElementById('togglePasswordIcon');

        if (togglePassword && passwordInput) {
            togglePassword.addEventListener('click', function () {
                const isHidden = passwordInput.type === 'password';
                passwordInput.type = isHidden ? 'text' : 'password';
                togglePasswordIcon.classList.toggle('bi-eye');
                togglePasswordIcon.classList.toggle('bi-eye-slash');
            });
        }
    </script>
</body>
</html>
