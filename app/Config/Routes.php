<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// 1. Rutas Públicas (Login)
$routes->get('login', 'AuthController::index');
$routes->post('login/authenticate', 'AuthController::authenticate');
$routes->get('logout', 'AuthController::logout');

// ========================================================================
// 2. Rutas Generales (Acceso para Administrador y Encargado)
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

    // Módulo de Facturación
    $routes->get('facturas', 'FacturaController::index');
    $routes->get('facturas/nueva', 'FacturaController::nueva');
    $routes->get('facturas/ver/(:num)', 'FacturaController::ver/$1');
    $routes->get('facturas/imprimir/(:num)', 'FacturaController::imprimir/$1'); // RUTA PARA EL PDF
});


// ========================================================================
// 3. Rutas Exclusivas (SOLO ADMINISTRADOR)
// ========================================================================
$routes->group('', ['filter' => 'auth:administrador'], function($routes) {
    // Vista de usuarios
    $routes->get('usuarios', 'UsuarioController::index');
});


// ========================================================================
// 4. Rutas de Procesamiento (Formularios y Eliminación)
// ========================================================================

// Procesamiento para Categorías
$routes->group('categorias', ['filter' => 'auth:administrador,encargado'], function($routes) {
    $routes->post('save', 'CategoriaController::save');
    $routes->get('delete/(:num)', 'CategoriaController::delete/$1');
});

// Procesamiento para Marcas
$routes->group('marcas', ['filter' => 'auth:administrador,encargado'], function($routes) {
    $routes->post('save', 'MarcaController::save');
    $routes->get('delete/(:num)', 'MarcaController::delete/$1');
});

// Procesamiento para Clientes
$routes->group('clientes', ['filter' => 'auth:administrador,encargado'], function($routes) {
    $routes->post('save', 'ClienteController::save');
    $routes->get('delete/(:num)', 'ClienteController::delete/$1');
});

// Procesamiento para Proveedores
$routes->group('proveedores', ['filter' => 'auth:administrador,encargado'], function($routes) {
    $routes->post('save', 'ProveedorController::save');
    $routes->get('delete/(:num)', 'ProveedorController::delete/$1');
});

// Procesamiento para Facturas
$routes->group('facturas', ['filter' => 'auth:administrador,encargado'], function($routes) {
    $routes->post('save', 'FacturaController::save');
    $routes->get('pagar/(:num)', 'FacturaController::pagar/$1');
    $routes->get('anular/(:num)', 'FacturaController::anular/$1');
    $routes->get('delete/(:num)', 'FacturaController::delete/$1');
});

// Procesamiento para Usuarios (SOLO ADMINISTRADOR)
$routes->group('usuarios', ['filter' => 'auth:administrador'], function($routes) {
    $routes->post('save', 'UsuarioController::save');
    $routes->get('delete/(:num)', 'UsuarioController::delete/$1');
});