<?php 
use \DrewM\MailChimp\MailChimp;

/**
 * Undocumented class
 */
class MTH_Mailchimp extends MailChimp {

    /**
     * Undocumented function
     *
     * @param array $tags_to_create_on_list
     * @param string $mc_list_id
     * @return void
     */
  function create_tags(array $tags_to_create_on_list, string $mc_list_id)
    {
        global $API;

        foreach ($tags_to_create_on_list as $tag) {

            $mc_array['name'] = $tag;
            $mc_array['static_segment'] = [];

            $mc_result_add_tag_to_list = $this->post("lists/$mc_list_id/segments/", $mc_array);

            $error_on_add_tag = ($mc_result_add_tag_to_list['name'] !== $tag);
            if ($error_on_add_tag) {
                return $API->return_error('Mailchimp Subscribe', 'Error on update tag!', $mc_result_add_tag_to_list);
            }
            wh_log("TAG created: " . $tag);
        }
    }

    /**
     * Undocumented function
     *
     * @param array $mc_list_tags_names_ids
     * @param string $mc_list_id
     * @param array $mc_array
     * @return void
     */
    function add_member_to_tag(array $mc_list_tags_names_ids, string $mc_list_id, array $mc_array)
    {
        global $API;

        $data['members_to_add'] = [$mc_array['email_address']];

        foreach ($mc_array['tags'] as $member_tag) {

            $tag_id = array_search($member_tag, $mc_list_tags_names_ids);

            $mc_result_add_member_to_tag = $this->post("lists/$mc_list_id/segments/" . $tag_id, $data);

            @$tag_exist = (strpos($mc_result_add_member_to_tag['errors'][0]['error'], "already exist"));
            @$tag_added = ($mc_result_add_member_to_tag['members_added'][0]['status'] == 'subscribed');

            if (!$tag_exist and !$tag_added) {
                return $API->return_error('Mailchimp Subscribe', 'Error on add member to tag!', $mc_result_add_member_to_tag);
            }
        }
    }


    /**
     * Undocumented function
     *
     * @param string $mc_list_id
     * @return array 
     */
    function get_segments(string $mc_list_id)
    {
        global $API;

        $segments = $this->get("lists/$mc_list_id/segments?count=1000");

        $error_get_segments = (is_array($segments['segments']));
        if (!$error_get_segments) {
            return $API->return_error('Mailchimp Subscribe', 'Error on get segments!', $segments);
        }

        $segments_no_statics = array_filter($segments['segments'], function ($v, $k) {
            return $v['type'] == 'static';
        }, ARRAY_FILTER_USE_BOTH);

        $mc_list_tags_names_ids = [];
        foreach ($segments_no_statics as $key) {
            $mc_list_tags_names_ids[$key['id']] = $key['name'];
        }

        $mc_list_tags_names = [];
        foreach ($segments_no_statics as $key) {
            array_push($mc_list_tags_names, $key['name']);
        }

        return array($mc_list_tags_names_ids, $mc_list_tags_names);
    }

    /**
     * Undocumented function
     *
     * @param [type] $mc_result
     * @param string $email
     * @param string $mc_list_id
     * @param array $mc_array
     * @return void
     */
    function member_exist($mc_result, string $email, string $mc_list_id, array $mc_array) {

        global $API;

        wh_log($mc_result['title'] . ": Trying to update data...");

        $subscriber_hash = md5($email);
        $put_result = $this->put("lists/$mc_list_id/members/$subscriber_hash", $mc_array);

        if ($put_result['status'] !== 'subscribed') {
                return $API->return_error('route_woocommerce_webhooks', 'Error on subscribing to Mailchimp. ', $put_result);
        }

        list($mc_list_tags_names_ids, $mc_list_tags_names) = $this->get_segments($mc_list_id);

        $tags_to_create_on_list = array_diff($mc_array['tags'], $mc_list_tags_names);

        $this->create_tags($tags_to_create_on_list, $mc_list_id);

        $this->add_member_to_tag($mc_list_tags_names_ids, $mc_list_id, $mc_array);

        return $API->return_success("Mailchimp Subscribe", "updated", $put_result);
    }

    /**
 * Undocumented function
 *
 * @param string $mc_list_id
 * @param array $mc_array
 * @return void
 */
function subscribe(string $mc_list_id, array $mc_array)
{
    global $MailChimp;
    global $API;

    $status_options = array('subscribed', 'unsubscribe');
    $valid_status = (in_array($mc_array['status'], $status_options));
    $valid_email = (filter_var($mc_array['email_address'], FILTER_VALIDATE_EMAIL));

    $params_valid = ($valid_email and isset($mc_list_id) and $valid_status);
    if (!$params_valid) {
        return $API->return_error('Mailchimp Subscribe', 'Params passed is not valid!', $mc_array);
    }

    $result = $MailChimp->post("lists/$mc_list_id/members", $mc_array);

    $success_subscribe = ($result['status'] == 'subscribed');
    if ($success_subscribe) {
        return $API->return_success("Mailchimp Subscribe", "subscribed", $result);
    }

    $member_exists = ($result['title'] == 'Member Exists');
    if ($member_exists) {

        $subscriber_hash = md5($mc_array['email_address']);
        $result = $MailChimp->put("lists/$mc_list_id/members/$subscriber_hash", $mc_array);

        $update_error = ($result['status'] !== 'subscribed');
        if ($update_error) {
            return $API->return_error('Mailchimp Subscribe', 'Error on update!', $result);
        }

        list($mc_list_tags_names_ids, $mc_list_tags_names) = $MailChimp->get_segments($mc_list_id);

        $tags_to_create_on_list = array_diff($mc_array['tags'], $mc_list_tags_names);

        $MailChimp->create_tags($tags_to_create_on_list, $mc_list_id);

        $MailChimp->add_member_to_tag($mc_list_tags_names_ids, $mc_list_id, $mc_array);

        return $API->return_success("Mailchimp Subscribe", "updated", $result);
    }

    return $API->return_error('Mailchimp Subscribe', 'Internal error: Mailchimp response not valid!', $result);
}
}