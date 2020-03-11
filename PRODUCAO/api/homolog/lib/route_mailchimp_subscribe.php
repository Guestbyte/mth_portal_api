<?php

/**
 * Undocumented function
 *
 * @param array $post
 * @return void
 */
function route_mailchimp_subscribe($post)
{
    wh_log('=======[ API request - route: route_mailchimp_subscribe ]=========');
    global $MailChimp;

    //fake post for debugging 
    // $post = array(
    //     "list_id" => "f3397d3993",
    //     "status" => "subscribed",
    //     "email_address" => "teste1102_6@mathema.com.br",
    //     "merge_fields" => [
    //         "NOME" => "Fernando TESTE",
    //         "ATUACAO" => "Ensino Fundamental",
    //         "INSTITUICA" => "mathema",
    //         "CELULAR" => "11 9999999",
    //         "N_ALUNOS" => "1 a 20",
    //         "CIDADE" => "sp",
    //         "ESTADO" => "Acre (AC)"
    //     ],
    //     "tags" => ['NOVO2', 'NOVO3', 'NOVO4']
    // );

    $data = (object) $post;
    $mc_list_id = @$data->list_id;
    $status = @$data->status;
    $email_address = @$data->email_address;

    $valid_post = (isset($mc_list_id) && isset($email_address) && isset($status));
    if (!$valid_post) {
        return return_error('Mailchimp Subscribe', 'Data posted is invalid!', $post);
    }
    wh_log("DATA: list_id: $mc_list_id, email: $email_address, status: $status !");

    $mc_array['status'] = $status;
    $mc_array['email_address'] = $email_address;

    foreach ($data->merge_fields as $key => $value) {
        $mc_array['merge_fields'][$key] = $value;
    }

    foreach ($data->tags as $key => $value) {
        $mc_array['tags'][$key] = $value;
    }

    return mailchimp_subscribe($mc_list_id, $mc_array);
}