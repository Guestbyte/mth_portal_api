<?php

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
        // header('HTTP/1.1 400 Bad Request');
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
            // header('HTTP/1.1 500 Internal Server Error');
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
    wh_log('------------------[ route_woocommerce_webhooks ]------------------');
    global $MailChimp;
    $mc_customers_list_id = '803e6a1581'; // CLIENTES MATHEMA ONLINE II : https://us16.admin.mailchimp.com/lists/members?id=165933
    $mc_onhold_list_id = '31cfca9bfd'; 

    $rawData = file_get_contents("php://input");
    $jsonData = json_decode($rawData);

    // $order_id = '69126';

    if ($order_id) {
        $jsonData->id = $order_id;
        wh_log("Manually process order: " . $jsonData->arg);
    }

    $order_id = ($jsonData->id) ? $jsonData->id : $jsonData->arg;

    if (!isset($order_id)) {
        wh_log("Error identifing order id!");
        $return['error_imput'] = 'Error identifing order id.';
        wh_log("error dump _input:\n" . print_r(json_decode($rawData), true));
        // header('HTTP/1.1 400 Bad Request');
        return json_encode($return, JSON_UNESCAPED_UNICODE);
    }

    wh_log("Proccessing order: " . @$order_id);

    $order = WP_API("GET", "/wc/v3/orders/" . $order_id . "?");

    if (!isset($order->id)) {
        wh_log("Error retriving order data: \n" . print_r($order, true));
        $return['error_order'] = 'Error on retriving order data.';
        // header('HTTP/1.1 500 Internal Server Error');
        return json_encode($return, JSON_UNESCAPED_UNICODE);
    }

    wh_log("Subscribing on MailChimp: " . $order_id);

    $mc_array['status'] = 'subscribed';
    $mc_array['email_address'] = $order->billing->email;
    $mc_array['merge_fields']['NOME'] = $order->billing->first_name;
    $mc_array['merge_fields']['SOBRENOME'] = $order->billing->last_name;
    $mc_array['merge_fields']['COMPRA'] = $order->date_created;
    ($order->date_completed) ? $mc_array['merge_fields']['PAGAMENTO'] = $order->date_completed : false ;
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
    $is_paying_customer = get_paying_customer($order->customer_id);;
    $order_completed = ($order->status == 'completed');
    $is_client = ($is_paying_customer or $order_completed);
    $mc_array['merge_fields']['CLIENTE'] = ($is_client) ? 'SIM' : 'NAO';

    $member_tags = [];
    foreach ($order->line_items as $item) {
        unset($product);
        $mc_nome_curso = $item->name;
        $mc_sku_curso = $item->sku;
        $product = WP_API("GET", "/wc/v3/products/" . $item->product_id . "?");

        if (!isset($product->id)) {
            wh_log("Error retriving product data: \n" . print_r($product, true));
            $return['error_product'] = 'Error on retriving order data.';
            // header('HTTP/1.1 500 Internal Server Error');
            break;
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

    // return json_encode($mc_array, JSON_UNESCAPED_UNICODE);

    $order_onhold = ($order->status == 'on-hold');
    $order_pending = ($order->status == 'pending');
    $order_boleto = ($order_payment_method == 'Boleto');   

    $order_onhold_boleto = ($order_onhold and $order_boleto);
    $order_pending_boleto = ($order_pending and $order_boleto);

    $mc_list_id = ($order_onhold_boleto or $order_pending_boleto) ? $mc_onhold_list_id : $mc_customers_list_id ;

    if ($order_onhold_boleto){
        wh_log("Order status: $order->status | Payment method: $order_payment_method. Subscribing on diferent audience: $mc_list_id");
    }

    // return json_encode($mc_list_id, JSON_UNESCAPED_UNICODE);
    
    $mc_result = $MailChimp->post("lists/$mc_list_id/members", $mc_array);

    $member_subscribed = ($mc_result['status'] == 'subscribed');
    if ($member_subscribed) {
        wh_log("Success subscribed to Mailchimp!");
        header('HTTP/1.1 200 OK');
    }

    $member_exists = ($mc_result['title'] == 'Member Exists');
    if ($member_exists) {
        wh_log($mc_result['title'] . ": Tryng to update data...");

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

function route_woocommerce_webhooks_bulk($status = 'completed', $after = '2020-02-01T00:00:00', $before = '2020-03-01T00:00:00')
{

    wh_log("Bulk process started: Status=$status, After: $after, Before: $before ");
    wh_log("Gethering orders... ");

    $orders = json_decode(WP_API("GET", "/wc/v3/orders/?order=asc&status[0]=$status&after=$after&before=$before&"));

    if (!isset($orders)) {
        wh_log("Error retriving order data: \n" . print_r($orders, true));
        $return['error_order'] = 'Error on retriving order data.';
        // header('HTTP/1.1 500 Internal Server Error');
        return json_encode($return, JSON_UNESCAPED_UNICODE);
    }

    foreach ($orders as $order) {
        // MTH_API("GET", "/woocommerce/webhooks/$order->id");
        route_woocommerce_webhooks($order->id);
    }

    // return $orders;
    $return['status'] = '200';
    wh_log('------------------[ route_woocommerce_webhooks_bulk ]------------------ ');
    return json_encode($return, JSON_UNESCAPED_UNICODE);
}

function get_paying_customer($id)
{
    $result = WP_API("GET", "/wc/v3/customers/$id?");
    return $result->is_paying_customer;
}

function wh_log($msg)
{
    global $log;
    $log->warning($msg);
    // $logfile = 'api.log';
    // file_put_contents($logfile, date("Y-m-d H:i:s") . " | " . $msg . "\n", FILE_APPEND);
}

function WP_API($method, $route, $data = false)
{
    $pagecnt = 1;
    $itemcnt = 0;
    $itemassoc = array();
    $base_url = "https://mathema.com.br/wp-json";
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
    $base_url = "https://mathema.com.br/api/v2";

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
        // header('HTTP/1.1 400 Bad Request');
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