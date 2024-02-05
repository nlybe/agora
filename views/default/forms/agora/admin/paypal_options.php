<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

use Agora\AgoraOptions;

if (!elgg_is_active_plugin('paypal_api')) {
    echo elgg_echo('agora:plugins:paypal_api:missing');
    return;
}

$plugin = elgg_get_plugin_from_id(AgoraOptions::PLUGIN_ID);
$pyn = [
    AgoraOptions::YES => elgg_echo('agora:settings:yes'),
    AgoraOptions::NO => elgg_echo('agora:settings:no'),
]; 

echo elgg_view_field([
    '#type' => 'checkbox',
    'name' => 'params[agora_paypal_enabled]',
    'default' => 'no',
    'switch' => true,
    'value' => 'yes',
    'checked' => ($plugin->agora_paypal_enabled === 'yes'), 
    '#label' => elgg_echo('agora:settings:agora_paypal_enabled'),
    '#help' => elgg_echo('agora:settings:agora_paypal_enabled:note', [$pa_setings_url]),
]);

echo elgg_view_field([
    '#type' => 'submit',
    'value' => elgg_echo('save'),
]);
