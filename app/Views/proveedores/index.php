<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
Administración de Proveedores
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
<i class="bi bi-truck text-primary me-2"></i>Gestión de Proveedores
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">
            <i class="bi bi-list-ul me-2"></i>Listado de Proveedores
        </h3>
        <button class="btn btn-primary btn-sm ms-auto" onclick="openModal()">
            <i class="bi bi-plus-circle me-1"></i> Nuevo Proveedor
        </button>
    </div>
    <div class="card-body p-3">
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0" id="proveedoresTable" style="width: 100%;">
                <thead class="table-light">
                    <tr>
                        <th width="5%">ID</th>
                        <th width="20%">RUC / Identificación</th>
                        <th>Nombre / Razón Social</th>
                        <th width="15%">Teléfono</th>
                        <th width="12%" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($proveedores)): ?>
                        <?php foreach($proveedores as $prov): ?>
                            <tr>
                                <td><?= $prov['id_proveedor'] ?></td>
                                <td><span class="badge bg-secondary"><?= esc($prov['identificacion']) ?></span></td>
                                <td><?= esc($prov['nombre']) ?></td>
                                <td><?= esc($prov['telefono']) ?></td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-warning" 
                                            onclick="openModal(<?= $prov['id_proveedor'] ?>, '<?= esc($prov['identificacion']) ?>', '<?= esc($prov['nombre']) ?>', '<?= esc($prov['telefono']) ?>')" title="Editar">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" onclick="confirmDelete('<?= base_url('proveedores/delete/'.$prov['id_proveedor']) ?>')" title="Eliminar">
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
<div class="modal fade" id="proveedorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= base_url('proveedores/save') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title" id="proveedorModalLabel">Nuevo Proveedor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_proveedor" id="id_proveedor" value="">
                    
                    <div class="mb-3">
                        <label for="identificacion" class="form-label">RUC / Identificación <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="identificacion" name="identificacion" required maxlength="20" placeholder="Ej. 1790000000001">
                    </div>
                    
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre / Razón Social <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required maxlength="100" placeholder="Ej. Distribuidora XYZ">
                    </div>

                    <div class="mb-3">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="text" class="form-control" id="telefono" name="telefono" maxlength="20" placeholder="Ej. 09...">
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
    $('#proveedoresTable').DataTable({
        language: { url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json' },
        responsive: true,
        order: [[0, 'desc']]
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

        Swal.fire({ icon: 'error', title: 'No se pudo guardar', html: errorMsgs, confirmButtonColor: '#0d6efd' });
    <?php endif; ?>
});

function openModal(id = '', identificacion = '', nombre = '', telefono = '') {
    var myModal = new bootstrap.Modal(document.getElementById('proveedorModal'));
    
    document.getElementById('id_proveedor').value = id;
    document.getElementById('identificacion').value = identificacion;
    document.getElementById('nombre').value = nombre;
    document.getElementById('telefono').value = telefono;
    
    document.getElementById('proveedorModalLabel').innerText = id ? 'Editar Proveedor' : 'Nuevo Proveedor';
    myModal.show();
}

function confirmDelete(url) {
    Swal.fire({
        title: '¿Eliminar proveedor?',
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