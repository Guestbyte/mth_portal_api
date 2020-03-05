<?php
/**
 * Undocumented function
 *
 * @param array $tags_to_create_on_list
 * @param string $mc_list_id
 * @return void
 */
function mailchimp_create_tags(array $tags_to_create_on_list, string $mc_list_id)
{
    global $MailChimp;

    foreach ($tags_to_create_on_list as $tag) {

        $mc_array['name'] = $tag;
        $mc_array['static_segment'] = [];

        $mc_result_add_tag_to_list = $MailChimp->post("lists/$mc_list_id/segments/", $mc_array);

        $error_on_add_tag = ($mc_result_add_tag_to_list['name'] !== $tag);
        if ($error_on_add_tag) {
            return return_error('Mailchimp Subscribe', 'Error on update tag!', $mc_result_add_tag_to_list);
        }
    }
}