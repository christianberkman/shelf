<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

/**
 * Books
 */
$routes->get('books/find', 'Books::find');
$routes->get('books/find/json', 'Books::json');
$routes->get('books/browse', 'Books::browse');

$routes->get('books/(:num)', 'Books::view/$1');
$routes->post('books/(:num)', 'Books::update/$1');
$routes->get('books/new', 'Books::new');
$routes->post('books/new', 'Books::insert');

/**
 * Authors
 */
$routes->get('authors/find', 'Authors::find');
$routes->get('authors/find/json', 'Authors::json');
$routes->get('authors/browse', 'Authors::browse');

$routes->get('authors/(:num)', 'Authors::view/$1');
$routes->post('authors/(:num)', 'Authors::update/$1');
$routes->get('authors/(:num)/delete', 'Authors::delete/$1');
$routes->get('authors/new', 'Authors::new');
$routes->post('authors/new', 'Authors::insert');

/**
 * Sections
 */
$routes->get('sections', 'Sections::index');
$routes->get('sections/(:segment)', 'Sections::view/$1');
$routes->post('sections/(:segment)', 'Sections::update/$1');

/**
 * Series
 */
$routes->get('series/find', 'Series::find');
$routes->get('series/find/all', 'Series::browse');
$routes->get('series/find/json', 'Series::ajax');

$routes->get('series/(:num)', 'Series::view/$1');
$routes->post('series/(:num)', 'Series::update/$1');
$routes->get('series/(:num)/delete', 'Series::delete/$1');
$routes->get('series/new', 'Series::new');
$routes->post('series/new', 'Series::insert');
