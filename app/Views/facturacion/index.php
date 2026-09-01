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