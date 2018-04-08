<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

$full = elgg_extract('full_view', $vars, false);

$entity = elgg_extract('entity', $vars, false);
if (!elgg_instanceof($entity, 'object', AgoraInterest::SUBTYPE)) {
    return;
}

$ad = $entity->getAd();
if (!elgg_instanceof($ad, 'object', Agora::SUBTYPE)) {
    return;
}

if ($full) {
    $potential = get_user($entity->int_buyer_guid);
    
    $render .= elgg_view('object/agora/feature', [
        'label' => elgg_echo('agora:request:user'), 
        'text' => elgg_view('output/url', [
            'href' => elgg_normalize_url("profile/{$potential->username}"),
            'text' => $potential->username,
        ]),
    ]);
    
    $render .= elgg_view('object/agora/feature', [
        'label' => elgg_echo('agora:request:date'), 
        'text' => date("Y-m-d H:i:s", $entity->time_created),
    ]);
    
    $status = AgoraOptions::getAdUserInterestStatus($entity->int_status);
    if ($status) {
        $render .= elgg_view('object/agora/feature', [
            'label' => elgg_echo('agora:request:status'), 
            'text' => $status,
        ]);
    }
    
    $render .= elgg_view('object/agora/feature', [
        'label' => elgg_echo('Message'), 
        'text' => elgg_view('output/url', array(
            'href' => elgg_normalize_url("messages/read/{$entity->int_message_guid}"),
            'text' => elgg_echo('agora:request:read_message'),
            'is_trusted' => true,
        )),
    ]);
   
    // show buttons only if status is not accepted or rejected and also if there are available products
    if ($entity->int_status == AgoraOptions::INTEREST && ($entity->howmany > 0 || !is_numeric($entity->howmany))) {
        $vars = array('interest_guid' => $entity->guid);

        // set accepted form
        $form_vars_set_accepted = array('name' => 'set_accepted', 'enctype' => 'multipart/form-data');
        $set_accepted_form = elgg_view_form('agora/set_accepted', $form_vars_set_accepted, $vars);
        $set_accepted_button = elgg_format_element('div', ['class' => 'interest_forms'], $set_accepted_form);

        // set rejected form
        $form_vars_set_rejected = array('name' => 'set_rejected', 'enctype' => 'multipart/form-data');
        $set_rejected_form = elgg_view_form('agora/set_rejected', $form_vars_set_rejected, $vars);
        $set_rejected_button = elgg_format_element('div', ['class' => 'interest_forms'], $set_rejected_form);

        $render .= elgg_view('object/agora/feature', [
            'label' => elgg_echo('agora:request:action'), 
            'text' => $set_accepted_button . $set_rejected_button,
        ]);
    }
    
    echo elgg_format_element('div', ['class' => 'interest_unit'], $render);
}
