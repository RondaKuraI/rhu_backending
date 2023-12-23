<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->match(['post', 'get'], '/api/register', 'UserController::register');
$routes->match(['post', 'get'], '/api/login', 'UserController::login');
$routes->get('/getData', 'UserController::getData');
$routes->get('/getAppointment_Data/(:any)', 'UserController::getAppointment_Data/$1');
$routes->get('/getAllAppointment_Data', 'UserController::getAllAppointment_Data');
$routes->get('/getData2', 'UserController::getData2');
$routes->get('/getpatrecData', 'UserController::getpatrecData');

$routes->post('/save', 'UserController::save');

$routes->match(['get', 'post'], '/api/getMedicines', 'AdminController::getMedicines');
$routes->match(['get', 'post'], '/api/newmedicine', 'AdminController::newmedicine');
$routes->put('/api/updateMedicine/(:num)', 'AdminController::updateMedicine/$1');
$routes->match(['get', 'post'], '/api/updateStocks', 'AdminController::updateStocks');