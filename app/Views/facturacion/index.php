<?= $this->extend('layouts/main') ?>

<!-- Título de la pestaña -->
<?= $this->section('title') ?>
Dashboard
<?= $this->endSection() ?>

<!-- Título principal dentro del contenido -->
<?= $this->section('page_title') ?>
<i class="bi bi-speedometer2 text-primary me-2"></i>Panel Principal
<?= $this->endSection() ?>

<!-- Contenido Principal -->
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
    <div>
        <h4 class="mb-0">Hola, <?= esc(session()->get('name') ?? 'Usuario') ?> 👋</h4>
        <p class="text-muted mb-0">Este es el resumen de tu actividad de facturación.</p>
    </div>
    <a href="<?= base_url('facturas/nueva') ?>" class="btn btn-primary mt-2 mt-sm-0">
        <i class="bi bi-plus-circle me-1"></i> Nueva Factura
    </a>
</div>

<!-- Tarjetas de estadísticas -->
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box text-bg-primary">
            <div class="inner">
                <h3><?= (int) $facturasDelMes ?></h3>
                <p>Facturas este mes</p>
            </div>
            <i class="bi bi-receipt-cutoff small-box-icon"></i>
            <a href="<?= base_url('facturas') ?>" class="small-box-footer">
                Ver historial <i class="bi bi-arrow-right-circle ms-1"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box text-bg-success">
            <div class="inner">
                <h3>$<?= number_format((float) $ingresosDelMes, 2) ?></h3>
                <p>Ingresos del mes</p>
            </div>
            <i class="bi bi-cash-coin small-box-icon"></i>
            <a href="<?= base_url('facturas') ?>" class="small-box-footer">
                Ver detalle <i class="bi bi-arrow-right-circle ms-1"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box text-bg-warning">
            <div class="inner">
                <h3><?= (int) $pendientesCobro ?></h3>
                <p>Pendientes de cobro</p>
            </div>
            <i class="bi bi-hourglass-split small-box-icon"></i>
            <a href="<?= base_url('facturas') ?>" class="small-box-footer">
                Revisar <i class="bi bi-arrow-right-circle ms-1"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box text-bg-danger">
            <div class="inner">
                <h3><?= (int) $facturasAnuladas ?></h3>
                <p>Facturas anuladas</p>
            </div>
            <i class="bi bi-x-circle small-box-icon"></i>
            <a href="<?= base_url('facturas') ?>" class="small-box-footer">
                Ver historial <i class="bi bi-arrow-right-circle ms-1"></i>
            </a>
        </div>
    </div>
</div>

<div class="row">
    <!-- Gráfico de ingresos -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="bi bi-graph-up-arrow me-2"></i>Ingresos de los últimos 6 meses
                </h3>
            </div>
            <div class="card-body">
                <canvas id="graficoIngresos" style="min-height: 260px;"></canvas>
            </div>
        </div>
    </div>

    <!-- Últimas facturas -->
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0">
                    <i class="bi bi-clock-history me-2"></i>Últimas Facturas
                </h3>
                <a href="<?= base_url('facturas') ?>" class="small">Ver todas</a>
            </div>
            <div class="card-body p-0">
                <?php if (!empty($ultimasFacturas)): ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($ultimasFacturas as $f): ?>
                            <?php
                                $badge = match ($f['estado']) {
                                    'pagada'   => 'success',
                                    'anulada'  => 'danger',
                                    default    => 'warning',
                                };
                            ?>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-semibold"><?= esc($f['numero_factura'] ?? ('#' . $f['id_factura'])) ?></div>
                                    <small class="text-muted"><?= esc(trim(($f['nombres'] ?? '') . ' ' . ($f['apellidos'] ?? '')) ?: 'Cliente eliminado') ?></small>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-<?= $badge ?> mb-1"><?= ucfirst($f['estado']) ?></span>
                                    <div class="small text-muted">$<?= number_format((float) $f['total'], 2) ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center text-muted py-5 px-3">
                        <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                        <p class="mt-2 mb-1">Aún no se han registrado facturas.</p>
                        <a href="<?= base_url('facturas/nueva') ?>" class="btn btn-sm btn-primary mt-2">
                            <i class="bi bi-plus-circle me-1"></i> Crear la primera factura
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const ctx = document.getElementById('graficoIngresos');
    const labels = <?= json_encode(array_column($graficoIngresos, 'mes')) ?>;
    const datos  = <?= json_encode(array_column($graficoIngresos, 'total')) ?>;

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Ingresos ($)',
                data: datos,
                backgroundColor: 'rgba(13, 110, 253, 0.55)',
                borderColor: 'rgba(13, 110, 253, 1)',
                borderWidth: 1,
                borderRadius: 6,
                maxBarThickness: 45
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { callback: (value) => '$' + value }
                }
            }
        }
    });
});
</script>
<?= $this->endSection() ?>
