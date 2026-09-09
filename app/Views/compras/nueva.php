<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
Registrar Compra
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
<i class="bi bi-cart-plus text-primary me-2"></i>Ingreso de Mercadería
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<form action="<?= base_url('compras/save') ?>" method="POST">
    <div class="row">
        <!-- Cabecera de Compra -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm border-0 rounded-3 h-100">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0 fw-bold" style="color: #1e293b;">Datos del Proveedor</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Proveedor</label>
                        <select name="id_proveedor" class="form-select" required>
                            <option value="">Seleccione un proveedor...</option>
                            <?php foreach ($proveedores as $prov): ?>
                                <option value="<?= esc($prov['id_proveedor']) ?>">
                                    <?= esc($prov['identificacion']) ?> - <?= esc($prov['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Fecha de Compra</label>
                        <input type="date" class="form-control" name="fecha" value="<?= date('Y-m-d') ?>" required>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detalle de Productos -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm border-0 rounded-3 h-100">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold" style="color: #1e293b;">Detalle de Productos</h6>
                </div>
                <div class="card-body p-0 table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light text-muted" style="font-size: 0.85rem;">
                            <tr>
                                <th class="ps-3" style="width: 45%;">Producto</th>
                                <th style="width: 20%;">Cantidad</th>
                                <th style="width: 20%;">Costo Unit.</th>
                                <th class="pe-3 text-center" style="width: 15%;">Acción</th>
                            </tr>
                        </thead>
                        <tbody id="detalle_tbody">
                            <!-- Fila base para ingresar un producto -->
                            <tr>
                                <td class="ps-3">
                                    <select class="form-select form-select-sm" name="id_producto[]" required>
                                        <option value="">Elegir producto...</option>
                                        <?php foreach ($productos as $prod): ?>
                                            <option value="<?= esc($prod['id_producto']) ?>"><?= esc($prod['nombre']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td><input type="number" name="cantidad[]" class="form-control form-control-sm" placeholder="0" min="1" required></td>
                                <td>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">$</span>
                                        <input type="number" step="0.01" name="costo_unitario[]" class="form-control" placeholder="0.00" min="0.01" required>
                                    </div>
                                </td>
                                <td class="pe-3 text-center">
                                    <button type="button" class="btn btn-sm btn-light text-primary btn-agregar-fila" title="Agregar fila">
                                        <i class="bi bi-plus-circle"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot class="bg-light">
                            <tr>
                                <td colspan="2" class="text-end fw-bold text-muted pt-3">TOTAL COMPRA:</td>
                                <td colspan="2" class="ps-0 pt-3">
                                    <h4 class="mb-0 fw-bold text-success" id="total_display">$0.00</h4>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="card-footer bg-white border-top text-end py-3">
                    <a href="<?= base_url('compras') ?>" class="btn btn-light me-2">Cancelar</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Guardar Compra
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
<?= $this->endSection() ?>

<!-- ========================================================= -->
<!-- SCRIPT PARA DINAMISMO (Cálculo y agregar filas)           -->
<!-- ========================================================= -->
<?= $this->section('scripts') ?>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const tbody = document.getElementById('detalle_tbody');
    const totalDisplay = document.getElementById('total_display');

    // Función para recalcular el total general
    function calcularTotal() {
        let total = 0;
        const filas = tbody.querySelectorAll('tr');
        filas.forEach(fila => {
            const cantidad = parseFloat(fila.querySelector('input[name="cantidad[]"]').value) || 0;
            const costo = parseFloat(fila.querySelector('input[name="costo_unitario[]"]').value) || 0;
            total += (cantidad * costo);
        });
        totalDisplay.innerText = '$' + total.toFixed(2);
    }

    // Escuchar cambios en los inputs para actualizar el total en tiempo real
    tbody.addEventListener('input', function(e) {
        if(e.target.name === 'cantidad[]' || e.target.name === 'costo_unitario[]') {
            calcularTotal();
        }
    });

    // Agregar nueva fila al presionar el botón "+" o eliminar con "-"
    tbody.addEventListener('click', function(e) {
        // Detectar si el clic fue en el botón de agregar
        const btnAgregar = e.target.closest('.btn-agregar-fila');
        if (btnAgregar) {
            // Clonar la primera fila
            const nuevaFila = tbody.querySelector('tr').cloneNode(true);
            
            // Limpiar los valores de los inputs de la nueva fila clonada
            nuevaFila.querySelectorAll('input').forEach(input => input.value = '');
            nuevaFila.querySelector('select').selectedIndex = 0;
            
            // Cambiar el botón "+" por una papelera "-" para poder eliminar la fila
            const tdAccion = nuevaFila.querySelector('.text-center');
            tdAccion.innerHTML = `
                <button type="button" class="btn btn-sm btn-outline-danger btn-eliminar-fila" title="Eliminar fila">
                    <i class="bi bi-trash"></i>
                </button>`;
                
            tbody.appendChild(nuevaFila);
        }

        // Detectar si el clic fue en el botón de la papelera para eliminar fila
        const btnEliminar = e.target.closest('.btn-eliminar-fila');
        if (btnEliminar) {
            btnEliminar.closest('tr').remove();
            calcularTotal(); // Recalcular total tras eliminar
        }
    });
});
</script>
<?= $this->endSection() ?>