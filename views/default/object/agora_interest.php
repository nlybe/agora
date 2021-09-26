<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

use Agora\AgoraOptions;

$full = elgg_extract('full_view', $vars, false);

$entity = elgg_extract('entity', $vars, false);
if (!$entity instanceof AgoraInterest) {
    return;
}

$ad = $entity->getAd();
if (!$ad instanceof Agora) { 
    return;
}

if (!$full) { 
    return;
}


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
    'text' => elgg_get_friendly_time($entity->time_created), //date("Y-m-d H:i:s", $entity->time_created),
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
    'text' => elgg_view('output/url', [
        'href' => elgg_normalize_url("messages/read/{$entity->int_message_guid}"),
        'text' => elgg_echo('agora:request:read_message'),
        'is_trusted' => true,
    ]),
]);

// show buttons only if status is not accepted or rejected and also if there are available products
if ($entity->int_status == AgoraOptions::INTEREST && ($entity->howmany > 0 || !is_numeric($entity->howmany))) {
    $vars = ['interest_guid' => $entity->guid];

    // set accepted form
    $form_vars_set_accepted = ['name' => 'set_accepted', 'enctype' => 'multipart/form-data'];
    $set_accepted_form = elgg_view_form('agora/set_accepted', $form_vars_set_accepted, $vars);
    $set_accepted_button = elgg_format_element('div', ['class' => 'interest_forms'], $set_accepted_form);

    // set rejected form
    $form_vars_set_rejected = ['name' => 'set_rejected', 'enctype' => 'multipart/form-data'];
    $set_rejected_form = elgg_view_form('agora/set_rejected', $form_vars_set_rejected, $vars);
    $set_rejected_button = elgg_format_element('div', ['class' => 'interest_forms'], $set_rejected_form);

    $render .= elgg_view('object/agora/feature', [
        'label' => elgg_echo('agora:request:action'), 
        'text' => $set_accepted_button . $set_rejected_button,
    ]);
}

echo elgg_format_element('div', ['class' => 'interest_unit'], $render);
