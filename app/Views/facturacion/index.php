<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
Dashboard
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Estilos personalizados para igualar el diseño minimalista -->
<style>
    .kpi-card { border: none; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.03); }
    .icon-box { width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; border-radius: 10px; font-size: 1.25rem; }
    .icon-box-blue { background-color: #e0f2fe; color: #0284c7; }
    .icon-box-green { background-color: #dcfce7; color: #16a34a; }
    .icon-box-orange { background-color: #ffedd5; color: #ea580c; }
    .icon-box-red { background-color: #fee2e2; color: #dc2626; }
    .text-xs { font-size: 0.75rem; }
    .chart-card { border: none; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.03); }
    .table-sub-header { font-size: 0.7rem; font-weight: bold; color: #9ca3af; text-transform: uppercase; }
</style>

<div class="d-flex justify-content-between align-items-center flex-wrap mb-4 mt-2">
    <div>
        <h3 class="mb-1 fw-bold" style="color: #1e293b;">Decisiones con datos claros</h3>
        <p class="text-muted mb-0 text-sm">Revisa el pulso del negocio y detecta lo que necesita atención hoy.</p>
    </div>
    <div class="d-flex align-items-center">
        <span class="badge bg-light text-secondary border p-2 text-sm me-3">
            <i class="bi bi-calendar3 me-1"></i> <?= date('d/m/Y') ?>
        </span>
    </div>
</div>

<!-- Tarjetas de estadísticas (Estilo Minimalista) -->
<div class="row mb-3">
    <!-- Ventas de hoy -->
    <div class="col-lg-3 col-md-6 col-12 mb-3">
        <div class="card kpi-card h-100">
            <div class="card-body d-flex align-items-center p-3">
                <div class="icon-box icon-box-blue me-3">
                    <i class="bi bi-receipt-cutoff"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1 text-xs fw-bold">Ventas de hoy</h6>
                    <h4 class="mb-0 fw-bold" style="color: #1e293b;"><?= esc($ventasHoy ?? 0) ?></h4>
                    <small class="text-muted text-xs">transacciones registradas</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Ingresos del mes -->
    <div class="col-lg-3 col-md-6 col-12 mb-3">
        <div class="card kpi-card h-100">
            <div class="card-body d-flex align-items-center p-3">
                <div class="icon-box icon-box-green me-3">
                    <i class="bi bi-cash-coin"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1 text-xs fw-bold">Ingresos del mes</h6>
                    <h4 class="mb-0 fw-bold" style="color: #1e293b;">$<?= number_format((float) ($ingresosMes ?? 0), 2) ?></h4>
                    <small class="text-muted text-xs">acumulado mensual</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Clientes registrados -->
    <div class="col-lg-3 col-md-6 col-12 mb-3">
        <div class="card kpi-card h-100">
            <div class="card-body d-flex align-items-center p-3">
                <div class="icon-box icon-box-orange me-3">
                    <i class="bi bi-people"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1 text-xs fw-bold">Clientes registrados</h6>
                    <h4 class="mb-0 fw-bold" style="color: #1e293b;"><?= esc($clientesRegistrados ?? 0) ?></h4>
                    <small class="text-muted text-xs">base de clientes</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Stock por revisar -->
    <div class="col-lg-3 col-md-6 col-12 mb-3">
        <div class="card kpi-card h-100">
            <div class="card-body d-flex align-items-center p-3">
                <div class="icon-box icon-box-red me-3">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1 text-xs fw-bold">Stock por revisar</h6>
                    <h4 class="mb-0 fw-bold" style="color: #1e293b;"><?= esc($stockRevisar ?? 0) ?></h4>
                    <small class="text-muted text-xs">productos con 5 o menos unidades</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Sección de Gráficos -->
<div class="row mb-3">
    <!-- Gráfico de Tendencia (Línea curva) -->
    <div class="col-lg-8 mb-3">
        <div class="card chart-card h-100">
            <div class="card-header bg-white border-0 pt-4 pb-0">
                <h6 class="fw-bold mb-0" style="color: #1e293b;">Actividad de los últimos meses</h6>
                <small class="text-muted text-xs">Ventas e ingresos históricos</small>
            </div>
            <div class="card-body">
                <canvas id="tendenciaChart" style="min-height: 250px; max-height: 250px;"></canvas>
            </div>
        </div>
    </div>

    <!-- Gráfico de Ingresos (Barras) -->
    <div class="col-lg-4 mb-3">
        <div class="card chart-card h-100">
            <div class="card-header bg-white border-0 pt-4 pb-0">
                <h6 class="fw-bold mb-0" style="color: #1e293b;">Ingresos mensuales</h6>
                <small class="text-muted text-xs">Últimos meses con actividad</small>
            </div>
            <div class="card-body">
                <canvas id="ingresosChart" style="min-height: 250px; max-height: 250px;"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Sección Inferior: Productos y Alertas (Maquetación inicial) -->
<div class="row">
    <div class="col-lg-7 mb-3">
        <div class="card chart-card h-100">
            <div class="card-header bg-white border-0 pt-4 pb-3">
                <h6 class="fw-bold mb-0" style="color: #1e293b;">Productos más vendidos</h6>
                <small class="text-muted text-xs">Unidades colocadas históricamente</small>
            </div>
            <div class="card-body p-0 table-responsive">
                <table class="table table-borderless table-hover align-middle mb-0">
                    <thead class="border-bottom">
                        <tr>
                            <th class="table-sub-header ps-4">Producto</th>
                            <th class="table-sub-header text-center">Unidades</th>
                            <th class="table-sub-header text-end pe-4">Ingresos</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Ejemplo estático para que veas el diseño -->
                        <tr>
                            <td class="ps-4 fw-bold text-sm" style="color: #1e293b;">LAPTOP HP 230</td>
                            <td class="text-center"><span class="badge bg-info bg-opacity-10 text-info px-2 py-1">2</span></td>
                            <td class="text-end pe-4 fw-bold text-sm">$ 2,400.00</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-5 mb-3">
        <div class="card chart-card h-100">
            <div class="card-header bg-white border-0 pt-4 pb-3">
                <h6 class="fw-bold mb-0" style="color: #1e293b;">Alertas de inventario</h6>
                <small class="text-muted text-xs">Productos que requieren atención</small>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <!-- Ejemplo estático para que veas el diseño -->
                    <div class="list-group-item border-bottom-0 d-flex justify-content-between align-items-center py-3 px-4">
                        <div>
                            <h6 class="mb-0 fw-bold text-sm" style="color: #1e293b;">LAPTOP HP 230</h6>
                            <small class="text-muted text-xs">Precio: $1,200.00</small>
                        </div>
                        <span class="badge bg-warning bg-opacity-10 text-warning px-2 py-1">3 unid.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const dataGrafico = <?= json_encode($graficoIngresos ?? []) ?>;
    const labels = dataGrafico.map(item => item.mes);
    const datosIngresos = dataGrafico.map(item => item.total);
    // Como aún no tenemos los datos de cantidad de ventas por mes, duplicamos ingresos para el ejemplo visual
    const datosVentas = dataGrafico.map(item => item.total / 100); 

    // 1. Gráfico de Línea Curva (Actividad)
    const ctxTendencia = document.getElementById('tendenciaChart');
    new Chart(ctxTendencia, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Ingresos ($)',
                    data: datosIngresos,
                    borderColor: '#f97316', // Naranja
                    backgroundColor: 'rgba(249, 115, 22, 0.1)',
                    borderWidth: 2,
                    tension: 0.4, // Esto hace la línea curva
                    fill: true
                },
                {
                    label: 'Ventas',
                    data: datosVentas,
                    borderColor: '#0284c7', // Azul
                    backgroundColor: 'transparent',
                    borderWidth: 2,
                    tension: 0.4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'top', labels: { usePointStyle: true, boxWidth: 8 } } },
            scales: { y: { beginAtZero: true, border: { dash: [4, 4] }, grid: { color: '#f3f4f6' } }, x: { grid: { display: false } } }
        }
    });

    // 2. Gráfico de Barras (Ingresos)
    const ctxIngresos = document.getElementById('ingresosChart');
    new Chart(ctxIngresos, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Ingresos ($)',
                data: datosIngresos,
                backgroundColor: '#bbf7d0', // Verde menta pastel
                borderRadius: 4,
                maxBarThickness: 40
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, border: { dash: [4, 4] }, grid: { color: '#f3f4f6' } }, x: { grid: { display: false } } }
        }
    });
});
</script>
<?= $this->endSection() ?>