<?php
/**
 * Undocumented function
 *
 * @return void
 */
function mth_campaign_plano()
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