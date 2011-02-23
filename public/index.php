<?php
define('DS', DIRECTORY_SEPARATOR);
define('APP_PATH', realpath('../app') . DS);

set_include_path('../app/controllers' . PATH_SEPARATOR . APP_PATH . 'classes' . PATH_SEPARATOR . '../system');

spl_autoload_register(function($class_name) {
    $path = str_replace("\\", "/", $class_name) . '.php';
    $path = str_replace('glenn/', '', $path);
    require_once $path;
});

// Create a custom error handler
set_error_handler(function ($errno, $errstr, $errfile, $errline)
{
    if (!(error_reporting() & $errno)) {
        return;
    }

	// Clean output buffer so we only print our error.
	@ob_end_clean();
	include APP_PATH . 'views/error.phtml';

	// Halt all execution. Should depend on error reporting settings.
	exit(-1);
	// The normal course of action, if we do not exit.
    return true;
});

/*********************
 * Start application *
 *********************/

use glenn\http\Request,
	glenn\controller\FrontController;

$request = new Request();
$frontController = new FrontController();
$response = $frontController->dispatch($request);
$response->send();