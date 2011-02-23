<?php

$start_time = microtime(true);
$start_mem = memory_get_usage();

define('DS', DIRECTORY_SEPARATOR);
define('APP_PATH', realpath('../app') . DS);
define('SYSTEM_PATH', realpath('../system') . DS);

set_include_path(APP_PATH . 'controllers' . PATH_SEPARATOR . APP_PATH . 'classes' . PATH_SEPARATOR . SYSTEM_PATH);

spl_autoload_register(function($class_name) {
	$path = str_replace("\\", "/", $class_name) . '.php';
	$path = str_replace('glenn/', '', $path);
	require_once $path;
});

// Create a custom error handler
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
	if (!(error_reporting() & $errno)) {
		return;
	}
	
	$code = file($errfile);
	
	$start = ($errline <= 3) ? 0 : $errline-3;
	$end = (count($code)-1 <= $errline+3) ? count($code)-1 : $errline+3;
	
	$errcode = '';
	for ($i = $start; $i < $end; $i++) {
		if($i == $errline-1) {
			$errcode .= '--> ';
		} else {
			$errcode .= '    ';
		}
		$errcode .= $code[$i] . PHP_EOL;
	}
	$errcode = htmlentities($errcode);

	// Clean output buffer so we only print our error.
	@ob_end_clean();
	if(file_exists(APP_PATH . 'views/error.phtml')) {
		include APP_PATH . 'views/error.phtml';
	} else {
		include SYSTEM_PATH . 'views/error.phtml';
	}

	// Halt all execution. Should depend on error reporting settings.
	exit(-1);
	// The normal course of action, if we do not exit.
	return true;
});

/* * *******************
 * Start application *
 * ******************* */

use glenn\http\Request,
	glenn\dispatch\FrontController;

$request = new Request();
$frontController = new FrontController();
$response = $frontController->dispatch($request);
$response->send();

// Echo resource use
echo '<pre>';
echo 'RESOURCES:' . PHP_EOL;
echo (microtime(true) - $start_time) * 1000 . ' ms' . PHP_EOL;
$bytes = memory_get_usage() - $start_mem;
echo ($bytes / 1000) . ' kB (' . ($bytes / (1000 * 1000)) . ' MB)';