<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

use Agora\AgoraOptions;

$user = elgg_extract("user", $vars);

if (empty($user) || !$user->canEdit()) {
    echo elgg_echo("agora:usersettings:no_fornormaluseryet");
}

if (AgoraOptions::canAllUsersPostClassifieds()) {
    if (AgoraOptions::isPaypalEnabled()) {
        $agora_paypal_account = $user->getMetadata("agora_paypal_account");
        $form_body .= elgg_view_module("inline", elgg_echo("agora:usersettings:paypal_settings"), 
            elgg_format_element('div', ['style' => 'margin-top: 20px;'], elgg_view_field([
                '#type' => 'text',
                'name' => 'agora_paypal_account',
                'value' => $agora_paypal_account,
                '#label' => elgg_echo('agora:usersettings:paypal'),
                '#help' => elgg_echo('agora:usersettings:paypal:note'),
            ])
        ));            
    }
}

if (!empty($form_body)) {
    $form_footer .= elgg_view_field([
        '#type' => 'hidden',
        "name" => "user_guid", 
        "value" => $user->getGUID(),
    ]);
    $form_footer .= elgg_view_field([
        '#type' => 'submit',
        'value' => elgg_echo('save'),
    ]);
    $form_body .= elgg_format_element('div', ['class' => 'elgg-foot'], $form_footer);

    echo elgg_view("input/form", ["body" => $form_body, "action" => "action/agora/usersettings", "class" => "elgg-form-alt", 'enctype' => 'multipart/form-data']);
} else {
    echo elgg_echo("agora:usersettings:no_settings");
}
