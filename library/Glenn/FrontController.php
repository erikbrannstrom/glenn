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
        
        $actions = get_class_methods($class);
        if(!in_array($method, $actions)) {
            throw new \BadMethodCallException("The action '$method' does not exist.");
        }
        
        $controller = new $class($request);
        
        return $controller->$method();
	}
    
    public function getRouter()
    {
        return $this->router;
    }
}