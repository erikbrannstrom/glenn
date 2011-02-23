<?php
set_include_path('../app/controllers' . PATH_SEPARATOR . '../app/classes' . PATH_SEPARATOR . '../system');

spl_autoload_register(function($class_name) {
    $path = str_replace("\\", "/", $class_name) . '.php';
    $path = str_replace('glenn/', '', $path);
    require_once $path;
});

use glenn\http\Request,
	glenn\controller\FrontController;

$request = new Request();
$frontController = new FrontController();
$response = $frontController->dispatch($request);
$response->send();