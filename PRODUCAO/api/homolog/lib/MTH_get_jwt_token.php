<?php

/**
 * Undocumented function
 *
 * @return string
 */
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