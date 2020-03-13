<?php

function route_woocommerce_webhooks_bulk($status = 'cancelled', $after = '2020-01-01T00:00:00', $before = '2021-01-01T00:00:00')
{
    global $API;

    $status = 'on-hold';

    wh_log("Bulk process started: Status=$status, After: $after, Before: $before ");
    wh_log("Gathering orders... ");

    $orders = json_decode($API->request("GET", "/wc/v3/orders/?order=asc&status=$status&after=$after&before=$before&"));

    if (!isset($orders)) {
        wh_log("Error retrieving order data: \n" . print_r($orders, true));
        $return['error_order'] = 'Error on retrieving order data.';
        // header('HTTP/1.1 500 Internal Server Error');
        return json_encode($return, JSON_UNESCAPED_UNICODE);
    }

    // $orders = array (
    //     72348,
    //     73645
    // );

    foreach ($orders as $order) {
        route_woocommerce_webhooks($order->id);
        // route_woocommerce_webhooks($order);
    }

    // return $orders;
    $return['status'] = '200';
    wh_log('------------------[ route_woocommerce_webhooks_bulk ]------------------ ');
    return json_encode($return, JSON_UNESCAPED_UNICODE);
}