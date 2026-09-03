<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// 1. Rutas Públicas (Login)
$routes->get('login', 'AuthController::index');
$routes->post('login/authenticate', 'AuthController::authenticate');
$routes->get('logout', 'AuthController::logout');

// 2. Rutas Protegidas que devuelven Vistas HTML (Solo requieren Login)
// Aquí quitamos el filtro 'ajax' para que carguen bien en el navegador
$routes->group('', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Home::index');
    $routes->get('facturacion', 'Home::index');
    
    // Vista principal de categorías (HTML)
    $routes->get('categorias', 'CategoriaController::index');
});

// 3. Rutas de la API / Endpoints (Requieren Login Y Petición AJAX)
// Agrupamos bajo el prefijo 'categorias' para mantener tus rutas: 
// categorias/save y categorias/delete/(:num)
$routes->group('categorias', ['filter' => ['auth', 'ajax']], function($routes) {
    
    // Tus rutas originales adaptadas al grupo:
    $routes->post('save', 'CategoriaController::save');
    $routes->get('delete/(:num)', 'CategoriaController::delete/$1');
    
    // (Opcional) Si decides agregar las funciones extras que mencionaste 
    // en el nuevo código (para hacer la tabla dinámica), serían así:
    // $routes->get('getCategorias', 'CategoriaController::getCategorias');
    // $routes->get('obtener/(:num)', 'CategoriaController::obtener/$1');
});