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

    $coupom = mth_create_coupom($code, $percent, $date_expires, $product_categories, $use_limit, $description);

    if (isset($coupom->code)) {
        wh_log("Coupom created: " . $coupom->code);
        $mc_array['merge_fields']['CUPOM_PRES'] = $coupom->code;
    } else {
        wh_log("Error create new coupom: \n" . print_r($coupom, true));
        $return['error_coupom'] = 'Error create new coupom.';
    }
}