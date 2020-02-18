<?php
require '../vendor/autoload.php';
require './MailChimp.php';
require './credentials.php';
require './functions.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use \DrewM\MailChimp\MailChimp;

$log = new Logger('name');
$log->pushHandler(new StreamHandler('audit.log', Logger::WARNING));

$MailChimp = new MailChimp(MAILCHIMP_KEY);

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
$route_woocommerce_webhooks_bulk = ($route == 'woocommerce' && $route2 == 'webhooks' && $route3 == 'bulk') ? true : false;

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
  case ($route_woocommerce_webhooks_bulk):
    echo route_woocommerce_webhooks_bulk($route4);
    die();
    break;
  case ($route_teste):
    $data['status'] = 'echo done!';
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    die();
    break;
  case ($no_route):
    $data = array(
      "name" => "MTH API",
      "description" => "HOMOLOG: WebService para viabilizar as integrações do Mathema.",
      "home" => "https://mathema.com.br",
      "url" => "https://mathema.com.br/api/homolog/",
      "version" => "beta",
      "status" => "developer",
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
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    // header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found", true, 401);
    header('HTTP/1.1 200 OK');
    die();
    break;
}

echo json_encode($return, JSON_UNESCAPED_UNICODE);