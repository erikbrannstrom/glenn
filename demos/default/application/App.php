<?php
use Glenn\FrontController,
    Glenn\Request,
    Glenn\Response;

class App
{
    
    private static function init()
    {
        set_include_path('../application/controllers' . PATH_SEPARATOR . '../../../library');

        spl_autoload_register(function($class_name) {
            require_once str_replace("\\", "/", $class_name) . '.php';
        });

        set_exception_handler(function($exception) {
            echo "<h1>Something went horribly wrong!</h1> <p>" . $exception->getMessage() . "</p>";
        });
    }
    
    public static function start()
    {
        App::init();
        $frontController = new FrontController();
        $request = new Request();
        $response = $frontController->dispatch($request);
        $response->send();
    }
    
}