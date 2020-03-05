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

    $order_id = '69126';

    if ($order_id) {
        $jsonData->id = $order_id;
        wh_log("Manually process order: " . $jsonData->arg);
    }

    $order_id = ($jsonData->id) ? $jsonData->id : $jsonData->arg;

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

    $is_paying_customer = get_paying_customer($order->customer_id);
    $order_zero_cart = ($order->total == '0.00');
    $order_completed = ($order->status == 'completed');
    $is_client = ($order_completed and ($is_paying_customer and !$order_zero_cart));
    $mc_array['merge_fields']['CLIENTE'] = ($is_client) ? 'SIM' : 'NAO';
    
    // return json_encode($mc_array, JSON_UNESCAPED_UNICODE);
    $member_tags = [];
    foreach ($order->line_items as $item) {
        unset($product);
        $mc_nome_curso = $item->name;
        $mc_sku_curso = $item->sku;
        $product = WP_API("GET", "/wc/v3/products/" . $item->product_id . "?");

        if (!isset($product->id)) {
            wh_log("Error retrieving product data: \n" . print_r($product, true));
            $return['error_product'] = 'Error on retrieving order data.';
            // header('HTTP/1.1 500 Internal Server Error');
            continue;
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
    // Esta funÃ§Ã£o identifica se o produto comprado Ã© um dos Planos, cria um cupom
    // if (@$mth_campanha_plano) {
    //     wh_log("CAMPANHA: Compre Plano ganhe Curso...");
    //     $chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVXYZ';
    //     $code = 'MTH_' . substr(str_shuffle($chars), 0, 3);
    //     $percent = 100;
    //     $date_expires = '2020-01-31';
    //     $product_categories = 43; // Curso 10h
    //     $use_limit = 1; // quantas vezes pode ser usado
    //     $description = 'API: Campanha Compre Plano ganhe Curso. Cliente: ' . $order->billing->email;

    //     $coupom = mth_create_coupom($code, $percent, $date_expires, $product_categories, $use_limit, $description);

    //     if (isset($coupom->code)) {
    //         wh_log("Coupom created: " . $coupom->code);
    //         $mc_array['merge_fields']['CUPOM_PRES'] = $coupom->code;
    //     } else {
    //         wh_log("Error create new coupom: \n" . print_r($coupom, true));
    //         $return['error_coupom'] = 'Error create new coupom.';
    //     }
    // }

    $order_onhold = ($order->status == 'on-hold');
    $order_pending = ($order->status == 'pending');
    $order_boleto = ($order_payment_method == 'Boleto');

    $order_onhold_boleto = ($order_onhold and $order_boleto);
    $order_pending_boleto = ($order_pending and $order_boleto);

    $mc_list_id = ($order_onhold_boleto or $order_pending_boleto) ? $mc_onhold_list_id : $mc_customers_list_id;

    if ($order_onhold_boleto) {
        wh_log("Order status: $order->status | Payment method: $order_payment_method. Subscribing on diferent audience: $mc_list_id");
    }

    $mc_result = $MailChimp->post("lists/$mc_list_id/members", $mc_array);

    $member_subscribed = ($mc_result['status'] == 'subscribed');
    if ($member_subscribed) {
        wh_log("Success subscribed to Mailchimp!");
        header('HTTP/1.1 200 OK');
    }

    $member_exists = ($mc_result['title'] == 'Member Exists');
    if ($member_exists) {
        //TODO Refatorar para funções globais

        wh_log($mc_result['title'] . ": Trying to update data...");

        $subscriber_hash = md5($order->billing->email);
        $put_result = $MailChimp->put("lists/$mc_list_id/members/$subscriber_hash", $mc_array);

        if ($put_result['status'] !== 'subscribed') {
            wh_log("Mailchimp Error: \n" . print_r($put_result, true));
            wh_log("dump array posted on update: \n" . print_r($mc_array, true));
            $return['error_mailchimp'] = 'Error on subscribing to Mailchimp.';
            $return['return'] = $put_result;
            // header('HTTP/1.1 500 Internal Server Error');
            return json_encode($return, JSON_UNESCAPED_UNICODE);
        }

        wh_log("Member data updated, tryng to update TAGs...");

        $mc_results = $MailChimp->get("lists/$mc_list_id/segments?count=1000");

        $mc_results_no_statics = array_filter($mc_results['segments'], function ($v, $k) {
            return $v['type'] == 'static';
        }, ARRAY_FILTER_USE_BOTH);

        $mc_list_tags_names = [];
        foreach ($mc_results_no_statics as $key) {
            array_push($mc_list_tags_names, $key['name']);
        }

        $tags_to_create_on_list = array_diff($mc_array['tags'], $mc_list_tags_names);

        foreach ($tags_to_create_on_list as $tag) {
            wh_log("Creating a TAG on list: " . $tag);

            $mc_array['name'] = $tag;
            $mc_array['static_segment'] = [];
            $mc_result_add_tag_to_list = $MailChimp->post("lists/$mc_list_id/segments/", $mc_array);

            if ($mc_result_add_tag_to_list['name'] !== $tag) {
                wh_log("Mailchimp Error: \n" . print_r($put_result, true));
                wh_log("dump array posted on update: \n" . print_r($mc_array, true));
                $return['error_mailchimp'] = 'Error on subscribing to Mailchimp.';
                $return['return'] = $put_result;
                // header('HTTP/1.1 500 Internal Server Error');
                return json_encode($return, JSON_UNESCAPED_UNICODE);
            }
            wh_log("TAG created: " . $tag);
            sleep(2);
        }

        $mc_list_tags_names_ids = [];
        foreach ($mc_results_no_statics as $key) {
            $mc_list_tags_names_ids[$key['id']] = $key['name'];
        }

        foreach ($mc_array['tags'] as $member_tag) {
            $tag_id = array_search($member_tag, $mc_list_tags_names_ids);
            if (!isset($tag_id)) {
                continue;
            }

            $data['members_to_add'] = [$order->billing->email];
            $mc_result_add_member_to_tag = $MailChimp->post("lists/$mc_list_id/segments/" . $tag_id, $data);

            if ($mc_result_add_member_to_tag['members_added'][0]['status'] == 'subscribed') {
                wh_log("Success on update TAG: $member_tag");
            } else {
                wh_log("Error on update tag $member_tag : " . $mc_result_add_member_to_tag);
            }

            unset($tag_id);
        }

        $return['status'] = '200';
        // wh_log("Success updated to Mailchimp!\n" . print_r($put_result, true));
        wh_log("Success updated to Mailchimp!");
        wh_log('------------------[ route_woocommerce_webhooks ]------------------ END');
        return json_encode($put_result, JSON_UNESCAPED_UNICODE);
    }

    if (!$member_exists and !$member_subscribed) {
        wh_log("Mailchimp Error: \n" . print_r($mc_result, true));
        wh_log("dump array posted: \n" . print_r($mc_array, true));
        $return['error_mailchimp'] = 'Error on subscribing to Mailchimp.';
        // header('HTTP/1.1 500 Internal Server Error');
        return json_encode($mc_result, JSON_UNESCAPED_UNICODE);
    }

    $return['status'] = '200';
    wh_log('------------------[ route_woocommerce_webhooks ]------------------ END');
    return json_encode($return, JSON_UNESCAPED_UNICODE);
}