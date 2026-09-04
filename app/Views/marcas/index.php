<?= $this->extend('layouts/main') ?>

<!-- Título de la pestaña -->
<?= $this->section('title') ?>
Administración de Marcas
<?= $this->endSection() ?>

<!-- Título principal dentro del contenido -->
<?= $this->section('page_title') ?>
<i class="bi bi-award text-primary me-2"></i>Gestión de Marcas
<?= $this->endSection() ?>

<!-- Contenido Principal -->
<?= $this->section('content') ?>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">
            <i class="bi bi-list-ul me-2"></i>Listado de Marcas
        </h3>
        <div class="ms-auto">
            <!-- Botón Añadir -->
            <button class="btn btn-primary btn-sm" onclick="openModal()">
                <i class="bi bi-plus-circle me-1"></i> Nueva Marca
            </button>
        </div>
    </div>
    <div class="card-body p-3">
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0" id="marcasTable" style="width: 100%;">
                <thead class="table-light">
                    <tr>
                        <th width="10%">ID</th>
                        <th>Nombre de la Marca</th>
                        <th width="15%" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($marcas)): ?>
                        <?php foreach($marcas as $marca): ?>
                            <tr>
                                <td><?= $marca['id_marca'] ?></td>
                                <td><?= esc($marca['nombre']) ?></td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-warning" onclick="openModal(<?= $marca['id_marca'] ?>, '<?= esc($marca['nombre']) ?>')" title="Editar">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" onclick="confirmDelete('<?= base_url('marcas/delete/'.$marca['id_marca']) ?>')" title="Eliminar">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal para Crear/Editar -->
<div class="modal fade" id="marcaModal" tabindex="-1" aria-labelledby="marcaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= base_url('marcas/save') ?>" method="POST" id="marcaForm">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title" id="marcaModalLabel">Nueva Marca</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Campo oculto para el ID en caso de edición -->
                    <input type="hidden" name="id_marca" id="id_marca" value="">
                    
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required maxlength="50" placeholder="Ej. Samsung, Sony...">
                        <div class="invalid-feedback">
                            Por favor ingrese el nombre de la marca.
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<!-- Sección de Scripts -->
<?= $this->section('scripts') ?>
<script>
document.addEventListener("DOMContentLoaded", function() {
    
    // 1. Inicialización de DataTables
    $('#marcasTable').DataTable({
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
        },
        responsive: true,
        order: [[0, 'desc']] // Ordenar por ID descendente por defecto
    });

    // 2. Configuración global para Toasts de SweetAlert2
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    // 3. Manejo de Flashdata (Éxito)
    <?php if (session()->getFlashdata('success')): ?>
        Toast.fire({
            icon: 'success',
            title: '<?= esc(session()->getFlashdata('success')) ?>'
        });
    <?php endif; ?>

    // 4. Manejo de Flashdata (Errores)
    <?php if (session()->getFlashdata('errors')): ?>
        let errorMessages = "<ul style='text-align: left;'>";
        <?php foreach (session()->getFlashdata('errors') as $error): ?>
            errorMessages += "<li><?= esc($error) ?></li>";
        <?php endforeach; ?>
        errorMessages += "</ul>";

        Swal.fire({
            icon: 'error',
            title: 'No se pudo guardar',
            html: errorMessages,
            confirmButtonColor: '#0d6efd'
        });
    <?php endif; ?>
});

// Función para abrir el modal en modo Crear o Editar
function openModal(id = '', nombre = '') {
    var myModal = new bootstrap.Modal(document.getElementById('marcaModal'));
    
    document.getElementById('id_marca').value = id;
    document.getElementById('nombre').value = nombre;
    
    if(id) {
        document.getElementById('marcaModalLabel').innerText = 'Editar Marca';
    } else {
        document.getElementById('marcaModalLabel').innerText = 'Nueva Marca';
    }
    
    myModal.show();
}

// Función para confirmar la eliminación de un registro
function confirmDelete(url) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "¡Esta acción no se puede revertir y la marca será eliminada!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url;
        }
    });
}
</script>
<?= $this->endSection() ?>