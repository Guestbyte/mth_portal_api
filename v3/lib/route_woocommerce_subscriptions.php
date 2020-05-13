<?php


function route_woocommerce_subscriptions($status = 'active')
{  
    global $API;

    // wh_log("Bulk process started: Status=$status, After: $after, Before: $before ");
    // wh_log("Gathering orders... ");

    $subscriptions = json_decode($API->request("GET", "/wc/v1/subscriptions?status=$active&"));

    print_r($subscriptions);

    // if (!isset($orders)) {
    //     wh_log("Error retrieving order data: \n" . print_r($orders, true));
    //     $return['error_order'] = 'Error on retrieving order data.';
    //     // header('HTTP/1.1 500 Internal Server Error');
    //     return json_encode($return, JSON_UNESCAPED_UNICODE);
    // }

    // // $orders = array (
    // //     72348,
    // //     73645
    // // );

    // foreach ($orders as $order) {
    //     route_woocommerce_webhooks($order->id);
    //     // route_woocommerce_webhooks($order);
    // }

    // return $orders;
    $return['status'] = '200';
    wh_log('------------------[ route_woocommerce_webhooks_bulk ]------------------ ');
    return json_encode($return, JSON_UNESCAPED_UNICODE);
}