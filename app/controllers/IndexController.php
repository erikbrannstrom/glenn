<?php
use glenn\controller\Controller,
    glenn\http\Response,
	glenn\view\View;

class IndexController extends Controller
{
    public function indexAction()
    {
        $view = new View('index');
        //$view->addParam('name', 'Glenn');
		try {
			$output = $view->render();
		} catch(Exception $e) {
			echo "GAAAAAAAAAH";
		}
        return new Response($output);
    }
}