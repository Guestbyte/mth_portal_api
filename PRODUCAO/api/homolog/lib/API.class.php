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
     * Route declaration.  
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

    
    /**
     * Return success message routine
     *
     * @param string $name
     * @param string $status
     * @param string $data
     * @return void
     */
    function return_success(string $name, string $status, $data = '')
    {
        $return['name'] = $name;
        $return['status'] = $status;
        $return['data'] = $data;
        wh_log("$name: $status\n" . print_r($data, true));
        header('HTTP/1.1 200 OK');
        return json_encode($return, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Return error message routine
     *
     * @param string $name Error name 
     * @param string $description Error description
     * @param string $data Optional. Detailed data
     * @return void
     */
    function return_error(string $name, string $description, $data = '') {
        $return['name'] = $name;
        $return['description'] = $description;
        $return['data'] = $data ;
        wh_log("$name: $description\n" . print_r($data, true));
        header('HTTP/1.1 400 Bad Request');
        return json_encode($return, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Undocumented function
     *
     * @param string $method
     * @param string $route
     * @param boolean $data
     * @return void
     */
    function request(string $method, string $route, $data = false)
    {
        global $MTH;

        $pagecnt = 1;
        $itemcnt = 0;
        $itemassoc = array();
        $base_url = "https://mathema.com.br/wp-json";
        $token = $MTH->get_jwt_token();

        while (1) {

            $curl = curl_init();
            switch ($method) {
                case "POST":
                    curl_setopt($curl, CURLOPT_POST, 1);
                    if ($data)
                        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                    break;
                case "PUT":
                    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                    if ($data)
                        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                    break;
                default:
                    if ($data)
                        $route = sprintf("%s?%s", $route, http_build_query($data));
            }

            // OPTIONS:
            $url = $base_url . $route . "per_page=100&page=" . $pagecnt;
            $pagecnt++;
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                "authorization: Bearer $token"
            ));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

            // EXECUTE:
            $result = curl_exec($curl);
            if (!$result) {
                echo $url;
                die("Connection Failure");
            }
            curl_close($curl);
            if (trim($result) == '[]' || $pagecnt > 30 || $result === false) {
                break; //stop if we run out, or if something went wrong and $pagecnt is over 30
            } else {
                $itemlist = json_decode($result);

                if (is_object($itemlist)) {
                    return $itemlist;
                }

                for ($i = 0; $i < count($itemlist); $i++) {
                    $itemassoc[$itemlist[$i]->id] = $itemlist[$i];
                    $itemcnt++;
                }

                if (count($itemlist) < 50) { //not going to be another page
                    break;
                }
            }
        }
        return json_encode($itemassoc);
    }

    /**
     * Undocumented function
     *
     * @param string $method
     * @param string $route
     * @param boolean $data
     * @return void
     */
    function mth_request(string $method, string $route, $data = false)
    {
        $pagecnt = 1;
        $itemcnt = 0;
        $itemassoc = array();
        $base_url = "https://mathema.com.br/api/v2";

        while (1) {

            $curl = curl_init();
            print_r($data);
            switch ($method) {
                case "POST":
                    curl_setopt($curl, CURLOPT_POST, 1);
                    if ($data)
                        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                    break;
                case "PUT":
                    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                    if ($data)
                        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                    break;
                default:
                    if ($data)
                        $route = sprintf("%s?%s", $route, http_build_query($data));
            }

            // OPTIONS:
            $url = $base_url . $route;
            $pagecnt++;
            curl_setopt($curl, CURLOPT_URL, $url);
            // curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            //   "authorization: Bearer $token"
            // ));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

            // EXECUTE:
            $result = curl_exec($curl);

            if (!$result) {
                echo $url;
                die("Connection Failure");
            }
            curl_close($curl);
            if (trim($result) == '[]' || $pagecnt > 30 || $result === false) {
                break; //stop if we run out, or if something went wrong and $pagecnt is over 30
            } else {
                $itemlist = json_decode($result);

                if (is_object($itemlist)) {
                    return $itemlist;
                }
                // echo gettype($itemlist);
                // echo $result;
                for ($i = 0; $i < count($itemlist); $i++) {
                    $itemassoc[$itemlist[$i]->id] = $itemlist[$i];
                    $itemcnt++;
                }
                if (count($itemlist) < 50) { //not going to be another page
                    break;
                }
            }
        }

        return json_encode($itemassoc);
    }
}