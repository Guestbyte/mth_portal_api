<?php 

/**
 * Undocumented function
 *
 * @param array $mc_list_tags_names_ids
 * @param string $mc_list_id
 * @param array $mc_array
 * @return void
 */
function mailchimp_add_member_to_tag(array $mc_list_tags_names_ids, string $mc_list_id, array $mc_array)
{
    global $MailChimp;

    $data['members_to_add'] = [$mc_array['email_address']];

    foreach ($mc_array['tags'] as $member_tag) {

        $tag_id = array_search($member_tag, $mc_list_tags_names_ids);

        $mc_result_add_member_to_tag = $MailChimp->post("lists/$mc_list_id/segments/" . $tag_id, $data);

        $tag_exist = (strpos($mc_result_add_member_to_tag['errors'][0]['error'], "already exist"));
        $tag_added = ($mc_result_add_member_to_tag['members_added'][0]['status'] == 'subscribed');

        if (!$tag_exist and !$tag_added) {
            return return_error('Mailchimp Subscribe', 'Error on add member to tag!', $mc_result_add_member_to_tag);
        }
        // return return_success("DUMP", "DUMP", $mc_result_add_member_to_tag);
    }
}