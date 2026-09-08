<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
Historial de Facturas
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
<i class="bi bi-receipt-cutoff text-primary me-2"></i>Historial de Facturas
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">
            <i class="bi bi-list-ul me-2"></i>Facturas Registradas
        </h3>
        <a href="<?= base_url('facturas/nueva') ?>" class="btn btn-primary btn-sm ms-auto">
            <i class="bi bi-plus-circle me-1"></i> Nueva Factura
        </a>
    </div>
    <div class="card-body p-3">
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0" id="facturasTable" style="width: 100%;">
                <thead class="table-light">
                    <tr>
                        <th>N° Factura</th>
                        <th>Cliente</th>
                        <th>Fecha</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th width="16%" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($facturas)): ?>
                        <?php foreach ($facturas as $f): ?>
                            <?php
                                $badge = match ($f['estado']) {
                                    'pagada'  => 'success',
                                    'anulada' => 'danger',
                                    default   => 'warning',
                                };
                            ?>
                            <tr>
                                <td><span class="badge bg-secondary"><?= esc($f['numero_factura'] ?? ('#' . $f['id_factura'])) ?></span></td>
                                <td><?= esc(trim(($f['nombres'] ?? '') . ' ' . ($f['apellidos'] ?? '')) ?: 'Cliente eliminado') ?></td>
                                <td><?= esc(date('d/m/Y', strtotime($f['fecha']))) ?></td>
                                <td>$<?= number_format((float) $f['total'], 2) ?></td>
                                <td><span class="badge bg-<?= $badge ?>"><?= ucfirst($f['estado']) ?></span></td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-primary" onclick="verFactura(<?= $f['id_factura'] ?>)" title="Ver detalle">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <?php if ($f['estado'] === 'pendiente'): ?>
                                        <button class="btn btn-sm btn-outline-success" onclick="confirmAccion('<?= base_url('facturas/pagar/' . $f['id_factura']) ?>', '¿Marcar como pagada?', 'La factura pasará al estado \'pagada\'.')" title="Marcar como pagada">
                                            <i class="bi bi-check-circle"></i>
                                        </button>
                                    <?php endif; ?>
                                    <?php if ($f['estado'] !== 'anulada'): ?>
                                        <button class="btn btn-sm btn-outline-warning" onclick="confirmAccion('<?= base_url('facturas/anular/' . $f['id_factura']) ?>', '¿Anular factura?', 'Esta acción cambiará el estado a \'anulada\'.')" title="Anular">
                                            <i class="bi bi-slash-circle"></i>
                                        </button>
                                    <?php endif; ?>
                                    <button class="btn btn-sm btn-outline-danger" onclick="confirmAccion('<?= base_url('facturas/delete/' . $f['id_factura']) ?>', '¿Eliminar factura?', '¡No podrás revertir esto!')" title="Eliminar">
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

<!-- Modal para ver el detalle de una factura -->
<div class="modal fade" id="verFacturaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-receipt me-2"></i>Detalle de Factura</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="detalleFacturaBody">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status"></div>
                </div>
            </div>
            <div class="modal-footer">
                <!-- Botón de PDF -->
                <a href="#" id="btnImprimirPdf" target="_blank" class="btn btn-danger">
                    <i class="bi bi-file-earmark-pdf"></i> Imprimir en PDF
                </a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener("DOMContentLoaded", function() {
    $('#facturasTable').DataTable({
        language: { url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json' },
        responsive: true,
        order: []
    });

    const Toast = Swal.mixin({
        toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true
    });

    <?php if (session()->getFlashdata('success')): ?>
        Toast.fire({ icon: 'success', title: '<?= esc(session()->getFlashdata('success')) ?>' });
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        Toast.fire({ icon: 'error', title: '<?= esc(session()->getFlashdata('error')) ?>' });
    <?php endif; ?>
});

function confirmAccion(url, titulo, texto) {
    Swal.fire({
        title: titulo,
        text: texto,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#0d6efd',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, continuar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) { window.location.href = url; }
    });
}

function verFactura(id) {
    const modalEl = document.getElementById('verFacturaModal');
    const modal = new bootstrap.Modal(modalEl);
    const body = document.getElementById('detalleFacturaBody');

    body.innerHTML = `<div class="text-center py-5"><div class="spinner-border text-primary" role="status"></div></div>`;
    modal.show();

    fetch(`<?= base_url('facturas/ver') ?>/${id}`)
        .then(res => res.json())
        .then(data => {
            if (data.error) {
                body.innerHTML = `<p class="text-danger text-center">${data.error}</p>`;
                return;
            }

            let filas = '';
            (data.items || []).forEach(item => {
                filas += `
                    <tr>
                        <td>${item.descripcion}</td>
                        <td class="text-center">${item.cantidad}</td>
                        <td class="text-end">$${parseFloat(item.precio_unitario).toFixed(2)}</td>
                        <td class="text-end">$${parseFloat(item.subtotal).toFixed(2)}</td>
                    </tr>`;
            });

            const estadoBadge = data.estado === 'pagada' ? 'success' : (data.estado === 'anulada' ? 'danger' : 'warning');

            body.innerHTML = `
                <div class="d-flex justify-content-between flex-wrap mb-3">
                    <div>
                        <h5 class="mb-0">${data.numero_factura ?? ('#' + data.id_factura)}</h5>
                        <small class="text-muted">${new Date(data.fecha).toLocaleDateString('es-EC')}</small>
                    </div>
                    <span class="badge bg-${estadoBadge} align-self-start">${data.estado.charAt(0).toUpperCase() + data.estado.slice(1)}</span>
                </div>
                <p class="mb-1"><strong>Cliente:</strong> ${(data.nombres ?? '') + ' ' + (data.apellidos ?? '')}</p>
                <p class="mb-3"><strong>Cédula:</strong> ${data.cedula ?? '-'} &nbsp; <strong>Teléfono:</strong> ${data.telefono ?? '-'}</p>

                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Descripción</th>
                                <th class="text-center">Cant.</th>
                                <th class="text-end">P. Unitario</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>${filas}</tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-end">
                    <table class="table table-sm w-auto mb-0">
                        <tr><td class="text-muted">Subtotal:</td><td class="text-end">$${parseFloat(data.subtotal).toFixed(2)}</td></tr>
                        <tr><td class="text-muted">IVA:</td><td class="text-end">$${parseFloat(data.iva).toFixed(2)}</td></tr>
                        <tr><td class="fw-bold">Total:</td><td class="text-end fw-bold">$${parseFloat(data.total).toFixed(2)}</td></tr>
                    </table>
                </div>

                ${data.observaciones ? `<p class="mt-2 mb-0"><strong>Observaciones:</strong> ${data.observaciones}</p>` : ''}
            `;
            
            // Actualizamos la ruta del botón PDF con el ID correcto
            document.getElementById('btnImprimirPdf').setAttribute('href', `<?= base_url('facturas/imprimir') ?>/${id}`);
        })
        .catch(() => {
            body.innerHTML = `<p class="text-danger text-center">Ocurrió un error al cargar la factura.</p>`;
        });
}
</script>
<?= $this->endSection() ?>