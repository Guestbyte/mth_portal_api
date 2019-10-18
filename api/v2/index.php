<?php
include('./MailChimp.php');

use \DrewM\MailChimp\MailChimp;

$MailChimp = new MailChimp('6b64f119d239790236c85be24171200e-us16');
// $mailchimp_list_id = '2da8383add'; // Lista MATHEMA ONLINE : https://us16.admin.mailchimp.com/lists/members?id=131005
// $mailchimp_list_id = '803e6a1581'; // Lista CLIENTES MATHEMA ONLINE II : https://us16.admin.mailchimp.com/lists/members?id=165933

header("Access-Control-Allow-Origin: *");
header("Content-type: application/json; charset=utf-8");
header("Access-Control-Allow-Methods: GET POST");
header("Access-Control-Allow-Headers: Authorization");

$method = $_SERVER['REQUEST_METHOD'];
$params = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
$route = $params['3'];
@$route2 = $params['4'];
@$route3 = $params['5'];
@$route4 = $params['6'];

// Declaring routes
$no_route = (!$route) ? true : false;
$route_mailchimp_subscribe = ($route == 'mailchimp' && $route2 == 'subscribe') ? true : false;
$route_woocommerce_webhooks = ($route == 'woocommerce' && $route2 == 'webhooks') ? true : false;

