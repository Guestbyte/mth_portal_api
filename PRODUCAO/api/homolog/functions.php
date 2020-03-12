<?php
require 'lib/route_mailchimp_subscribe.php';
require 'lib/route_woocommerce_webhooks.php';
require 'lib/route_woocommerce_webhooks_bulk.php';

/**
 * Undocumented function
 *
 * @param integer $id
 * @return void
 */
function get_paying_customer(int $id)
{
    global $API;

    $result = $API->request("GET", "/wc/v3/customers/$id?");
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