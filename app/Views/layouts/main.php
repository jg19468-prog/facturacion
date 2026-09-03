<!DOCTYPE html>
<html lang="es">

<!-- Cargar Head original -->
<?= $this->include('layouts/components/head') ?>

<!-- Estilos globales para DataTables y SweetAlert2 -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">

        <!-- Cargar Navbar -->
        <?= $this->include('layouts/components/navbar') ?>

        <!-- Cargar Sidebar -->
        <?= $this->include('layouts/components/sidebar') ?>

        <!-- Main Content Wrapper -->
        <main class="app-main">
            <!-- Header de la página (Título y Breadcrumb) -->
            <div class="app-content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <h3 class="mb-0"><?= $this->renderSection('page_title') ?></h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contenido dinámico de cada vista -->
            <div class="app-content">
                <div class="container-fluid">
                    <?= $this->renderSection('content') ?>
                </div>
            </div>
        </main>

        <!-- Cargar Footer -->
        <?= $this->include('layouts/components/footer') ?>

    </div>

    <!-- Cargar Scripts originales de tu plantilla -->
    <?= $this->include('layouts/components/scripts') ?>

    <!-- 
      jQuery (DataTables lo exige). 
      NOTA: Si tu plantilla ya incluye jQuery dentro de 'components/scripts', 
      puedes borrar esta línea de abajo para evitar conflictos. 
    -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- Scripts globales de DataTables y SweetAlert2 -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Sección dinámica para insertar scripts desde las vistas hijas -->
    <?= $this->renderSection('scripts') ?>
</body>
</html>