// Routes execution
switch (true) {
  case ($route_mailchimp_subscribe):
    $rawData = $_POST;
    $jsonData = json_decode($rawData);
    $data = $MailChimp->post("lists/$mailchimp_list_id/members", print_r($_POST, true));
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    die();
    break;
  case ($route_woocommerce_webhooks):
    wh_log('==================[ Incoming Request ]==================');
    $rawData = file_get_contents("php://input");
    $jsonData = json_decode($rawData);

    if (!isset($jsonData->arg)) {
      wh_log("Error identifing order id!");
      $return['error_imput'] = 'Error identifing order id.';
      wh_log("error dump _input:\n" . print_r(json_decode($rawData), true));
      header('HTTP/1.1 400 Bad Request');
      break;
    }

    wh_log("Proccessing order: " . @$jsonData->arg);

    $order = WP_API("GET", "/wc/v3/orders/" . $jsonData->arg);

    if (!isset($order->id)) {
      wh_log("Error retriving order data: \n" . print_r($order, true));
      $return['error_order'] = 'Error on retriving order data.';
      header('HTTP/1.1 500 Internal Server Error');
      break;
    }

    wh_log("dump order: \n" . print_r($order, true));

    $is_client = ($order->total > '0' || isset($order->coupon_lines[0]->code));
    if ($is_client) {
      $mc_array['merge_fields']['CLIENTE'] = 'SIM';
    } else {
      $mc_array['merge_fields']['CLIENTE'] = 'NAO';
    }

    $product_type = [];
    foreach ($order->line_items as $item) {
      unset($product);
      $product = WP_API("GET", "/wc/v3/products/" . $item->product_id);

      if (!isset($product->id)) {
        wh_log("Error retriving product data: \n" . print_r($product, true));
        $return['error_product'] = 'Error on retriving order data.';
        header('HTTP/1.1 500 Internal Server Error');
        break;
      }

      array_push($product_type, $product->sku);

      switch ($product->acf->carga_horaria_produto) {
        case "10":
          array_push($product_type, "CURSO");
          break;
        case "16":
          array_push($product_type, "CURSO");
          break;
        case "40":
          array_push($product_type, "TRILHA");
          break;
        case "120":
          array_push($product_type, "PLANO_FASE");
          $mth_campanha_plano = true;
          break;
        case "400":
          array_push($product_type, "PLANO_PREMIUM");
          $mth_campanha_plano = true;
          break;
        default:
          array_push($product_type, "TIPO_NAO_IDENTIFICADO: " . $product->acf->carga_horaria_produto);
          break;
      }
    }
    $mc_array['tags'] = $product_type;

    // CAMPANHA: Quem compra uma Plano, ganha de presente um curso gratis. 
    // Esta função identifica se o produto comprado é um dos Planos, cria um cupom
    if ($mth_campanha_plano) {
      wh_log("CAMPANHA: Compre Plano ganhe Curso...");
      $chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVXYZ';
      $code = 'MTH_' . substr(str_shuffle($chars), 0, 3);
      $percent = 100;
      $date_expires = '2020-01-31';
      $product_categories = 43; // Curso 10h
      $use_limit = 1; // quantas vezes pode ser usado
      $description = 'API: Campanha Compre Plano ganhe Curso. Cliente: ' . $order->billing->email;

      $coupom = mth_create_coupom($code, $percent, $date_expires, $product_categories, $use_limit, $description);

      if (isset($coupom->code)) {
        wh_log("Coupom created: " . $coupom->code);
        $mc_array['merge_fields']['CUPOM_PRES'] = $coupom->code;
      } else {
        wh_log("Error create new coupom: \n" . print_r($coupom, true));
        $return['error_coupom'] = 'Error create new coupom.';
      }
    }

    wh_log("Subscribing on MailChimp: " . $jsonData->arg);

    $mc_array['status'] = 'subscribed';
    $mc_array['email_address'] = $order->billing->email;
    $mc_array['merge_fields']['NOME'] = $order->billing->first_name;
    $mc_array['merge_fields']['SOBRENOME'] = $order->billing->last_name;
    $mc_array['merge_fields']['COMPRA'] = $order->date_created;
    $mc_array['merge_fields']['PAGAMENTO'] = $order->date_completed;
    $mc_array['merge_fields']['SITUACAO'] = $order->status;
    $mc_array['merge_fields']['ENDERECO'] = $order->billing->address_1;
    $mc_array['merge_fields']['BAIRRO'] = $order->billing->neighborhood;
    $mc_array['merge_fields']['CIDADE'] = $order->billing->city;
    $mc_array['merge_fields']['ESTADO'] = $order->billing->state;
    $mc_array['merge_fields']['CEP'] = $order->billing->postcode;

    if (isset($order->coupon_lines[0]->code)) {
      $mc_array['merge_fields']['CUPOM'] = $order->coupon_lines[0]->code;
    }

    if (isset($order->billing->phone)) {
      $mc_array['merge_fields']['TELEFONE'] = $order->billing->phone;
    }

    $mc_array['merge_fields']['CELULAR'] = $order->billing->cellphone;
    $mc_array['merge_fields']['CPF'] = $order->billing->cpf;
    $mc_array['merge_fields']['CNPJ'] = $order->billing->cnpj;
    $mc_array['merge_fields']['TIPOCLIENT'] = $order->billing->persontype;

    wh_log("dump array: \n" . print_r($mc_array, true));

    $mc_list_id = '803e6a1581'; // Lista CLIENTES MATHEMA ONLINE II : https://us16.admin.mailchimp.com/lists/members?id=165933
    $mc_result = $MailChimp->post("lists/$mc_list_id/members", $mc_array);

    if ($mc_result['status'] == 'subscribed') {
      wh_log("Success subscribed to Mailchimp!");
      header('HTTP/1.1 200 OK');
    } else {
      wh_log("Mailchimp Error: \n" . print_r($mc_result, true));
      $return['error_mailchimp'] = 'Error on subscribing to Mailchimp.';
      header('HTTP/1.1 500 Internal Server Error');
      break;
    }

    $return['status'] = '200';

    echo json_encode($return, JSON_UNESCAPED_UNICODE);
    die();
    break;
  case ($no_route):
    $data = array(
      "name" => "Dashboard Formar - API Personalizada",
      "description" => "WebService para dashboards do Mathema. [Fernando - 03/07/2019]",
      "home" => "https://mathema.com.br",
      "url" => "https://mathema.com.br/dashboard/v1/index.php",
      "version" => "v1.0.1 alpha - 09/2019",
      "status" => "Em desenvolvimento",
      "author" => "Fernando Ortiz de Mello - fernando.ortiz@mathema.com.br",
      "routes" => [
        "/" => [
          "methods" => ["GET"],
        ],
        "/users" => [
          "methods" => ["GET"]
        ],
        "/users/{id}" => [
          "methods" => ["GET"],
          "args" => [
            "id" => [
              "required" => true,
              "description" => "Identificador unico para o objeto.",
              "type" => "integer"
            ]
          ]
        ],
        "/courses" => [
          "methods" => ["GET"]
        ],
        "/courses/{id}" => [
          "methods" => ["GET"],
          "args" => [
            "id" => [
              "required" => true,
              "description" => "Identificador unico para o objeto.",
              "type" => "integer"
            ]
          ]
        ],
        "/accounts" => [
          "methods" => ["GET"]
        ],
        "/accounts/{id}/courses" => [
          "methods" => ["GET"],
          "args" => [
            "id" => [
              "required" => true,
              "description" => "Identificador unico para o objeto.",
              "type" => "integer"
            ]
          ]
        ]
      ]
    );
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found", true, 401);
    die();
    break;
}

