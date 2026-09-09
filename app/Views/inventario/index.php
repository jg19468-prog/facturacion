<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
Inventario
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
<i class="bi bi-box-seam text-primary me-2"></i>Gestión de Inventario
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card shadow-sm border-0 rounded-3">
    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
        <h5 class="card-title mb-0 fw-bold" style="color: #1e293b;">Catálogo de Productos</h5>
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalNuevoProducto">
            <i class="bi bi-plus-circle me-1"></i> Nuevo Producto
        </button>
    </div>
    <div class="card-body p-0 table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light text-muted" style="font-size: 0.85rem; text-transform: uppercase;">
                <tr>
                    <th class="ps-4">Código</th>
                    <th>Producto</th>
                    <th>Categoría / Marca</th>
                    <th>Precio Venta</th>
                    <th class="text-center">Stock</th>
                    <th class="text-center pe-4">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($productos)): ?>
                    <?php foreach ($productos as $p): ?>
                        <tr>
                            <td class="ps-4 fw-medium text-secondary"><?= esc($p['codigo_barras'] ?? 'N/A') ?></td>
                            <td class="fw-bold" style="color: #1e293b;"><?= esc($p['nombre']) ?></td>
                            <td>
                                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary-subtle rounded-pill me-1"><?= esc($p['categoria_nombre']) ?></span>
                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary-subtle rounded-pill"><?= esc($p['marca_nombre']) ?></span>
                            </td>
                            <td class="fw-semibold text-success">$<?= number_format((float)$p['precio_venta'], 2) ?></td>
                            <td class="text-center">
                                <?php if ($p['stock'] <= 5): ?>
                                    <span class="badge bg-danger px-2 py-1"><?= esc($p['stock']) ?> unid.</span>
                                <?php else: ?>
                                    <span class="badge bg-success bg-opacity-10 text-success px-2 py-1"><?= esc($p['stock']) ?> unid.</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center pe-4">
                                <!-- Botón Editar llama a la función JS -->
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="editarProducto(<?= $p['id_producto'] ?>, '<?= esc($p['codigo_barras']) ?>', '<?= esc($p['nombre']) ?>', <?= $p['id_categoria'] ?>, <?= $p['id_marca'] ?>, <?= $p['precio_venta'] ?>)">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <!-- Botón Eliminar ejecuta la ruta con confirmación -->
                                <a href="<?= base_url('inventario/delete/' . $p['id_producto']) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Seguro que deseas eliminar este producto?');">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                            No hay productos registrados en el inventario.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ========================================================= -->
<!-- MODAL REGISTRO / EDICIÓN -->
<!-- ========================================================= -->
<div class="modal fade" id="modalNuevoProducto" tabindex="-1" aria-labelledby="modalNuevoProductoLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold" id="modalNuevoProductoLabel" style="color: #1e293b;">Registrar Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- Se agregó un ID al formulario para poder resetearlo con JS -->
            <form id="formProducto" action="<?= base_url('inventario/save') ?>" method="POST">
                <div class="modal-body">
                    <!-- Input oculto para manejar la edición -->
                    <input type="hidden" name="id_producto" id="id_producto" value="">
                    
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Código de Barras</label>
                        <input type="text" class="form-control" name="codigo_barras" id="codigo_barras" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Nombre del Producto</label>
                        <input type="text" class="form-control" name="nombre" id="nombre" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small fw-bold">Categoría</label>
                            <select class="form-select" name="id_categoria" id="id_categoria" required>
                                <option value="">Seleccione...</option>
                                <?php if(!empty($categorias)): ?>
                                    <?php foreach ($categorias as $cat): ?>
                                        <option value="<?= esc($cat['id_categoria']) ?>"><?= esc($cat['nombre']) ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small fw-bold">Marca</label>
                            <select class="form-select" name="id_marca" id="id_marca" required>
                                <option value="">Seleccione...</option>
                                <?php if(!empty($marcas)): ?>
                                    <?php foreach ($marcas as $mar): ?>
                                        <option value="<?= esc($mar['id_marca']) ?>"><?= esc($mar['nombre']) ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Precio de Venta ($)</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" step="0.01" class="form-control" name="precio_venta" id="precio_venta" placeholder="0.00" required>
                        </div>
                    </div>
                    <div class="alert alert-info py-2 mb-0 text-sm">
                        <i class="bi bi-info-circle me-1"></i> El stock no se modifica desde aquí, se altera con Compras y Ventas.
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Guardar Producto
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<!-- ========================================================= -->
<!-- SCRIPT PARA LLENAR EL MODAL AL EDITAR -->
<!-- ========================================================= -->
<?= $this->section('scripts') ?>
<script>
    // Función que recibe los datos de la tabla y los pone en los inputs
    function editarProducto(id, codigo, nombre, categoria, marca, precio) {
        document.getElementById('modalNuevoProductoLabel').innerText = 'Editar Producto';
        
        document.getElementById('id_producto').value = id;
        document.getElementById('codigo_barras').value = codigo;
        document.getElementById('nombre').value = nombre;
        document.getElementById('id_categoria').value = categoria;
        document.getElementById('id_marca').value = marca;
        document.getElementById('precio_venta').value = precio;
        
        // Desplegar el modal usando la API de Bootstrap
        var modal = new bootstrap.Modal(document.getElementById('modalNuevoProducto'));
        modal.show();
    }

    // Evento para limpiar el modal cuando se cierra (así sirve para crear uno nuevo limpio)
    document.getElementById('modalNuevoProducto').addEventListener('hidden.bs.modal', function () {
        document.getElementById('modalNuevoProductoLabel').innerText = 'Registrar Producto';
        document.getElementById('formProducto').reset();
        document.getElementById('id_producto').value = '';
    });
</script>
<?= $this->endSection() ?>