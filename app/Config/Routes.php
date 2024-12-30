<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

/**
 * Books
 */
$routes->group('books', static function ($routes) {
    $routes->get('find', 'Books::find');
    $routes->get('findAjax', 'Books::findAjax');
});

/**
 * Sections
 */
$routes->group('sections', static function ($routes) {
    $routes->get('/', 'Sections::index');
    $routes->get('(:segment)', 'Sections::view/$1');
});
