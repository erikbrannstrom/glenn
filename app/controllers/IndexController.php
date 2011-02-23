<?php
use glenn\dispatch\Controller,
    glenn\http\Response,
	glenn\view\View;

class IndexController extends Controller
{
    public function indexAction()
    {
        $view = new View('index');
        //$view->addParam('name', 'Glenn');
		$output = $view->render();
        return new Response($output);
    }
}