<?php 
/**
 * Undocumented function
 *
 * @param string $mc_list_id
 * @param array $mc_array
 * @return void
 */
function mailchimp_subscribe(string $mc_list_id, array $mc_array)
{
    global $MailChimp;

    $status_options = array('subscribed', 'unsubiscribe');
    $valid_status = (in_array($mc_array['status'], $status_options));
    $valid_email = (filter_var($mc_array['email_address'], FILTER_VALIDATE_EMAIL));

    $params_valid = ($valid_email and isset($mc_list_id) and $valid_status);
    if (!$params_valid) {
        return return_error('Mailchimp Subscribe', 'Params passed is not valid!', $mc_array);
    }

    $result = $MailChimp->post("lists/$mc_list_id/members", $mc_array);

    $success_subscribe = ($result['status'] == 'subscribed');
    if ($success_subscribe) {
        return return_success("Mailchimp Subscribe", "subscribed", $result);
    }

    $member_exists = ($result['title'] == 'Member Exists');
    if ($member_exists) {

        $subscriber_hash = md5($mc_array['email_address']);
        $result = $MailChimp->put("lists/$mc_list_id/members/$subscriber_hash", $mc_array);

        $update_error = ($result['status'] !== 'subscribed');
        if ($update_error) {
            return return_error('Mailchimp Subscribe', 'Error on update!', $result);
        }

        list($mc_list_tags_names_ids, $mc_list_tags_names) = mailchimp_get_segments($mc_list_id);

        $tags_to_create_on_list = array_diff($mc_array['tags'], $mc_list_tags_names);

        mailchimp_create_tags($tags_to_create_on_list, $mc_list_id);

        mailchimp_add_member_to_tag($mc_list_tags_names_ids, $mc_list_id, $mc_array);

        return return_success("Mailchimp Subscribe", "updated", $result);
    }

    return return_error('Mailchimp Subscribe', 'Internal error: Mailchimp response not valid!', $result);
}