echo json_encode($return, JSON_UNESCAPED_UNICODE);

function wh_log($msg)
{
  $logfile = 'api.log';
  file_put_contents($logfile, date("Y-m-d H:i:s") . " | " . $msg . "\n", FILE_APPEND);
}

function WP_API($method, $route, $data = false)
{
  $pagecnt = 1;
  $itemcnt = 0;
  $itemassoc = array();
  $base_url = "https://mathema.com.br/wp-json";
  $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczpcL1wvbWF0aGVtYS5jb20uYnIiLCJpYXQiOjE1NzEwNTYyODAsIm5iZiI6MTU3MTA1NjI4MCwiZXhwIjoxNTcxNjYxMDgwLCJkYXRhIjp7InVzZXIiOnsiaWQiOiIxNTcifX19.dp_MYkgSq-zA8trhBvVakzlA77T66FPgoyw94HqdRXw";

  // TODO: Validate and renew token: https://mathema.com.br/wp-json/jwt-auth/v1/token/validate
  //   {
  //     "code": "jwt_auth_invalid_token",
  //     "message": "Expired token",
  //     "data": {
  //         "status": 403
  //     }
  // }

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
    $url = $base_url . $route . "?per_page=100&page=" . $pagecnt;
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
      // echo gettype($itemlist);
      // echo $result;
      for ($i = 0; $i < count($itemlist); $i++) {
        $itemassoc[$itemlist[$i]->id] = $itemlist[$i];
        $itemcnt++;
      }
      if (count($itemlist) < 50) { //not going to be another page
        break;
      }
      // array_push($return, $result);
    }
  }

  return json_encode($itemassoc);
}

function MTH_API($method, $route, $data = false)
{
  $pagecnt = 1;
  $itemcnt = 0;
  $itemassoc = array();
  $base_url = "https://mathema.com.br/api/v1/index.php";

  while (1) {

    $curl = curl_init();
    echo "dentrod a funcition MTH_API";
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
    echo "result of mailchimp";
    print_r($result);
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

function mth_create_coupom($code, $percent, $date_expires = '', $product_categories = '', $use_limit = '',  $description = '')
{

  $result = WP_API("POST", "/wc/v3/coupons", [
    'code' => $code,
    'amount' => $percent,
    'description' => $description,
    'discount_type' => 'recurring_percent',
    'limit_usage_to_x_items' => $use_limit,
    'usage_limit' => $use_limit,
    'usage_limit_per_user' => $use_limit,
    'product_categories' => $product_categories,
    'individual_use' => 'true',
    'date_expires' => $date_expires
  ]);

  return $result;
}
