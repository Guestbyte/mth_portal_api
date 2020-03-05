<?php
require 'lib/route_mailchimp_subscribe.php';
require 'lib/route_woocommerce_webhooks.php';
require 'lib/route_woocommerce_webhooks_bulk.php';
require 'lib/mth_create_coupom.php';
require 'lib/MTH_API.php';
require 'lib/WP_API.php';
require 'lib/MTH_get_jwt_token.php';
require 'lib/mailchimp_get_segments.php';
require 'lib/mailchimp_create_tags.php';
require 'lib/mailchimp_add_member_to_tag.php';
require 'lib/mailchimp_subscribe.php';

/**
 * Undocumented function
 *
 * @param integer $id
 * @return void
 */
function get_paying_customer(int $id)
{
    $result = WP_API("GET", "/wc/v3/customers/$id?");
    return $result->is_paying_customer;
}

/**
 * Write message in log file 
 *
 * @param string $msg
 * @return void
 */
function wh_log(string $msg)
{
    global $log;
    $log->warning($msg);
}

/**
 * Return success message routine
 *
 * @param string $name
 * @param string $status
 * @param string $data
 * @return void
 */
function return_success(string $name, string $status, $data = '')
{
    $return['name'] = $name;
    $return['status'] = $status;
    $return['data'] = $data;
    wh_log("$name: $status\n" . print_r($data, true));
    header('HTTP/1.1 200 OK');
    return json_encode($return, JSON_UNESCAPED_UNICODE);
}

/**
 * Return error message routine
 *
 * @param string $name Error name 
 * @param string $description Error description
 * @param string $data Optional. Detailed data
 * @return void
 */
function return_error(string $name, string $description, $data = '')
{
    $return['name'] = $name;
    $return['description'] = $description;
    $return['data'] = $data ;
    wh_log("$name: $description\n" . print_r($data, true));
    header('HTTP/1.1 400 Bad Request');
    return json_encode($return, JSON_UNESCAPED_UNICODE);
}