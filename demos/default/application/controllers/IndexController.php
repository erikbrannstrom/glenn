<?php
use Glenn\Controller,
    Glenn\Response;

class IndexController extends Controller
{
    public function indexAction()
    {
        return new Response("Index page");
    }
    
    public function aboutAction()
    
    {
        return new Response("About page");
    }
}