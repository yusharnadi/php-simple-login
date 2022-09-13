<?php

require_once __DIR__ . '/../vendor/autoload.php';

use ProgrammerZamanNow\Belajar\PHP\MVC\App\Router;
use ProgrammerZamanNow\Belajar\PHP\MVC\Config\Database;
use ProgrammerZamanNow\Belajar\PHP\MVC\Controller\HomeController;
use ProgrammerZamanNow\Belajar\PHP\MVC\Controller\UserController;

Database::getConnection('prod');

Router::add('GET', '/products/([0-9a-zA-Z]*)/categories/([0-9a-zA-Z]*)', ProductController::class, 'categories');

Router::add('GET', '/', HomeController::class, 'index');

Router::add('GET', '/users/register', UserController::class, 'register');
Router::add('POST', '/users/register', UserController::class, 'postRegister');

Router::add('GET', '/users/login', UserController::class, 'login');
Router::add('POST', '/users/login', UserController::class, 'postLogin');

Router::run();
