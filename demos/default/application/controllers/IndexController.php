<?php
use Glenn\Controller,
    Glenn\Response;

class IndexController extends Controller
{
    public function indexAction()
    {
        return new Response("Index page");
    }
    
    public function listAction()
    
    {
        return new Response("About page");
    }
}