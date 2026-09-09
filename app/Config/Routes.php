<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// 1. Rutas Públicas (Login)
$routes->get('login', 'AuthController::index');
$routes->post('login/authenticate', 'AuthController::authenticate');
$routes->get('logout', 'AuthController::logout');

// ========================================================================
// 2. Rutas Generales (Administrador y Encargado)
// ========================================================================
$routes->group('', ['filter' => 'auth:administrador,encargado'], function($routes) {
    $routes->get('/', 'Home::index');
    $routes->get('facturacion', 'Home::index');
    $routes->get('dashboard', 'Home::index');

    // Vistas principales (HTML)
    $routes->get('categorias', 'CategoriaController::index');
    $routes->get('marcas', 'MarcaController::index');
    $routes->get('clientes', 'ClienteController::index');
    $routes->get('proveedores', 'ProveedorController::index');

    // === NUEVAS RUTAS DE INVENTARIO Y COMPRAS ===
    $routes->get('inventario', 'InventarioController::index');
    
    $routes->get('compras', 'CompraController::index');
    $routes->get('compras/nueva', 'CompraController::nueva');
    $routes->post('compras/save', 'CompraController::save');
    $routes->get('compras/delete/(:num)', 'CompraController::delete/$1');
    // ============================================

    // Módulo de Facturación
    $routes->get('facturas', 'FacturaController::index');
    $routes->get('facturas/nueva', 'FacturaController::nueva');
    $routes->get('facturas/ver/(:num)', 'FacturaController::ver/$1');
    $routes->get('facturas/imprimir/(:num)', 'FacturaController::imprimir/$1'); 
});

// ========================================================================
// 3. Rutas Exclusivas (SOLO ADMINISTRADOR)
// ========================================================================
$routes->group('', ['filter' => 'auth:administrador'], function($routes) {
    $routes->get('usuarios', 'UsuarioController::index');
    $routes->post('usuarios/save', 'UsuarioController::save');
    $routes->get('usuarios/delete/(:num)', 'UsuarioController::delete/$1');
});

// ========================================================================
// 4. Rutas de Procesamiento (Formularios y Eliminación)
// ========================================================================

// Inventario
$routes->post('inventario/save', 'InventarioController::save', ['filter' => 'auth:administrador,encargado']);
$routes->get('inventario/delete/(:num)', 'InventarioController::delete/$1', ['filter' => 'auth:administrador,encargado']);

// Categorías
$routes->post('categorias/save', 'CategoriaController::save', ['filter' => 'auth:administrador,encargado']);
$routes->get('categorias/delete/(:num)', 'CategoriaController::delete/$1', ['filter' => 'auth:administrador,encargado']);

// Marcas
$routes->post('marcas/save', 'MarcaController::save', ['filter' => 'auth:administrador,encargado']);
$routes->get('marcas/delete/(:num)', 'MarcaController::delete/$1', ['filter' => 'auth:administrador,encargado']);

// Clientes
$routes->post('clientes/save', 'ClienteController::save', ['filter' => 'auth:administrador,encargado']);
$routes->get('clientes/delete/(:num)', 'ClienteController::delete/$1', ['filter' => 'auth:administrador,encargado']);

// Proveedores
$routes->post('proveedores/save', 'ProveedorController::save', ['filter' => 'auth:administrador,encargado']);
$routes->get('proveedores/delete/(:num)', 'ProveedorController::delete/$1', ['filter' => 'auth:administrador,encargado']);

// Facturas
$routes->group('facturas', ['filter' => 'auth:administrador,encargado'], function($routes) {
    $routes->post('save', 'FacturaController::save');
    $routes->get('pagar/(:num)', 'FacturaController::pagar/$1');
    $routes->get('anular/(:num)', 'FacturaController::anular/$1');
    $routes->get('delete/(:num)', 'FacturaController::delete/$1');
});