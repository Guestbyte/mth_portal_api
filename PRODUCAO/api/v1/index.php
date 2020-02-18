<?php
include('./MailChimp.php');
require '../vendor/autoload.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use \DrewM\MailChimp\MailChimp;

// create a log channel 
$log = new Logger('name');
$log->pushHandler(new StreamHandler('api.log', Logger::WARNING));

// $log->warning('Foo');
// $log->error('Bar');

$MailChimp = new MailChimp('6b64f119d239790236c85be24171200e-us16');
// $mailchimp_list_id = '2da8383add'; // Lista MATHEMA ONLINE : https://us16.admin.mailchimp.com/lists/members?id=131005
// $mailchimp_list_id = '803e6a1581'; // Lista CLIENTES MATHEMA ONLINE II : https://us16.admin.mailchimp.com/lists/members?id=165933
// $mailchimp_list_id = 'f3397d3993'; // Lista 'Leads Portal' : https://us16.admin.mailchimp.com/lists/members?id=177689

header("Access-Control-Allow-Origin: *");
header("Content-type: application/json; charset=utf-8");
header("Access-Control-Allow-Methods: GET POST");
header("Access-Control-Allow-Headers: Authorization");

$method = $_SERVER['REQUEST_METHOD'];
$params = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
$route = $params['2'];
@$route2 = $params['3'];
@$route3 = $params['4'];
@$route4 = $params['5'];

wh_log('==================[ Incoming Request ]==================');
// wh_log("Params:\n" . print_r($params, true));

// Declaring routes
$no_route = (!$route) ? true : false;
$route_teste = ($route == 'echo') ? true : false;
$route_mailchimp_subscribe = ($route == 'mailchimp' && $route2 == 'subscribe') ? true : false;
$route_woocommerce_webhooks = ($route == 'woocommerce' && $route2 == 'webhooks' && !isset($route3)) ? true : false;
$route_woocommerce_webhooks_id = ($route == 'woocommerce' && $route2 == 'webhooks' && is_numeric($route3)) ? true : false;
// $route_woocommerce_webhooks_force = ($route == 'woocommerce' && $route2 == 'webhooks' && $route3 == 'force') ? true : false;

