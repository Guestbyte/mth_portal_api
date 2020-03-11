<?php 

/**
 * Undocumented class
 */
class API{
    
    public $baseRoute;

    function __construct($endpoint) {
        $this->baseRoute = $endpoint;
    }

    public function route(string $endpoint, string $func, $param = null){
    
        $REQUEST_URI = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
        $endpoint = explode('/', trim($this->baseRoute . $endpoint, '/'));

        (is_null($param)) ? $param = array_shift(array_filter($REQUEST_URI, function($uri){ return is_numeric($uri); })) : false;

        $REQUEST_URI = array_map(function($uri){
            if (!is_numeric($uri)) {
                return $uri;
            } 
        }, $REQUEST_URI);
        $REQUEST_URI = array_filter($REQUEST_URI);

        $endpoint = array_map(function($uri){
            if (!is_numeric($uri)) {
                return $uri;
            } 
        }, $endpoint);

        if ($REQUEST_URI == $endpoint) {
            echo $func($param);
        }
        
        return $this;
    }
}