<?php 

/**
 * Undocumented class
 */
class API{
    
    public $baseRoute;
    public $routeMatch = false;

    function __construct($endpoint) {
        $this->baseRoute = $endpoint;
    }

    /**
     * Undocumented function
     *
     * @param string $endpoint
     * @param string $func
     * @param [type] $param
     * @return void
     */
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

        if ($REQUEST_URI === $endpoint) {
            echo $func($param);
            $this->routeMatch = true;
        }
        
        return $this;
    }

    public function catchError() {
        if ($this->routeMatch == false ) {
            $this->baseRoute();
        }
    }

    function baseRoute($data = null) {
        $data = array(
            "name" => "MTH API",
            "description" => "HOMOLOG: WebService para viabilizar as integrações do Mathema.",
            "home" => "https://mathema.com.br",
            "url" => "https://mathema.com.br/api/homolog/",
            "version" => "1.5",
            "status" => "beta",
            "author" => "Fernando Ortiz de Mello - fernando.ortiz@mathema.com.br",
            "routes" => [
                "/" => [
                "methods" => ["GET"],
                ],
                "/mailchimp/subscribe" => [
                "methods" => ["GET"],
                "args" => [
                    "documentação em andamento"
                ]
                ],
                "/woocommerce/webhooks" => [
                "methods" => ["POST"],
                "args" => [
                    "arg" => [
                    "required" => true,
                    "description" => "Numero do pedido.",
                    "type" => "integer"
                    ]
                ]
                ],
                "/woocommerce/webhooks/{id}" => [
                "methods" => ["GET"],
                "args" => [
                    "id" => [
                    "required" => true,
                    "description" => "Numero do pedido.",
                    "type" => "integer"
                    ]
                ]
                ],
                "/woocommerce/webhooks/bulk/{status}" => [
                "description" => "Processa a integração retroativamente.",
                "methods" => ["GET"],
                "args" => [
                    "status" => [
                    "required" => false,
                    "description" => "Status do pedido.",
                    "default" => "completed",
                    "type" => "string",
                    "enum" => [
                        "on-hold",
                        "completed"
                    ]
                    ]
                ]
                ]
            ]
        );
        header('HTTP/1.1 200 OK');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}