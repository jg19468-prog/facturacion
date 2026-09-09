<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
Historial de Compras
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
<i class="bi bi-bag-check text-primary me-2"></i>Historial de Compras
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card shadow-sm border-0 rounded-3">
    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
        <h5 class="card-title mb-0 fw-bold" style="color: #1e293b;">Registro de Ingresos</h5>
        <a href="<?= base_url('compras/nueva') ?>" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle me-1"></i> Nueva Compra
        </a>
    </div>
    <div class="card-body p-0 table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light text-muted" style="font-size: 0.85rem; text-transform: uppercase;">
                <tr>
                    <th class="ps-4">ID Compra</th>
                    <th>Fecha</th>
                    <th>Proveedor</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($compras)): ?>
                    <?php foreach ($compras as $c): ?>
                        <tr>
                            <td class="ps-4 fw-medium text-secondary">#<?= esc($c['id_compra']) ?></td>
                            <td><?= esc(date('d/m/Y', strtotime($c['fecha']))) ?></td>
                            <td class="fw-bold" style="color: #1e293b;"><?= esc($c['proveedor_nombre']) ?></td>
                            <td class="fw-semibold text-success">$<?= number_format((float)$c['total'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                            No hay compras registradas.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>