<?php
namespace Glenn;

class Request
{
    private $uri;
    private $method;
    	
	public function __construct($uri = null, $method = null)
	{
        if ($uri !== null) {
            $this->uri = $uri;
        } else if ($_SERVER['REQUEST_URI'] !== null) {
            $this->uri = $_SERVER['REQUEST_URI'];
        }
        
        if ($method !== null) {
            $this->method = $method;
        } else if ($_SERVER['REQUEST_METHOD'] !== null) {
            $this->method = $_SERVER['REQUEST_METHOD'];
        }
    }
    
    /**
     * Returns the chosen GET parameter if $key is set to an array key.
     * To return the full GET array, use $key as the filter boolean (default true).
     */
    public function get($key = null, $filter = true)
    {
        return $this->request(INPUT_GET, $key, $filter);
    }
    
    /**
     * Returns the chosen POST parameter if $key is set to an array key.
     * To return the full POST array, use $key as the filter boolean (default true).
     */
    public function post($key = null, $filter = true)
    {
        return $this->request(INPUT_POST, $key, $filter);
    }
    
    /**
     * Private method that does the filtering for the post and get methods.
     * Uses the native PHP filter FILTER_SANITIZE_STRING.
     */
    private function request($type, $key, $filter) {
        if ($key === null || $key === true) {
            return filter_input_array($type, FILTER_SANITIZE_STRING);
        } else if ($key === false) {
            return filter_input_array($type, FILTER_UNSAFE_RAW);
        } else if ($filter) {
            return filter_input($type, $key, FILTER_SANITIZE_STRING);
        } else {
            return filter_input($type, $key, FILTER_UNSAFE_RAW);
        }
    }
    
    public function __toString() {
        
    }
}

