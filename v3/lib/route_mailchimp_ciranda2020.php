<?php

/**
 * Undocumented function
 *
 * @param array $post
 * @return void
 */
function route_mailchimp_ciranda2020()
{

    ini_set('display_startup_errors', 1);
    ini_set('display_errors', 1);
    error_reporting(-1);

    wh_log('=======[ API request - route: route_mailchimp_ciranda2020 ]=========');
    global $MailChimp;
    global $API;
    global $MTH;
    
    //pegar lista de membros
    $mc_list_id = "3e3bee8024";
    $result = $MailChimp->get("lists/$mc_list_id/members?count=1000");
    $members = $result['members'];
    
    //iterar
    $count = 0;
    foreach ($members as $member) {
        // return json_encode($member, true);
        
        if ($member['status'] !== 'subscribed') {
            continue;
        }

        // pego valores 
        $email = $member['email_address'];
        
        // gera cod cupom
        $coupon_code = $MTH->generate_coupon_code("CIR_", 6, $email);
        
        // cria cupom no ecommerce
        $percent = 18.82;
        $date_expires = "2020-06-12T00:00:00";
        $product_categories = 43; // Curso 10h
        $limit_usage_to_x_items = 1;
        $usage_limit = 0 ;
        $usage_limit_per_user = 1;
        $description = 'API: Campanha CIRANDA: Indique um Amigo. Cliente: ' . $email;
        
        $coupon = $MTH->create_coupon($coupon_code, $percent, $date_expires, $product_categories, $limit_usage_to_x_items, $usage_limit, $usage_limit_per_user, $description);

        // $coupon_created = (strtoupper($coupon->code) === strtoupper($code));
        // if (!$coupon_created) {
        //     return $API->return_error('route_mailchimp_ciranda_2020', 'Error on created coupon. ', $coupon);
        // }

        // atualiza cupom no MailChimp
        $mc_array = [];
        $mc_array['status'] = 'subscribed';
        $mc_array['email_address'] = $email;
        $mc_array['merge_fields']['CUPOM'] = $coupon_code;

        $subscriber_hash = md5($email);
        $put_result = $MailChimp->put("lists/$mc_list_id/members/$subscriber_hash", $mc_array);
        
        if ($put_result['status'] !== 'subscribed') {
            $API->return_error('route_mailchimp_ciranda_2020', 'Error on update member to Mailchimp. ', $put_result);
        }
        $count++;
        $return[$coupon_code] = $email;

        if ($count > 2) {
            return json_encode($return, true);
        }
    }
    return json_encode($return, true);
}