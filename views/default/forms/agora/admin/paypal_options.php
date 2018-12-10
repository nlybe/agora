<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

if (!elgg_is_active_plugin('paypal_api')) {
    echo elgg_echo('agora:plugins:paypal_api:missing');
    return;
}

elgg_require_js("agora/js/agora_admin");

$plugin = elgg_get_plugin_from_id(AgoraOptions::PLUGIN_ID);
$pyn = array(
    AgoraOptions::YES => elgg_echo('agora:settings:yes'),
    AgoraOptions::NO => elgg_echo('agora:settings:no'),
); 

echo elgg_view_input('checkbox', array(
    'id' => 'agora_paypal_enabled',
    'name' => 'params[agora_paypal_enabled]',
    'label' => elgg_echo('agora:settings:agora_paypal_enabled'),
    'checked' => ($plugin->agora_paypal_enabled? true : false),
    'help' => elgg_echo('agora:settings:agora_paypal_enabled:note', [$pa_setings_url]),
    'required' => false,
));

// Adaptive Payments
$pa_setings_url = elgg_view('output/url', [
    'href' => elgg_normalize_url('admin/plugin_settings/paypal_api'),
    'text' => elgg_echo('agora:settings:agora_adaptive_payments:paypal_api'),
    'target' => "_blank",
]);

$adaptive_input = elgg_view_input('checkbox', array(
    'id' => 'agora_adaptive_payments',
    'name' => 'params[agora_adaptive_payments]',
    'label' => elgg_echo('agora:settings:agora_adaptive_payments'),
    'checked' => ($plugin->agora_adaptive_payments? true : false),
    'help' => elgg_echo('agora:settings:agora_adaptive_payments:note', [$pa_setings_url]),
    'required' => false,
));

$adaptive_input .= elgg_view_input('text', array(
    'id' => 'agora_adaptive_payments_commission',
    'name' => 'params[agora_adaptive_payments_commission]',
    'value' => intval($plugin->agora_adaptive_payments_commission) > 0?intval($plugin->agora_adaptive_payments_commission):AgoraOptions::ADAPTIVE_DEFAULT_COMMISSION,
    'label' => elgg_echo('agora:settings:agora_adaptive_payments_commission'),
    'help' => elgg_echo('agora:settings:agora_adaptive_payments_commission:note', [$pa_setings_url]),
    'style' => 'width: 60px;'
));

$legent = elgg_format_element('legend', [], elgg_echo('agora:settings:agora_adaptive_payments:title'));
$list = elgg_format_element('fieldset', [], $legent.$adaptive_input);
echo elgg_format_element('div', ['class' => 'agora_adaptive_settings'], $list);

echo elgg_view('input/submit', array('value' => elgg_echo("save")));
