<?php

// Define constants for profiling so we don't cause conflicts with variable names.
define('START_TIME', microtime(true));
define('START_MEMORY', memory_get_usage());

// Useful constants for creating paths.
define('DS', DIRECTORY_SEPARATOR);
define('PS', PATH_SEPARATOR);
define('BASE_PATH', realpath('..') . DS);
define('APP_PATH', realpath('../app') . DS);
define('SYSTEM_PATH', realpath('../glenn') . DS);

// Create auto-loader
spl_autoload_register(function($class_name) {
	$paths = array(
		'app' => APP_PATH, 
		'streambur' => BASE_PATH . 'streambur' . DS,
		'glenn' => SYSTEM_PATH
	);
	
	$namespace = explode('\\', $class_name);
	foreach($paths as $name => $path) {
		if($namespace[0] === $name) {
			unset($namespace[0]);
			$test = $path . 'classes/' . implode(DS, $namespace) . '.php';
			if(file_exists($test)) {
				require_once $test;
				return;
			}
		} else {
			$test = $path . 'extensions/' . implode(DS, $namespace) . '.php';
			if(file_exists($test)) {
				require_once $test;
				return;
			}
		}
	}
});

// Create a custom error handler
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
	if (!(error_reporting() & $errno)) {
		return;
	}
	
	$code = file($errfile);
	
	$start = ($errline <= 3) ? 0 : $errline-3;
	$end = (count($code)+1 <= $errline+3) ? count($code)+1 : $errline+3;
	
	$errcode = array();
	for ($i = $start; $i <= $end; $i++) {
		$errcode[$i] = htmlentities($code[$i-1]);
	}

	// Clean output buffer if it's in use so we only print the error.
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

/*********************
 * Start application *
 *********************/

use glenn\http\Request,
	glenn\action\FrontController;

$request = new Request();
$frontController = new FrontController();
$response = $frontController->dispatch($request);
$response->send();

// Echo resource use
echo '<pre>';
echo 'RESOURCES:' . PHP_EOL;
echo (microtime(true) - START_TIME) * 1000 . ' ms' . PHP_EOL;
$bytes = memory_get_usage() - START_MEMORY;
echo ($bytes / 1000) . ' kB (' . ($bytes / (1000 * 1000)) . ' MB)';