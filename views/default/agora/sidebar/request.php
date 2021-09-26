<?php
/**
 * Elgg Agora Classifieds plugin
 * @package Agora
 */

use Agora\AgoraOptions;

$entity = elgg_extract('entity', $vars);

// be interested form
if (
    elgg_is_logged_in() 
    && elgg_is_active_plugin("messages") 
    && AgoraOptions::canMembersSendPrivateMessage() 
    && !$soldout
    && !(elgg_get_logged_in_user_guid() == $entity->owner_guid)
    && $entity instanceof Agora
) {
    $pmbutton = elgg_view('output/url', [
        'name' => 'reply',
        'class' => 'elgg-button elgg-button-action',
        'rel' => 'toggle',
        'href' => '#interested-in-form',
        'text' => elgg_echo('agora:be_interested'),
        'icon' => 'hand-point-up',
    ]);
    $output = elgg_format_element('div', ['class' => 'pm'], $pmbutton);
    
    $form_params = [
        'id' => 'interested-in-form',
        'class' => 'hidden mtl',
    ];
    $body_params = [
        'classified_guid' => $entity->guid,
        'recipient_guid' => $entity->owner_guid,
        'subject' => elgg_echo("agora:be_interested:ad_message_subject", [$entity->title]),
    ];
    $output .= elgg_view_form('agora/be_interested', $form_params, $body_params);

    echo $output;
}