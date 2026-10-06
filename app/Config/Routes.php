<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/about', 'About::index');
$routes->get('/services', 'Services::index');
$routes->match(['get', 'post'], '/contact', 'Contact::index');
$routes->get('/register', 'Register::index');
$routes->post('/register', 'Register::create');
$routes->get('/login', 'Login::index');
$routes->post('/login', 'Login::authenticate');
$routes->get('/logout', 'Login::logout');
$routes->get('/dashboard', 'Home::management');
$routes->get('accounts/new', 'Home::newAccount');
$routes->post('accounts', 'Home::createAccount');
$routes->get('account/(:num)/edit', 'Home::editAccount/$1');
$routes->post('account/(:num)', 'Home::updateAccount/$1');
$routes->post('account/(:num)/delete', 'Home::deleteAccount/$1');
$routes->get('account/(:num)', 'Home::viewAccount/$1');
