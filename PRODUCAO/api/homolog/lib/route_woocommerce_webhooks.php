<?php
function route_woocommerce_webhooks($order_id = false)
{
    //WIP Sincronizar status do pedido com as listas do MC
    
    global $MailChimp;
    wh_log('------------------[ route_woocommerce_webhooks ]------------------');
    
    $mc_customers_list_id = '803e6a1581'; // CLIENTES MATHEMA ONLINE II : https://us16.admin.mailchimp.com/lists/members?id=165933
    $mc_onhold_list_id = '31cfca9bfd';

    $rawData = file_get_contents("php://input");
    $jsonData = json_decode($rawData);

    // $order_id = '69126'; // for debugging proposes 

    if ($order_id) {
        @$jsonData->id = $order_id;
        wh_log("Manually process order: " . @$jsonData->arg);
    }

    $order_id = ($jsonData->id) ? $jsonData->id : @$jsonData->arg;

    if (!isset($order_id)) {
            return return_error('route_woocommerce_webhooks', 'Error identifying order id!', $jsonData);
    }
 
    wh_log("Processing order: " . @$order_id);

    $order = WP_API("GET", "/wc/v3/orders/" . $order_id . "?");
    if (!isset($order->id)) {
        return return_error('route_woocommerce_webhooks', 'Error retrieving order data', $order);
    }

    wh_log("Subscribing on MailChimp: " . $order_id);

    $mc_array['status'] = 'subscribed';
    $mc_array['email_address'] = $order->billing->email;
    $mc_array['merge_fields']['NOME'] = $order->billing->first_name;
    $mc_array['merge_fields']['SOBRENOME'] = $order->billing->last_name;
    $mc_array['merge_fields']['COMPRA'] = $order->date_created;
    ($order->date_completed) ? $mc_array['merge_fields']['PAGAMENTO'] = $order->date_completed : false;
    $mc_array['merge_fields']['SITUACAO'] = $order->status;
    $mc_array['merge_fields']['ENDERECO'] = $order->billing->address_1;
    $mc_array['merge_fields']['BAIRRO'] = $order->billing->neighborhood;
    $mc_array['merge_fields']['CIDADE'] = $order->billing->city;
    $mc_array['merge_fields']['ESTADO'] = $order->billing->state;
    $mc_array['merge_fields']['CEP'] = $order->billing->postcode;
    $mc_array['merge_fields']['CUPOM'] = (isset($order->coupon_lines[0]->code)) ? $order->coupon_lines[0]->code : '';
    $mc_array['merge_fields']['TELEFONE'] = (isset($order->billing->phone)) ? $order->billing->phone : '';
    $mc_array['merge_fields']['CELULAR'] = $order->billing->cellphone;
    $mc_array['merge_fields']['CPF'] = $order->billing->cpf;
    $mc_array['merge_fields']['CNPJ'] = $order->billing->cnpj;
    $mc_array['merge_fields']['TIPOCLIENT'] = $order->billing->persontype;
    $mc_array['merge_fields']['ORDER_ID'] = $order->id;
    // $has_coupon = (isset($order->coupon_lines[0]->code));

    $is_client = is_client($order);
    $mc_array['merge_fields']['CLIENTE'] = ($is_client) ? 'SIM' : 'NAO';
    
    $member_tags = [];
    foreach ($order->line_items as $item) {
        unset($product);
        $mc_nome_curso = $item->name;
        $mc_sku_curso = $item->sku;
        $product = WP_API("GET", "/wc/v3/products/" . $item->product_id . "?");

        if (!isset($product->id)) {
              return return_error('route_woocommerce_webhooks', 'Error retrieving product data: ', $product);
        }

        array_push($member_tags, $item->sku);

        foreach ($product->categories as $categories) {
            switch ($categories->id) {
                case "196": // Curso gratis
                    array_push($member_tags, "CURSO");
                    array_push($member_tags, "GRATIS");
                    break;
                case "43": // curso de 10h
                    array_push($member_tags, "CURSO");
                    array_push($member_tags, "10H");
                    break;
                case "193": // curso de 16h
                    array_push($member_tags, "CURSO");
                    array_push($member_tags, "16H");
                    break;
                case "74": // trilha
                    array_push($member_tags, "TRILHA");
                    array_push($member_tags, "40H");
                    break;
                case "224": // Plano ensino infantil anos iniciais
                    array_push($member_tags, "PLANO_EIAI");
                    break;
                case "225": // Plano anos finais ensino medio
                    array_push($member_tags, "PLANO_AFEM");
                    break;
                case "223": // Plano premium
                    array_push($member_tags, "PLANO_PREMIUM");
                    break;
            }
        }
    }
    $mc_array['merge_fields']['CURSO'] = $mc_nome_curso;
    $mc_array['merge_fields']['SKU'] = $mc_sku_curso;
    $mc_array['tags'] = $member_tags;

    foreach ($order->meta_data as $item) {
        switch ($item->key) {
            case "Tipo de pagamento": // Curso gratis
                $mc_array['merge_fields']['FORMA_PAGM'] = $item->value;
                $order_payment_method = $item->value;
                break;
        }
    }

    // CAMPANHA: Quem compra uma Plano, ganha de presente um curso gratis. 
    // TODO mth_campaign_plano: função ainda em teste
    // mth_campaign_plano();
    
    $mc_list_id = (mth_is_onhold_list($order, @$order_payment_method)) ? $mc_onhold_list_id : $mc_customers_list_id;

    $mc_result = $MailChimp->post("lists/$mc_list_id/members", $mc_array);

    $member_subscribed = ($mc_result['status'] == 'subscribed');
    if ($member_subscribed) {
        return return_success("route_woocommerce_webhooks", "Success subscribed to Mailchimp!", $mc_result);
    }

    $member_exists = ($mc_result['title'] == 'Member Exists');
    if ($member_exists) {
        return mailchimp_member_exist($mc_result, $order->billing->email, $mc_list_id, $mc_array);
    }

    if (!$member_exists and !$member_subscribed) {
        return return_error('route_woocommerce_webhooks', 'Error on subscribing to Mailchimp. ', $mc_result);
    }
}