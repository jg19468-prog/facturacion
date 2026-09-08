<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
Nueva Factura
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
<i class="bi bi-file-earmark-plus text-primary me-2"></i>Nueva Factura
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<form action="<?= base_url('facturas/save') ?>" method="POST" id="facturaForm">
    <?= csrf_field() ?>

    <div class="card mb-3">
        <div class="card-header">
            <h3 class="card-title mb-0"><i class="bi bi-person-lines-fill me-2"></i>Datos de la Factura</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="id_cliente" class="form-label">Cliente <span class="text-danger">*</span></label>
                    <select class="form-select" id="id_cliente" name="id_cliente" required>
                        <option value="">-- Seleccione un cliente --</option>
                        <?php foreach ($clientes as $c): ?>
                            <option value="<?= $c['id_cliente'] ?>">
                                <?= esc($c['nombres'] . ' ' . $c['apellidos']) ?> — <?= esc($c['cedula']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (empty($clientes)): ?>
                        <div class="form-text text-danger">
                            No hay clientes registrados. <a href="<?= base_url('clientes') ?>">Registra uno primero.</a>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="fecha" class="form-label">Fecha <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="fecha" name="fecha" value="<?= esc($hoy) ?>" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">IVA aplicado</label>
                    <input type="text" class="form-control" value="<?= (int) $iva ?>%" disabled>
                </div>
            </div>
            <div class="mb-0">
                <label for="observaciones" class="form-label">Observaciones</label>
                <textarea class="form-control" id="observaciones" name="observaciones" rows="2" maxlength="255" placeholder="Opcional"></textarea>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0"><i class="bi bi-cart-plus me-2"></i>Productos / Servicios</h3>
            <button type="button" class="btn btn-sm btn-outline-primary" onclick="agregarLinea()">
                <i class="bi bi-plus-lg me-1"></i> Agregar línea
            </button>
        </div>
        <div class="card-body p-3">
            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0" id="itemsTable">
                    <thead class="table-light">
                        <tr>
                            <th style="min-width: 220px;">Descripción</th>
                            <th style="width: 110px;">Cantidad</th>
                            <th style="width: 150px;">Precio Unitario</th>
                            <th style="width: 130px;" class="text-end">Subtotal</th>
                            <th style="width: 50px;"></th>
                        </tr>
                    </thead>
                    <tbody id="itemsBody">
                        <!-- Las filas se agregan dinámicamente por JS -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <a href="<?= base_url('facturas') ?>" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Cancelar
            </a>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Subtotal</span>
                        <span id="lblSubtotal">$0.00</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">IVA (<?= (int) $iva ?>%)</span>
                        <span id="lblIva">$0.00</span>
                    </div>
                    <hr class="my-2">
                    <div class="d-flex justify-content-between fw-bold fs-5">
                        <span>Total</span>
                        <span id="lblTotal">$0.00</span>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mt-3">
                        <i class="bi bi-save me-1"></i> Guardar Factura
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
const IVA_PORCENTAJE = <?= (float) $iva ?>;
let filaIndex = 0;

function filaHtml(index) {
    return `
        <tr data-row="${index}">
            <td>
                <input type="text" class="form-control" name="descripcion[]" placeholder="Ej. Servicio de mantenimiento" required>
            </td>
            <td>
                <input type="number" class="form-control cantidad" name="cantidad[]" value="1" min="0.01" step="0.01" required>
            </td>
            <td>
                <input type="number" class="form-control precio" name="precio_unitario[]" value="0.00" min="0" step="0.01" required>
            </td>
            <td class="text-end subtotal-linea">$0.00</td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="eliminarLinea(this)">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        </tr>`;
}

function agregarLinea() {
    filaIndex++;
    document.getElementById('itemsBody').insertAdjacentHTML('beforeend', filaHtml(filaIndex));
    recalcularTotales();
}

function eliminarLinea(btn) {
    const filas = document.querySelectorAll('#itemsBody tr');
    if (filas.length <= 1) {
        Swal.fire({ icon: 'info', title: 'Debe existir al menos una línea', timer: 1800, showConfirmButton: false });
        return;
    }
    btn.closest('tr').remove();
    recalcularTotales();
}

function recalcularTotales() {
    let subtotal = 0;

    document.querySelectorAll('#itemsBody tr').forEach(tr => {
        const cantidad = parseFloat(tr.querySelector('.cantidad').value) || 0;
        const precio   = parseFloat(tr.querySelector('.precio').value) || 0;
        const lineaSubtotal = cantidad * precio;

        tr.querySelector('.subtotal-linea').textContent = '$' + lineaSubtotal.toFixed(2);
        subtotal += lineaSubtotal;
    });

    const iva = subtotal * (IVA_PORCENTAJE / 100);
    const total = subtotal + iva;

    document.getElementById('lblSubtotal').textContent = '$' + subtotal.toFixed(2);
    document.getElementById('lblIva').textContent = '$' + iva.toFixed(2);
    document.getElementById('lblTotal').textContent = '$' + total.toFixed(2);
}

document.addEventListener('DOMContentLoaded', function () {
    agregarLinea(); // primera línea por defecto

    document.getElementById('itemsBody').addEventListener('input', function (e) {
        if (e.target.classList.contains('cantidad') || e.target.classList.contains('precio')) {
            recalcularTotales();
        }
    });

    document.getElementById('facturaForm').addEventListener('submit', function (e) {
        const filas = document.querySelectorAll('#itemsBody tr');
        let algunaValida = false;

        filas.forEach(tr => {
            const desc = tr.querySelector('[name="descripcion[]"]').value.trim();
            const cant = parseFloat(tr.querySelector('.cantidad').value) || 0;
            if (desc !== '' && cant > 0) algunaValida = true;
        });

        if (!algunaValida) {
            e.preventDefault();
            Swal.fire({ icon: 'warning', title: 'Agrega al menos un producto o servicio válido' });
        }
    });

    <?php if (session()->getFlashdata('errors')): ?>
        let errorMsgs = "<ul style='text-align: left;'>";
        <?php foreach (session()->getFlashdata('errors') as $error): ?>
            errorMsgs += "<li><?= esc($error) ?></li>";
        <?php endforeach; ?>
        errorMsgs += "</ul>";
        Swal.fire({ icon: 'error', title: 'Revisa el formulario', html: errorMsgs, confirmButtonColor: '#0d6efd' });
    <?php endif; ?>
});
</script>
<?= $this->endSection() ?>
