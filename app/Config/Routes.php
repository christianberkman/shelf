<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

/**
 * Books
 */
$routes->get('find/book', 'Books::find');
$routes->get('find/book/ajax', 'Books::findAjax');

$routes->get('book/(:num)', 'Books::view/$1');
$routes->post('book/(:num)', 'Books::update/$1');

/**
 * Authors
 */
$routes->get('find/author', 'Authors::find');
$routes->get('find/author/ajax', 'Authors::ajax');

$routes->get('author/(:num)', 'Authors::view/$1');
$routes->post('author/(:num)', 'Authors::update/$1');
$routes->get('author/(:num)/delete', 'Authors::delete/$1');

/**
 * Sections
 */
$routes->get('sections', 'Sections::index');
$routes->get('section/(:segment)', 'Sections::view/$1');
$routes->post('section/(:segment)', 'Sections::update/$1');
