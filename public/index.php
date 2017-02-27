<?php

/**
 *
 * Public file for the Bingo Framework
 * Adding routes for the various controllers and views happens here 
 * 
 * @package The Bingo Framework
 * @author Lochemem Bruno Michael
 *
 */

require dirname(__DIR__) . '/packages/autoload.php';

set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');

$router = new Core\Router();

$router->addRoute("", ['controller' => 'Home', 'action' => 'index']);

$router->addRoute("posts", ['controller' => 'Posts', 'action' => 'index']);

$router->addRoute('{controller}/{action}');

$router->addRoute('{controller}/{id:\d+}/{action}');

$router->addRoute('{controller}/{id:\d+}/{name:\w+}/{action}');

$router->addRoute('admin/{controller}/{action}', ['namespace' => 'Admin']);

$router->dispatch($_SERVER['QUERY_STRING']);
