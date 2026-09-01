<?= $this->extend('layouts/main') ?>

<!-- Título de la pestaña -->
<?= $this->section('title') ?>
Administración de Categorías
<?= $this->endSection() ?>

<!-- Título principal dentro del contenido -->
<?= $this->section('page_title') ?>
<i class="bi bi-tags text-primary me-2"></i>Gestión de Categorías
<?= $this->endSection() ?>

<!-- Contenido Principal -->
<?= $this->section('content') ?>

<!-- Alertas de éxito o error -->
<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0">
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <li><?= $error ?></li>
            <?php endforeach; ?>
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">
            <i class="bi bi-list-ul me-2"></i>Listado de Categorías
        </h3>
        <div class="ms-auto d-flex gap-2">
            <!-- Buscador -->
            <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Buscar categoría..." onkeyup="filterTable()">
            <!-- Botón Añadir -->
            <button class="btn btn-primary btn-sm" onclick="openModal()">
                <i class="bi bi-plus-circle me-1"></i> Nueva Categoría
            </button>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0" id="categoriasTable">
                <thead class="table-light">
                    <tr>
                        <th width="10%">ID</th>
                        <th>Nombre de la Categoría</th>
                        <th width="15%" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($categorias)): ?>
                        <?php foreach($categorias as $cat): ?>
                            <tr>
                                <td><?= $cat['id_categoria'] ?></td>
                                <td><?= esc($cat['nombre']) ?></td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-warning" onclick="openModal(<?= $cat['id_categoria'] ?>, '<?= esc($cat['nombre']) ?>')" title="Editar">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <a href="<?= base_url('categorias/delete/'.$cat['id_categoria']) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Está seguro de eliminar esta categoría?');" title="Eliminar">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="text-center">No hay categorías registradas.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal para Crear/Editar -->
<div class="modal fade" id="categoriaModal" tabindex="-1" aria-labelledby="categoriaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= base_url('categorias/save') ?>" method="POST" id="categoriaForm">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title" id="categoriaModalLabel">Nueva Categoría</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Campo oculto para el ID en caso de edición -->
                    <input type="hidden" name="id_categoria" id="id_categoria" value="">
                    
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required maxlength="50" placeholder="Ej. Lácteos, Herramientas...">
                        <div class="invalid-feedback">
                            Por favor ingrese el nombre de la categoría.
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

<script>
// Función para filtrar la tabla
function filterTable() {
    let input = document.getElementById("searchInput");
    let filter = input.value.toLowerCase();
    let table = document.getElementById("categoriasTable");
    let tr = table.getElementsByTagName("tr");

    for (let i = 1; i < tr.length; i++) {
        let td = tr[i].getElementsByTagName("td")[1]; // Filtra por la columna "Nombre"
        if (td) {
            let txtValue = td.textContent || td.innerText;
            if (txtValue.toLowerCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}

// Función para abrir el modal en modo Crear o Editar
function openModal(id = '', nombre = '') {
    // Referencia al modal (requiere Bootstrap 5 JS)
    var myModal = new bootstrap.Modal(document.getElementById('categoriaModal'));
    
    // Cambiar título y valores
    document.getElementById('id_categoria').value = id;
    document.getElementById('nombre').value = nombre;
    
    if(id) {
        document.getElementById('categoriaModalLabel').innerText = 'Editar Categoría';
    } else {
        document.getElementById('categoriaModalLabel').innerText = 'Nueva Categoría';
    }
    
    myModal.show();
}
</script>
<?= $this->endSection() ?>