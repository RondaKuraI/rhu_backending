<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->match(['post', 'get'], '/api/register', 'UserController::register');
$routes->match(['post', 'get'], '/api/login', 'UserController::login');
$routes->get('/getData', 'UserController::getData');
$routes->get('/getAppointment_Data', 'UserController::getAppointment_Data');
$routes->get('/getData2', 'UserController::getData2');
$routes->get('/getpatrecData', 'UserController::getpatrecData');

$routes->post('/save', 'UserController::save');