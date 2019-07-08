<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

$plugin = elgg_get_plugin_from_id(AgoraOptions::PLUGIN_ID);

echo elgg_view('agora/admin/tabs', ['basic_options_selected' => true]);

$pyn = array(
    AgoraOptions::YES => elgg_echo('agora:settings:yes'),
    AgoraOptions::NO => elgg_echo('agora:settings:no'),
); 

// set categories
$output .= elgg_view_field([
    '#type' => 'text',
    'name' => 'params[categories]',
    'value' => $plugin->categories,
    '#label' => elgg_echo('agora:settings:categories'),
    '#help' => elgg_echo('agora:settings:categories:note'),
]);


// set default currency
$output .= elgg_view_field([
    '#type' => 'dropdown',
    'name' => 'params[default_currency]',
    'value' => $plugin->default_currency?$plugin->default_currency:AgoraOptions::DEFAULT_CURRENCY,
    'options_values' => AgoraOptions::getAllCurrencies(),
    '#label' => elgg_echo('agora:settings:default_currency'),
    '#help' => elgg_echo('agora:settings:default_currency:note'),
]);

$output .= elgg_view_field([
    '#type' => 'dropdown',
    'name' => 'params[default_timezone]',
    'value' => $plugin->default_timezone?$plugin->default_timezone:AgoraOptions::DEFAULT_TIMEZONE,
    'options_values' => AgoraOptions::getAllTimesZones(),
    '#label' => elgg_echo('agora:settings:default_timezone'),
    '#help' => elgg_echo('agora:settings:default_timezone:note'),
]);

$output .= elgg_view_field([
    '#type' => 'dropdown',
    'name' => 'params[agora_uploaders]',
    'value' => $plugin->agora_uploaders?$plugin->agora_uploaders:AgoraOptions::UPLOADER_ALL,
    'options_values' => [
        AgoraOptions::UPLOADER_ADMINS => elgg_echo('agora:settings:agora_uploaders:admins'),
        AgoraOptions::UPLOADER_ALL => elgg_echo('agora:settings:agora_uploaders:allmembers'),
    ],
    '#label' => elgg_echo('agora:settings:agora_uploaders'),
    '#help' => elgg_echo('agora:settings:agora_uploaders:note'),
]);

$output .= elgg_view_field([
    '#type' => 'dropdown',
    'name' => 'params[multiple_ad_purchase]',
    'value' => $plugin->multiple_ad_purchase?$plugin->multiple_ad_purchase:AgoraOptions::NO,
    'options_values' => $pyn,
    '#label' => elgg_echo('agora:settings:multiple_ad_purchase'),
    '#help' => elgg_echo('agora:settings:multiple_ad_purchase:note'),
]);

$output .= elgg_view_field([
    '#type' => 'dropdown',
    'name' => 'params[html_allowed]',
    'value' => $plugin->html_allowed?$plugin->html_allowed:AgoraOptions::NO,
    'options_values' => $pyn,
    '#label' => elgg_echo('agora:settings:html_allowed'),
    '#help' => elgg_echo('agora:settings:html_allowed:note'),
]);

// set max number of inages for each ad
$output .= elgg_view_field([
    '#type' => 'text',
    'name' => 'params[max_images]',
    'value' => intval($plugin->max_images) > 0?intval($plugin->max_images):AgoraOptions::MAX_IMAGES_GALLERY,
    '#label' => elgg_echo('agora:settings:max_images'),
    '#help' => elgg_echo('agora:settings:max_images:note'),
    'style' => 'width: 60px;'
]);

// set if members can send private message to seller
$output .= elgg_view_field([
    '#type' => 'dropdown',
    'name' => 'params[send_message]',
    'value' => $plugin->send_message?$plugin->send_message:AgoraOptions::NO,
    'options_values' => $pyn,
    '#label' => elgg_echo('agora:settings:send_message'),
    '#help' => elgg_echo('agora:settings:send_message:note'),
]);


// set users to notify for each transaction
$output .= elgg_view_field([
    '#type' => 'text',
    'name' => 'params[users_to_notify]',
    'value' => $plugin->users_to_notify,
    '#label' => elgg_echo('agora:settings:users_to_notify'),
    '#help' => elgg_echo('agora:settings:users_to_notify:note'),
]);

// set terms of use
$output .= elgg_view_field([
    '#type' => 'longtext',
    'name' => 'params[terms_of_use]',
    'value' => $plugin->terms_of_use,
    '#label' => elgg_echo('agora:settings:terms_of_use'),
    '#help' => elgg_echo('agora:settings:terms_of_use:note'),
]);

echo elgg_view_module('info', elgg_echo('agora:settings:tabs:basic_options'), $output);