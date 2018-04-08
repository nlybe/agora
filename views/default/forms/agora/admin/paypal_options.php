<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

if (!elgg_is_active_plugin('paypal_api')) {
    echo elgg_echo('agora:plugins:paypal_api:missing');
    return;
}

$plugin = elgg_get_plugin_from_id(AgoraOptions::PLUGIN_ID);
$pyn = array(
    AgoraOptions::YES => elgg_echo('agora:settings:yes'),
    AgoraOptions::NO => elgg_echo('agora:settings:no'),
); 

echo elgg_view_input('dropdown', array(
    'name' => 'params[paypal_enabled]',
    'value' => $plugin->paypal_enabled?$plugin->paypal_enabled:AgoraOptions::NO,
    'options_values' => $pyn,
    'label' => elgg_echo('agora:settings:paypal_enabled'),
    'help' => elgg_echo('agora:settings:paypal_enabled:note'),
));


$pa_setings_url = elgg_view('output/url', [
    'href' => elgg_normalize_url('admin/plugin_settings/paypal_api'),
    'text' => elgg_echo('agora:settings:agora_adaptive_payments:paypal_api'),
    'target' => "_blank",
]);

// Adaptive Payments
//$adaptive_payments .= elgg_view_input('dropdown', array(
//    'name' => 'params[agora_adaptive_payments]',
//    'value' => $plugin->agora_adaptive_payments?$plugin->agora_adaptive_payments:AgoraOptions::NO,
//    'options_values' => $pyn,
//    'label' => elgg_echo('agora:settings:agora_adaptive_payments'),
//    'help' => elgg_echo('agora:settings:agora_adaptive_payments:note', [$pa_setings_url]),
//));
//
//$adaptive_payments .= elgg_view_input('text', array(
//    'name' => 'params[agora_adaptive_payments_commission]',
//    'value' => intval($plugin->agora_adaptive_payments_commission) > 0?intval($plugin->agora_adaptive_payments_commission):AgoraOptions::MAX_IMAGES_GALLERY,
//    'label' => elgg_echo('agora:settings:agora_adaptive_payments_commission'),
//    'help' => elgg_echo('agora:settings:agora_adaptive_payments_commission:note', [$pa_setings_url]),
//    'style' => 'width: 60px;'
//));
//
//$adaptive_payments .= elgg_format_element('div', [], elgg_echo('agora:paypal:agora_adaptive_payments_important'));
//
//echo elgg_view_module("inline", elgg_echo('agora:settings:agora_adaptive_payments:title'), $adaptive_payments);

echo elgg_view('input/submit', array('value' => elgg_echo("save")));
