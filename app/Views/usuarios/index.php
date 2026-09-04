<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
Administración de Usuarios
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
<i class="bi bi-person-badge text-primary me-2"></i>Gestión de Usuarios
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">
            <i class="bi bi-list-ul me-2"></i>Listado de Usuarios
        </h3>
        <button class="btn btn-primary btn-sm ms-auto" onclick="openModal()">
            <i class="bi bi-person-plus me-1"></i> Nuevo Usuario
        </button>
    </div>
    <div class="card-body p-3">
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0" id="usuariosTable" style="width: 100%;">
                <thead class="table-light">
                    <tr>
                        <th width="5%">ID</th>
                        <th>Nombre</th>
                        <th>Correo Electrónico</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th width="12%" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($usuarios)): ?>
                        <?php foreach($usuarios as $usr): ?>
                            <tr>
                                <td><?= $usr['id_usuario'] ?></td>
                                <td><?= esc($usr['nombre']) ?></td>
                                <td><?= esc($usr['correo']) ?></td>
                                <td>
                                    <?php if($usr['rol'] == 'administrador'): ?>
                                        <span class="badge bg-danger"><i class="bi bi-shield-lock me-1"></i>Admin</span>
                                    <?php else: ?>
                                        <span class="badge bg-info text-dark"><i class="bi bi-person-workspace me-1"></i>Encargado</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($usr['estado']): ?>
                                        <span class="badge bg-success">Activo</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Inactivo</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-warning" 
                                            onclick="openModal(<?= $usr['id_usuario'] ?>, '<?= esc($usr['nombre']) ?>', '<?= esc($usr['correo']) ?>', '<?= esc($usr['rol']) ?>', <?= $usr['estado'] ?>)" title="Editar">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" onclick="confirmDelete('<?= base_url('usuarios/delete/'.$usr['id_usuario']) ?>')" title="Eliminar">
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
<div class="modal fade" id="usuarioModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= base_url('usuarios/save') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title" id="usuarioModalLabel">Nuevo Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_usuario" id="id_usuario" value="">
                    
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre Completo <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required maxlength="100" placeholder="Ej. Juan Pérez">
                    </div>
                    
                    <div class="mb-3">
                        <label for="correo" class="form-label">Correo Electrónico <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="correo" name="correo" required maxlength="100" placeholder="usuario@correo.com">
                    </div>

                    <div class="mb-3">
                        <label for="clave" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="clave" name="clave" placeholder="••••••••">
                        <div class="form-text" id="claveHelp">Para usuarios nuevos es obligatoria. Al editar, déjala en blanco para mantener la actual.</div>
                    </div>

                    <div class="mb-3">
                        <label for="rol" class="form-label">Rol del Sistema <span class="text-danger">*</span></label>
                        <select class="form-select" id="rol" name="rol" required>
                            <option value="">Seleccione un rol...</option>
                            <option value="administrador">Administrador</option>
                            <option value="encargado">Encargado</option>
                        </select>
                    </div>

                    <div class="form-check form-switch mt-3">
                        <input class="form-check-input" type="checkbox" role="switch" id="estado" name="estado" checked>
                        <label class="form-check-label" for="estado">Usuario Activo</label>
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

<?= $this->section('scripts') ?>
<script>
document.addEventListener("DOMContentLoaded", function() {
    $('#usuariosTable').DataTable({
        language: { url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json' },
        responsive: true
    });

    const Toast = Swal.mixin({
        toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true
    });

    <?php if (session()->getFlashdata('success')): ?>
        Toast.fire({ icon: 'success', title: '<?= esc(session()->getFlashdata('success')) ?>' });
    <?php endif; ?>

    <?php if (session()->getFlashdata('errors')): ?>
        let errorMsgs = "<ul style='text-align: left;'>";
        <?php foreach (session()->getFlashdata('errors') as $error): ?>
            errorMsgs += "<li><?= esc($error) ?></li>";
        <?php endforeach; ?>
        errorMsgs += "</ul>";

        Swal.fire({ icon: 'error', title: 'Error al guardar', html: errorMsgs, confirmButtonColor: '#0d6efd' });
    <?php endif; ?>
});

function openModal(id = '', nombre = '', correo = '', rol = '', estado = 1) {
    var myModal = new bootstrap.Modal(document.getElementById('usuarioModal'));
    
    document.getElementById('id_usuario').value = id;
    document.getElementById('nombre').value = nombre;
    document.getElementById('correo').value = correo;
    document.getElementById('rol').value = rol;
    document.getElementById('clave').value = ''; // Siempre limpiar la clave por seguridad
    
    // Checkbox de estado
    document.getElementById('estado').checked = estado == 1;

    // Ajustar si la clave es requerida
    if (id) {
        document.getElementById('usuarioModalLabel').innerText = 'Editar Usuario';
        document.getElementById('clave').removeAttribute('required');
    } else {
        document.getElementById('usuarioModalLabel').innerText = 'Nuevo Usuario';
        document.getElementById('clave').setAttribute('required', 'required');
    }
    
    myModal.show();
}

function confirmDelete(url) {
    Swal.fire({
        title: '¿Eliminar usuario?',
        text: "¡No podrás revertir esto!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) { window.location.href = url; }
    });
}
</script>
<?= $this->endSection() ?>