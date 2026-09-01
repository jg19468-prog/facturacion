<?= $this->extend('layouts/main') ?>

<!-- Título de la pestaña -->
<?= $this->section('title') ?>
Listado de Facturas
<?= $this->endSection() ?>

<!-- Título principal dentro del contenido -->
<?= $this->section('page_title') ?>
<i class="bi bi-receipt-cutoff text-primary me-2"></i>Gestión de Facturas
<?= $this->endSection() ?>

<!-- Contenido Principal -->
<?= $this->section('content') ?>

<!-- Tarjetas de resumen del dashboard (datos de ejemplo: conectar con el módulo de facturación) -->
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box text-bg-primary">
            <div class="inner">
                <h3>128</h3>
                <p>Facturas Emitidas</p>
            </div>
            <i class="small-box-icon bi bi-receipt"></i>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box text-bg-success">
            <div class="inner">
                <h3>$24,850</h3>
                <p>Total Facturado</p>
            </div>
            <i class="small-box-icon bi bi-cash-coin"></i>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box text-bg-warning">
            <div class="inner">
                <h3>7</h3>
                <p>Facturas Pendientes</p>
            </div>
            <i class="small-box-icon bi bi-hourglass-split"></i>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box text-bg-info">
            <div class="inner">
                <h3>42</h3>
                <p>Clientes Activos</p>
            </div>
            <i class="small-box-icon bi bi-people-fill"></i>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="bi bi-list-ul me-2"></i>Facturas Registradas
        </h3>
    </div>
    <div class="card-body">
        <p>Aquí irá la tabla o el formulario de tu módulo de facturación.</p>
    </div>
</div>
<?= $this->endSection() ?>
