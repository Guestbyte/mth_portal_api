<?php 

/**
 * Undocumented function
 *
 * @param [type] $mc_result
 * @param string $email
 * @param string $mc_list_id
 * @param array $mc_array
 * @return void
 */
function mailchimp_member_exist($mc_result, string $email, string $mc_list_id, array $mc_array) {

    global $MailChimp;
    global $API;

    wh_log($mc_result['title'] . ": Trying to update data...");

    $subscriber_hash = md5($email);
    $put_result = $MailChimp->put("lists/$mc_list_id/members/$subscriber_hash", $mc_array);

    if ($put_result['status'] !== 'subscribed') {
            return $API->return_error('route_woocommerce_webhooks', 'Error on subscribing to Mailchimp. ', $put_result);
    }

    list($mc_list_tags_names_ids, $mc_list_tags_names) = mailchimp_get_segments($mc_list_id);

    $tags_to_create_on_list = array_diff($mc_array['tags'], $mc_list_tags_names);

    mailchimp_create_tags($tags_to_create_on_list, $mc_list_id);

    mailchimp_add_member_to_tag($mc_list_tags_names_ids, $mc_list_id, $mc_array);

    return return_success("Mailchimp Subscribe", "updated", $put_result);
}