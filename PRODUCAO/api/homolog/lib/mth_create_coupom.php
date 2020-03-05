<?php

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
function mth_create_coupom(string $code, int $percent, int $date_expires = null, $product_categories = '', int $use_limit = null, string $description = '')
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