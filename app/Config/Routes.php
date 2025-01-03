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
    $routes->get('(:segment)', 'Books::view/$1');
    
    $routes->get('find', 'Books::find');
    $routes->get('findAjax', 'Books::findAjax');

});

/**
 * Authors
 */
$routes->group('authors', static function ($routes) {
    $routes->get('(:segment)', 'Authors::view/$1');
    $routes->post('(:segment)', 'Authors::update/$1');
    
    $routes->get('find', 'Authors::find');
    $routes->get('ajax', 'Authors::ajax');
});

/**
 * Sections
 */
$routes->group('sections', static function ($routes) {
    $routes->get('/', 'Sections::index');
    $routes->get('(:segment)', 'Sections::view/$1');
    $routes->post('(:segment)', 'Sections::update/$1');
});
