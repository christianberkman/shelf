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

    $routes->get('(:segment)', 'Books::view/$1');
});

/**
 * Authors
 */
$routes->group('authors', static function ($routes) {
    $routes->get('find', 'Authors::find');
    $routes->get('ajax', 'Authors::ajax');

    $routes->get('(:segment)', 'Authors::view/$1');
    $routes->post('(:segment)', 'Authors::update/$1');
    $routes->get('(:segment)/delete', 'Authors::delete/$1');
});

/**
 * Sections
 */
$routes->group('sections', static function ($routes) {
    $routes->get('/', 'Sections::index');
    $routes->get('(:segment)', 'Sections::view/$1');
    $routes->post('(:segment)', 'Sections::update/$1');
});
