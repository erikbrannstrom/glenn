<?php
namespace glenn\view;

class View
{
    private $viewFile;
    private $params;
    
    public function __construct($viewFile)
    {
        $this->viewFile = $viewFile;
        $this->params = array();
    }
    
    public function addParam($key, $value)
    {
        $this->params[$key] = $value;
    }

	public function addParams($params)
	{
		$this->params = array_merge($this->params, $params);
	}
	
	public static function factory($file, $params)
	{
		$view = new View($file);
		$view->addParams($params);
		return $view;
	}
    
	public function render()
	{
	    return \streambur\Party::woohoo("I overrode.. Overrid? Overrode your ass!");
	}
}