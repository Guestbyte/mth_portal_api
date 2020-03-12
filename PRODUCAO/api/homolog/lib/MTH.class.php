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
}