// Routes execution   
switch (true) {
  case ($route_mailchimp_subscribe):
    echo route_mailchimp_subscribe($_POST);
    die();
    break;
  case ($route_woocommerce_webhooks):
    echo route_woocommerce_webhooks();
    die();
    break;
  case ($route_woocommerce_webhooks_id):
    echo route_woocommerce_webhooks($route3);
    die();
    break;
  case ($route_teste):
    $data['status'] = 'echo done!';
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    die();
    break;
    // case ($route_woocommerce_webhooks_force):
    //   wh_log("FORCING SYNC orders: ");
    //   $orders = WP_API("GET", "/wc/v3/orders/?status=completed&");
    //   foreach ($orders as $order) {
    //     wh_log("FORCING process orders: " . $order->id);
    //     echo route_woocommerce_webhooks($order->id);
    //     die();
    //   }
    //   // wh_log("Error retriving order data: \n" . print_r($orders, true));
    //   // echo route_woocommerce_webhooks($route3);
    //   // echo json_encode($orders, JSON_UNESCAPED_UNICODE);
    //   die();
    //   break;
  case ($no_route):
    $data = array(
      "name" => "MTH API",
      "description" => "RestAPI WebService destinado às integrações do Mathema.",
      "home" => "https://mathema.com.br",
      "url" => "https://mathema.com.br/api/v2/",
      "version" => "v2 - 28/01/2020",
      "status" => "production",
      "author" => "Fernando Ortiz de Mello - fernando.ortiz@mathema.com.br",
      "routes" => [
        "/" => [
          "methods" => ["GET"],
        ],
        "/mailchimp/subscribe" => [
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
  // $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczpcL1wvbWF0aGVtYS5jb20uYnIiLCJpYXQiOjE1NzE2NjYzNzAsIm5iZiI6MTU3MTY2NjM3MCwiZXhwIjoxNTcyMjcxMTcwLCJkYXRhIjp7InVzZXIiOnsiaWQiOiIyIn19fQ.ft4d3UFFFFYDnFfnd8oKOtyUdoTeV5Y0r7Wcpb_iixA";
  $token = MTH_get_jwt_token();

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

function MTH_API($method, $route, $data = false)
{
  $pagecnt = 1;
  $itemcnt = 0;
  $itemassoc = array();
  $base_url = "https://mathema.com.br/api/v1/index.php";

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

function MTH_get_jwt_token()
{
  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => "https://mathema.com.br/wp-json/jwt-auth/v1/token",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => "------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"username\"\r\n\r\nrest_api_user\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"password\"\r\n\r\nMTH@api!rest\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW--",
    CURLOPT_HTTPHEADER => array(
      "cache-control: no-cache",
      "content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW",
      "postman-token: 716c77cb-2bd1-2278-877e-e2f5ebd9e2e0"
    ),
  ));

  $response = curl_exec($curl);
  $err = curl_error($curl);
  $jsonData = json_decode($response, true);
  $token = @$jsonData['token'];

  curl_close($curl);

  if ($err && !isset($token)) {
    $err_array['status'] = 'Error on generate new token!';
    $err_array['error'] = $err;
    wh_log("Data posted not valid.\n" . print_r($err_array, true));
    header('HTTP/1.1 400 Bad Request');
    return json_encode($err_array, JSON_UNESCAPED_UNICODE);
  }

  return $token;
}

function mth_create_coupom($code, $percent, $date_expires = '', $product_categories = '', $use_limit = '',  $description = '')
{

  $result = WP_API("POST", "/wc/v3/coupons/?", [
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

function route_mailchimp_subscribe($post)
{
  wh_log('=======[ API request - route: route_mailchimp_subscribe ]=========');
  global $MailChimp;
  $data = (object) $post;
  $mc_list_id = @$data->list_id;
  $status = @$data->status;
  $email_address = @$data->email_address;

  $valid_post = (isset($mc_list_id) && isset($email_address) && isset($status));
  if (!$valid_post) {
    $err_array['status'] = 'Data posted is invalid!';
    $err_array['post'] = $post;
    wh_log("Data posted not valid.\n" . print_r($err_array, true));
    header('HTTP/1.1 400 Bad Request');
    return json_encode($err_array, JSON_UNESCAPED_UNICODE);
  }

  $mc_array['status'] = $status;
  $mc_array['email_address'] = $email_address;

  foreach ($data->merge_fields as $key => $value) {
    $mc_array['merge_fields'][$key] = $value;
  }

  foreach ($data->tags as $key => $value) {
    $mc_array['tags'][$key] = $value;
  }

  wh_log("Mailchimp data: \n" . print_r($data, true));
  wh_log("Mailchimp mc_array: \n" . print_r($mc_array, true));
  $result = $MailChimp->post("lists/$mc_list_id/members", $mc_array);

  if ($result['title'] == 'Member Exists') {
    wh_log("Member Exists on list, tryng to update...");
    $subscriber_hash = md5($email_address);

    $put_result = $MailChimp->put("lists/$mc_list_id/members/$subscriber_hash", $mc_array);

    if ($put_result['status'] !== 'subscribed') {
      wh_log("Mailchimp Error: \n" . print_r($put_result, true));
      $return['error_mailchimp'] = 'Error on subscribing to Mailchimp.';
      $return['return'] = $put_result;
      header('HTTP/1.1 500 Internal Server Error');
      return json_encode($return, JSON_UNESCAPED_UNICODE);
    }

    wh_log("Success updated to Mailchimp!\n" . print_r($put_result, true));
    return json_encode($put_result, JSON_UNESCAPED_UNICODE);
  }

  wh_log("Success subscribed to Mailchimp!\n" . print_r($result, true));
  return json_encode($result, JSON_UNESCAPED_UNICODE);
}

function route_woocommerce_webhooks($order_id = false)
{
  global $MailChimp;
  wh_log('------------------[ route_woocommerce_webhooks ]------------------');

  $rawData = file_get_contents("php://input");
  $jsonData = json_decode($rawData);

  if ($order_id) {
    $jsonData->arg = $order_id;
    wh_log("Manually process order: " . $jsonData->arg);
  }

  if (!isset($jsonData->arg)) {
    wh_log("Error identifing order id!");
    $return['error_imput'] = 'Error identifing order id.';
    wh_log("error dump _input:\n" . print_r(json_decode($rawData), true));
    header('HTTP/1.1 400 Bad Request');
    // break;
    return json_encode($return, JSON_UNESCAPED_UNICODE);
  }

  wh_log("Proccessing order: " . @$jsonData->arg);

  $order = WP_API("GET", "/wc/v3/orders/" . $jsonData->arg . "?");

  if (!isset($order->id)) {
    wh_log("Error retriving order data: \n" . print_r($order, true));
    $return['error_order'] = 'Error on retriving order data.';
    header('HTTP/1.1 500 Internal Server Error');
    // break;
    return json_encode($return, JSON_UNESCAPED_UNICODE);
  }

  $is_client = ($order->total > '0' || isset($order->coupon_lines[0]->code));
  if ($is_client) {
    $mc_array['merge_fields']['CLIENTE'] = 'SIM';
  } else {
    $mc_array['merge_fields']['CLIENTE'] = 'NAO';
  }

  $product_type = [];
  foreach ($order->line_items as $item) {
    unset($product);
    $product = WP_API("GET", "/wc/v3/products/" . $item->product_id . "?");
    $mc_nome_curso = $item->name;

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
        $mth_campanha_plano = false;
        break;
      case "400":
        array_push($product_type, "PLANO_PREMIUM");
        $mth_campanha_plano = false;
        break;
      default:
        array_push($product_type, "TIPO_NAO_IDENTIFICADO: " . $product->acf->carga_horaria_produto);
        break;
    }
  }
  $mc_array['tags'] = $product_type;

  // CAMPANHA: Quem compra uma Plano, ganha de presente um curso gratis. 
  // Esta funÃ§Ã£o identifica se o produto comprado Ã© um dos Planos, cria um cupom
  if (@$mth_campanha_plano) {
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
  $mc_array['merge_fields']['CURSO'] = $mc_nome_curso;

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



  $mc_list_id = '803e6a1581'; // Lista CLIENTES MATHEMA ONLINE II : https://us16.admin.mailchimp.com/lists/members?id=165933
  $mc_result = $MailChimp->post("lists/$mc_list_id/members", $mc_array);

  if ($mc_result['status'] == 'subscribed') {
    wh_log("Success subscribed to Mailchimp!");
    header('HTTP/1.1 200 OK');
  } elseif ($mc_result['title'] == 'Member Exists') {
    wh_log("Member Exists on list, tryng to update...");
    $subscriber_hash = md5($order->billing->email);

    $put_result = $MailChimp->put("lists/$mc_list_id/members/$subscriber_hash", $mc_array);

    if ($put_result['status'] !== 'subscribed') {
      wh_log("Mailchimp Error: \n" . print_r($put_result, true));
      wh_log("dump array posted on update: \n" . print_r($mc_array, true));
      $return['error_mailchimp'] = 'Error on subscribing to Mailchimp.';
      $return['return'] = $put_result;
      header('HTTP/1.1 500 Internal Server Error');
      return json_encode($return, JSON_UNESCAPED_UNICODE);
    }

    $mc_result_get = $MailChimp->get("lists/$mc_list_id/segments");
    // wh_log("mc_result_get: \n" . print_r($mc_result_get['segments'], true));

    foreach ($mc_array['tags'] as $member_tag) {
      foreach ($mc_result_get['segments'] as $list_tag) {
        if ($list_tag['type'] == 'static' and $list_tag['name'] == $member_tag) {
          $data['members_to_add'] = [$order->billing->email];
          $mc_result_add_member_to_tag = $MailChimp->post("lists/$mc_list_id/segments/" . $list_tag['id'], $data);
          if ($mc_result_add_member_to_tag['members_added'][0]['status'] == 'subscribed') {
            wh_log("Success on update TAG: $member_tag");
          } else {
            // wh_log("Error on update tag - DUMP: \n" . print_r($mc_result_add_member_to_tag, true));
            wh_log("Error on update tag: $member_tag. Fail or email already exist on tag.");
          }
        }
      }
    }

    $return['status'] = '200';
    // wh_log("Success updated to Mailchimp!\n" . print_r($put_result, true));
    wh_log("Success updated to Mailchimp!");
    wh_log('------------------[ route_woocommerce_webhooks ]------------------ END');
    return json_encode($put_result, JSON_UNESCAPED_UNICODE);
  } else {
    wh_log("Mailchimp Error: \n" . print_r($mc_result, true));
    wh_log("dump array posted: \n" . print_r($mc_array, true));
    $return['error_mailchimp'] = 'Error on subscribing to Mailchimp.';
    header('HTTP/1.1 500 Internal Server Error');
    return json_encode($return, JSON_UNESCAPED_UNICODE);
  }

  $return['status'] = '200';
  wh_log('------------------[ route_woocommerce_webhooks ]------------------ END');
  return json_encode($return, JSON_UNESCAPED_UNICODE);
}