<?php
namespace Glenn;

class FrontController implements Dispatcher
{
    private $router;
    
    public function __construct() 
    {
        $this->router = new Router();
    }
    
	public function dispatch(Request $request)
	{
        $result = $this->router->route($request);
        
        $class = ucfirst($result['controller']) . 'Controller';
        $method = lcfirst($result['action']) . 'Action';
        
        $controller = new $class($request);
        
        return $controller->$method();
	}
    
    public function getRouter()
    {
        return $this->router;
    }
}