<?php
require 'lib/route_mailchimp_subscribe.php';
require 'lib/route_woocommerce_webhooks.php';
require 'lib/route_woocommerce_webhooks_bulk.php';
require 'lib/mth_create_coupom.php';
require 'lib/MTH_API.php';
require 'lib/WP_API.php';
require 'lib/MTH_get_jwt_token.php';
require 'lib/mailchimp_get_segments.php';
require 'lib/mailchimp_create_tags.php';
require 'lib/mailchimp_add_member_to_tag.php';
require 'lib/mailchimp_subscribe.php';
require 'lib/mailchimp_member_exist.php';
require 'lib/mth_campaign_plano.php';

/**
 * Undocumented function
 *
 * @param integer $id
 * @return void
 */
function get_paying_customer(int $id)
{
    $result = WP_API("GET", "/wc/v3/customers/$id?");
    return $result->is_paying_customer;
}

/**
 * Write message in log file 
 *
 * @param string $msg
 * @return void
 */
function wh_log(string $msg)
{
    global $log;
    $log->warning($msg);
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
function return_error(string $name, string $description, $data = '')
{
    $return['name'] = $name;
    $return['description'] = $description;
    $return['data'] = $data ;
    wh_log("$name: $description\n" . print_r($data, true));
    header('HTTP/1.1 400 Bad Request');
    return json_encode($return, JSON_UNESCAPED_UNICODE);
}

/**
 * Check if order type is from a client
 *
 * @param [type] $order
 * @return boolean
 */
function is_client($order) {
    
    $is_paying_customer = get_paying_customer($order->customer_id);
    $order_zero_cart = ($order->total == '0.00');
    $order_completed = ($order->status == 'completed');
    $is_client = ($order_completed and ($is_paying_customer and !$order_zero_cart));

    return $is_client;
}

/**
 * Return if Order is typed to be 
 *
 * @param [type] $order
 * @return boolean
 */
function mth_is_onhold_list($order, $order_payment_method) {

    $order_boleto = ($order_payment_method == 'Boleto');
    $order_onhold = ($order->status == 'on-hold');
    $order_pending = ($order->status == 'pending');

    $order_onhold_boleto = ($order_onhold and $order_boleto);
    $order_pending_boleto = ($order_pending and $order_boleto);
    
    $is_onhold_list = ($order_onhold_boleto or $order_pending_boleto);
    
    if ($is_onhold_list) {
         wh_log("Order status: $order->status | Payment method: $order_payment_method. Subscribing on different audience: $mc_list_id");
     }
     
    return $is_onhold_list;
}

function baseRoute($data) {
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