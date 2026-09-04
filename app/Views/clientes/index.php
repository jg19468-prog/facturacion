<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
Administración de Clientes
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
<i class="bi bi-people text-primary me-2"></i>Gestión de Clientes
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">
            <i class="bi bi-list-ul me-2"></i>Listado de Clientes
        </h3>
        <button class="btn btn-primary btn-sm ms-auto" onclick="openModal()">
            <i class="bi bi-person-plus me-1"></i> Nuevo Cliente
        </button>
    </div>
    <div class="card-body p-3">
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0" id="clientesTable" style="width: 100%;">
                <thead class="table-light">
                    <tr>
                        <th>Cédula</th>
                        <th>Nombres y Apellidos</th>
                        <th>Teléfono</th>
                        <th>Dirección</th>
                        <th width="12%" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($clientes)): ?>
                        <?php foreach($clientes as $cli): ?>
                            <tr>
                                <td><span class="badge bg-secondary"><?= esc($cli['cedula']) ?></span></td>
                                <td><?= esc($cli['nombres'] . ' ' . $cli['apellidos']) ?></td>
                                <td><?= esc($cli['telefono']) ?></td>
                                <td><?= esc($cli['direccion']) ?></td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-warning" 
                                            onclick="openModal(<?= $cli['id_cliente'] ?>, '<?= esc($cli['cedula']) ?>', '<?= esc($cli['nombres']) ?>', '<?= esc($cli['apellidos']) ?>', '<?= esc($cli['telefono']) ?>', '<?= esc($cli['direccion']) ?>')" title="Editar">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" onclick="confirmDelete('<?= base_url('clientes/delete/'.$cli['id_cliente']) ?>')" title="Eliminar">
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
<div class="modal fade" id="clienteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="<?= base_url('clientes/save') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title" id="clienteModalLabel">Nuevo Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_cliente" id="id_cliente" value="">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="cedula" class="form-label">Cédula <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="cedula" name="cedula" required pattern="[0-9]{10}" maxlength="10" placeholder="10 dígitos">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" id="telefono" name="telefono" maxlength="15" placeholder="Ej. 09...">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nombres" class="form-label">Nombres <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nombres" name="nombres" required maxlength="100">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="apellidos" class="form-label">Apellidos <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="apellidos" name="apellidos" required maxlength="100">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="direccion" class="form-label">Dirección</label>
                        <input type="text" class="form-control" id="direccion" name="direccion" maxlength="200">
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
    $('#clientesTable').DataTable({
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

        Swal.fire({ icon: 'error', title: 'Error de validación', html: errorMsgs, confirmButtonColor: '#0d6efd' });
    <?php endif; ?>
});

function openModal(id = '', cedula = '', nombres = '', apellidos = '', telefono = '', direccion = '') {
    var myModal = new bootstrap.Modal(document.getElementById('clienteModal'));
    
    document.getElementById('id_cliente').value = id;
    document.getElementById('cedula').value = cedula;
    document.getElementById('nombres').value = nombres;
    document.getElementById('apellidos').value = apellidos;
    document.getElementById('telefono').value = telefono;
    document.getElementById('direccion').value = direccion;
    
    document.getElementById('clienteModalLabel').innerText = id ? 'Editar Cliente' : 'Nuevo Cliente';
    myModal.show();
}

function confirmDelete(url) {
    Swal.fire({
        title: '¿Eliminar cliente?',
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