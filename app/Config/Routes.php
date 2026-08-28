<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');

$routes->get('/saludo/(:any)/(:any)', 'Home::saludo/$1/$2', ['as' => 'saludo']);

$routes->get('suma/(:num)/(:num)', 'Home::sumita/$1/$2'); 

$routes->get('/facturacion', 'Home::index');