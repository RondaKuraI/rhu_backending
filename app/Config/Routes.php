<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->match(['post', 'get'], '/api/login', 'UserController::login');
$routes->get('/getData', 'UserController::getData');
$routes->get('/getData2', 'UserController::getData2');
$routes->get('/getpatrecData', 'UserController::getpatrecData');
