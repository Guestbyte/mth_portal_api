<?php 

/**
 * Undocumented class
 */

class API{
    
    public $baseRoute;
    public $routeMatch = false;
    public $requestId = null;
    public $version = "4.0.1";
    public $status = "production";
    public $author = "Fernando Ortiz de Mello (guestbyte@gmail.com)";
    public $docs = [];

    function __construct($endpoint) {
        $this->baseRoute = $endpoint;
        $this->REQUEST_URI = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
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
    
        //$REQUEST_URI = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
        $endpoint = explode('/', trim($this->baseRoute . $endpoint, '/'));

         (is_null($this->requestId)) ? @$this->requestId = array_shift(array_filter($this->REQUEST_URI, function($uri){ return is_numeric($uri); })) : null;

        $this->REQUEST_URI = array_map(function($uri){
            if (!is_numeric($uri)) {
                return $uri;
            } 
        }, $this->REQUEST_URI);
        $this->REQUEST_URI = array_filter($this->REQUEST_URI);

        $endpoint = array_map(function($uri){
            if (!is_numeric($uri)) {
                return $uri;
            } 
        }, $endpoint);

        if ($this->REQUEST_URI === $endpoint) {
            
            if (function_exists($func)) {
                echo $func();
            } else {
                echo $this->$func();
            }

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

        // $data = [];
        $data['name'] = "API Mathema";
        $data['description'] = "RestAPI para viabilizar as integrações do Mathema.";
        $data['home'] = "https://".$_SERVER['SERVER_NAME'];
        $data['url'] = "https://".$_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
        $data['version'] = $this->version;
        $data['status'] = $this->status;
        $data['author'] = $this->author;
        $data['routes']["/"]["methods"] = ["GET"];
        $data['routes']["/mailchimp/subscribe"]["methods"] = ["POST"];
        $data['routes']["/mailchimp/subscribe"]["args"] = ["Documentação em andamento"];
        $data['routes']["/woocommerce/webhooks"]["methods"] = ["POST"];
        $data['routes']["/woocommerce/webhooks"]["args"]['{arg}']['required'] = true;
        $data['routes']["/woocommerce/webhooks"]["args"]['{arg}']['description'] = "Order number";
        $data['routes']["/woocommerce/webhooks"]["args"]['{arg}']['type'] = "integer";
        $data['routes']["/woocommerce/webhooks/{id}"]["methods"] = ["GET"];
        $data['routes']["/woocommerce/webhooks/{id}"]["args"]['{id}']['required'] = true;
        $data['routes']["/woocommerce/webhooks/{id}"]["args"]['{id}']['description'] = "Order number";
        $data['routes']["/woocommerce/webhooks/{id}"]["args"]['{id}']['type'] = "integer";
        $data['routes']["/woocommerce/webhooks/bulk"]["methods"] = ["GET"];
        $data['routes']["/woocommerce/webhooks/bulk"]["args"] = ["Documentação em andamento"];
        $data['routes']["/woocommerce/subscriptions"]["methods"] = ["GET"];
        $data['routes']["/woocommerce/subscriptions"]["args"] = ["Documentação em andamento"];
        $data['routes']["/moodle/progress/csv/{id}"]["methods"] = ["GET"];
        $data['routes']["/moodle/progress/csv/{id}"]["args"]['id']['required'] = true;
        $data['routes']["/moodle/progress/csv/{id}"]["args"]['id']['description'] = 'Moodle course ID';
        $data['routes']["/moodle/progress/csv/{id}"]["args"]['id']['type'] = 'integer';
        $data['routes']["/mailchimp/ciranda2020"]["methods"] = ["GET"];
        $data['routes']["/mailchimp/ciranda2020"]["args"] = ["Documentação em andamento"];

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
            if (trim($result) == '[]' || $pagecnt > 100 || $result === false) {
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
    function moodle_request(string $function, string $args)
    {
        global $MTH;
        global $cache;
        // $cache_name = $function.'_'.$args;

        // $isCached = $cache->get_cache($cache_name);
        // if ($isCached) {
        //     return json_decode($isCached);
        // }
        
        $itemassoc = array();
        $base_url = "https://mathema.com.br/online/webservice/rest/server.php?moodlewsrestformat=json&wstoken=b3544b0a59aaff2016be4499ce8024f4&wsfunction=";
        
        $curl = curl_init();
        $url = $base_url . $function . $args;
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        
        $result = curl_exec($curl);

        if (!$result) {
            print_r($result);
            die("Connection Failure");
        }
        curl_close($curl);
        
        $itemlist = json_decode($result);
        
        if (is_object($itemlist)) {
            // $cache->set_cache($cache_name, json_encode($itemlist));
            return $itemlist;
        }
        
        for ($i = 0; $i < count($itemlist); $i++) {
            $itemassoc[] = $itemlist[$i];
        }
        
        // $cache->set_cache($cache_name, json_encode($itemassoc));
        return $itemassoc;
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