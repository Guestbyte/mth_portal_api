<?php


function route_woocommerce_subscriptions($status = 'active')
{  
    global $API;
    wh_log('------------------[ route_woocommerce_subscriptions ]------------------ ');

    $subscriptions = json_decode($API->request("GET", "/wc/v1/subscriptions?status=active&order=asc&"));

    // echo "subscription_id,order_id,date_created,customer_id,total,date_completed,start_date,end_date\n";

    $return = [];
    foreach ($subscriptions as $subscription) {
        $end = date("m/Y", strtotime($subscription->end_date));
        $count = $return[$end]; 
        $count++;
        $return[$end] = $count; 

    //    echo $subscription->id . ","
    //    . $subscription->parent_id. ","
    //    . $subscription->date_created. ","
    //    . $subscription->customer_id. ","
    //    . $subscription->total. ","
    //    . $subscription->date_completed. ","
    //    . $subscription->start_date. ","
    //    . $end. "\n"
    //    ;

    //    echo(date("d/m/y", strtotime($subscription->end_date))."\n");
    }


    $return = [];
    foreach ($subscriptions as $subscription) {
        $end = date("m/Y", strtotime($subscription->end_date));
        $count = $return[$end]; 
        $count++;
        $return[$end] = $count; 
    }

    // $return['status'] = '200';
    wh_log('------------------[ route_woocommerce_subscriptions ]------------------ ');
    return json_encode($return, JSON_UNESCAPED_UNICODE);
}