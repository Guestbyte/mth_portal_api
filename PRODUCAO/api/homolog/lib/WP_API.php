<?php

/**
 * Undocumented function
 *
 * @param string $method
 * @param string $route
 * @param boolean $data
 * @return void
 */
function WP_API(string $method, string $route, $data = false)
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