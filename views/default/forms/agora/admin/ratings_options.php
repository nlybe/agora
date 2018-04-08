<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

if (!elgg_is_active_plugin('ratings')) {
    echo elgg_echo('agora:plugins:ratings:missing');
    return;
}

$plugin = elgg_get_plugin_from_id(AgoraOptions::PLUGIN_ID);
$pyn = array(
    AgoraOptions::YES => elgg_echo('agora:settings:yes'),
    AgoraOptions::NO => elgg_echo('agora:settings:no'),
);

echo elgg_view_input('dropdown', array(
    'name' => 'params[buyers_comrat]',
    'value' => $plugin->buyers_comrat?$plugin->buyers_comrat:AgoraOptions::NO,
    'options_values' => $pyn,
    'label' => elgg_echo('agora:settings:buyers_comrat'),
    'help' => elgg_echo('agora:settings:buyers_comrat:note'),
));

// set users to notify for each transaction
echo elgg_view_input('text', array(
    'name' => 'params[buyers_comrat_notify]',
    'value' => $plugin->buyers_comrat_notify,
    'label' => elgg_echo('agora:settings:buyers_comrat_notify'),
    'help' => elgg_echo('agora:settings:buyers_comrat_notify:note'),
    'style' => 'width: 60px;'
));

echo elgg_view('input/submit', array('value' => elgg_echo("save")));
