<?php
namespace Glenn;

abstract class Controller 
{
    private $request;
    
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    
}