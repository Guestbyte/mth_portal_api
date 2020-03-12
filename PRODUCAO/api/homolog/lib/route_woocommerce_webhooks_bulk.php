<?php

function route_woocommerce_webhooks_bulk($status = 'completed', $after = '2019-06-01T00:00:00', $before = '2020-01-01T00:00:00')
{

    wh_log("Bulk process started: Status=$status, After: $after, Before: $before ");
    wh_log("Gathering orders... ");

    $orders = json_decode($API->request("GET", "/wc/v3/orders/?order=asc&status[0]=$status&after=$after&before=$before&"));

    if (!isset($orders)) {
        wh_log("Error retrieving order data: \n" . print_r($orders, true));
        $return['error_order'] = 'Error on retrieving order data.';
        // header('HTTP/1.1 500 Internal Server Error');
        return json_encode($return, JSON_UNESCAPED_UNICODE);
    }

    foreach ($orders as $order) {
        route_woocommerce_webhooks($order->id);
    }

    // return $orders;
    $return['status'] = '200';
    wh_log('------------------[ route_woocommerce_webhooks_bulk ]------------------ ');
    return json_encode($return, JSON_UNESCAPED_UNICODE);
}