<?php
function route_woocommerce_webhooks($order_id = false)
{
    
    wh_log('------------------[ route_woocommerce_webhooks ]------------------');
    global $MailChimp;
    global $API;
    global $MTH;
    
    $mc_customers_list_id = '803e6a1581'; // CLIENTES MATHEMA ONLINE II : https://us16.admin.mailchimp.com/lists/members?id=165933
    $mc_onhold_list_id = '31cfca9bfd';

    $rawData = file_get_contents("php://input");
    $jsonData = json_decode($rawData);

    // $order_id = '77141'; // for debugging proposes 

    if ($order_id) {
        @$jsonData->id = $order_id;
        wh_log("Manually process order: " . @$jsonData->arg);
    }

    $order_id = ($jsonData->id) ? $jsonData->id : @$jsonData->arg;

    if (!isset($order_id)) {
            return $API->return_error('route_woocommerce_webhooks', 'Error identifying order id!', $jsonData);
    }
 
    wh_log("Processing order: " . @$order_id);

    $order = $API->request("GET", "/wc/v3/orders/" . $order_id . "?");

    if (!isset($order->id)) {
        return $API->return_error('route_woocommerce_webhooks', 'Error retrieving order data', $order);
    }

    // CAMPANHA: Quem compra uma Plano, ganha de presente um curso gratis. 
    // TODO campaign_plano: função ainda em teste
    // $MTH->campaign_plano();

    list($mc_array, $order_payment_method) = $MTH->prepare_data_to_mailchimp($order);
    
    $is_onhold_list = $MTH->is_onhold_list($order, @$order_payment_method);
    
    $mc_list_id = ($is_onhold_list) ? $mc_onhold_list_id : $mc_customers_list_id;

    wh_log("" . $order->billing->first_name. " ". $order->billing->last_name ." | " . $order->billing->email . " | Cliente: ". $mc_array['merge_fields']['CLIENTE'] . " | ". $mc_array['merge_fields']['SKU'] . " | " . $mc_array['merge_fields']['FORMA_PAGM'] . " | $order->status | $mc_list_id");
    
    $mc_result = $MailChimp->post("lists/$mc_list_id/members", $mc_array);
    
    $order_boleto_completed = ($order_payment_method == 'Boleto' and $order->status == 'completed');
    $member_subscribed = ($mc_result['status'] == 'subscribed');
    if ($member_subscribed) {

        ($order_boleto_completed) ? $MailChimp->deletePermanent($mc_onhold_list_id, $mc_array) : false ;

        return $API->return_success("route_woocommerce_webhooks", "Success subscribed to Mailchimp!");
    }
    
    $member_exists = ($mc_result['title'] == 'Member Exists');
    if ($member_exists) {

        ($order_boleto_completed) ? $MailChimp->deletePermanent($mc_onhold_list_id, $mc_array) : false ;

        return $MailChimp->member_exist($mc_result, $order->billing->email, $mc_list_id, $mc_array);
    }

    if (!$member_exists and !$member_subscribed) {
        return $API->return_error('route_woocommerce_webhooks', 'Error on subscribing to Mailchimp. ', $mc_result);
    }
}