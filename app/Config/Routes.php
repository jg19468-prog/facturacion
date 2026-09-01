<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Rutas Públicas (Login)
$routes->get('login', 'AuthController::index');
$routes->post('login/authenticate', 'AuthController::authenticate');
$routes->get('logout', 'AuthController::logout');

// Rutas Protegidas (Requieren autenticación)
$routes->group('', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Home::index');
    $routes->get('facturacion', 'Home::index');
    
    // -- ADMINISTRACIÓN DE CATEGORÍAS --
    $routes->get('categorias', 'CategoriaController::index');
    $routes->post('categorias/save', 'CategoriaController::save');
    $routes->get('categorias/delete/(:num)', 'CategoriaController::delete/$1');
});
