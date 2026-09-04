<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// 1. Rutas Públicas (Login)
$routes->get('login', 'AuthController::index');
$routes->post('login/authenticate', 'AuthController::authenticate');
$routes->get('logout', 'AuthController::logout');

// 2. Rutas Protegidas que devuelven Vistas HTML (Solo requieren Login)
$routes->group('', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Home::index');
    $routes->get('facturacion', 'Home::index');
    
    // Vista principal de categorías (HTML)
    $routes->get('categorias', 'CategoriaController::index');
    
    // Vista principal de marcas (HTML)
    $routes->get('marcas', 'MarcaController::index');
    
    // Vista principal de clientes (HTML)
    $routes->get('clientes', 'ClienteController::index');

    // Vista principal de proveedores (HTML) -> (CORREGIDO: Va en este grupo)
    $routes->get('proveedores', 'ProveedorController::index');
});

// 3. Rutas de Procesamiento (Formularios y Eliminación)
// AQUÍ ESTÁ EL CAMBIO: Quitamos 'ajax', dejamos solo 'auth'

// Procesamiento para Categorías
$routes->group('categorias', ['filter' => 'auth'], function($routes) {
    
    // Tus rutas originales adaptadas al grupo:
    $routes->post('save', 'CategoriaController::save');
    $routes->get('delete/(:num)', 'CategoriaController::delete/$1');
    
});

// Procesamiento para Marcas
$routes->group('marcas', ['filter' => 'auth'], function($routes) {
    
    // Rutas para guardar (crear/editar) y eliminar marcas:
    $routes->post('save', 'MarcaController::save');
    $routes->get('delete/(:num)', 'MarcaController::delete/$1');
    
});

// Procesamiento para Clientes
$routes->group('clientes', ['filter' => 'auth'], function($routes) {
    
    // Rutas para guardar (crear/editar) y eliminar clientes:
    $routes->post('save', 'ClienteController::save');
    $routes->get('delete/(:num)', 'ClienteController::delete/$1');
    
});

// Procesamiento para Proveedores -> (CORREGIDO: Va totalmente afuera)
$routes->group('proveedores', ['filter' => 'auth'], function($routes) {
    
    // Rutas para guardar (crear/editar) y eliminar proveedores:
    $routes->post('save', 'ProveedorController::save');
    $routes->get('delete/(:num)', 'ProveedorController::delete/$1');
    
});