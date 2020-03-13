<?php


class MTH {

    function __construct() {
        // $this->baseRoute = $endpoint;
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    function get_jwt_token()
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

    /**
     * Undocumented function
     *
     * @param string $code
     * @param integer $percent
     * @param integer $date_expires
     * @param string $product_categories
     * @param integer $use_limit
     * @param string $description
     * @return void
     */
    function create_coupon(string $code, int $percent, int $date_expires = null, $product_categories = '', int $use_limit = null, string $description = '')
    {
        global $API;

        $result = $API->request("POST", "/wc/v3/coupons/?", [
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

    /**
     * Undocumented function
     *
     * @return void
     */
    function campaign_plano()
    {
        wh_log("CAMPANHA: Compre Plano ganhe Curso...");
        $chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVXYZ';
        $code = 'MTH_' . substr(str_shuffle($chars), 0, 3);
        $percent = 100;
        $date_expires = '2020-01-31';
        $product_categories = 43; // Curso 10h
        $use_limit = 1; // quantas vezes pode ser usado
        $description = 'API: Campanha Compre Plano ganhe Curso. Cliente: ' . $order->billing->email;

        $coupon = $MTH->create_coupon($code, $percent, $date_expires, $product_categories, $use_limit, $description);

        if (isset($coupon->code)) {
            wh_log("Coupon created: " . $coupon->code);
            $mc_array['merge_fields']['CUPOM_PRES'] = $coupon->code;
        } else {
            wh_log("Error create new coupon: \n" . print_r($coupon, true));
            $return['error_coupon'] = 'Error create new coupon.';
        }
    }

    /**
     * Return if Order is typed to be 
     *
     * @param [type] $order
     * @return boolean
     */
    function is_onhold_list($order, $order_payment_method) {

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

    /**
     * Check if order type is from a client
     *
     * @param [type] $order
     * @return boolean
     */
    function is_client($order) {
        
        $is_paying_customer = $this->get_paying_customer($order->customer_id);
        $order_zero_cart = ($order->total == '0.00');
        $order_completed = ($order->status == 'completed');
        $is_client = ($order_completed and ($is_paying_customer and !$order_zero_cart));

        return $is_client;
    }

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
     * Prepare return array to be send to Mailchimp post
     *
     * @param [type] $order
     * @return array
     */
    function prepare_data($order) {
        global $MTH;
        global $API;
    
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
    
        $is_client = $MTH->is_client($order);
        $mc_array['merge_fields']['CLIENTE'] = ($is_client) ? 'SIM' : 'NAO';
        
        $member_tags = [];
        foreach ($order->line_items as $item) {
            unset($product);
            $mc_nome_curso = $item->name;
            $mc_sku_curso = $item->sku;
            $product = $API->request("GET", "/wc/v3/products/" . $item->product_id . "?");
    
            if (!isset($product->id)) {
                  return $API->return_error('route_woocommerce_webhooks', 'Error retrieving product data: ', $product);
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
        return $mc_array;
    }
}