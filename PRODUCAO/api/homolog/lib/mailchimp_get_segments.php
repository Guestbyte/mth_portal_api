<?php
/**
 * Undocumented function
 *
 * @param string $mc_list_id
 * @return array 
 */
function mailchimp_get_segments(string $mc_list_id)
{
    global $MailChimp;

    $segments = $MailChimp->get("lists/$mc_list_id/segments?count=1000");

    $error_get_segments = (is_array($segments['segments']));
    if (!$error_get_segments) {
        return return_error('Mailchimp Subscribe', 'Error on get segments!', $segments);
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