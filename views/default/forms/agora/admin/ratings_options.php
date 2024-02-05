<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

use Agora\AgoraOptions;

if (!elgg_is_active_plugin('ratings')) {
    echo elgg_echo('agora:plugins:ratings:missing');
    return;
}

$plugin = elgg_get_plugin_from_id(AgoraOptions::PLUGIN_ID);
$pyn = [
    AgoraOptions::YES => elgg_echo('agora:settings:yes'),
    AgoraOptions::NO => elgg_echo('agora:settings:no'),
];

echo elgg_view_field([
    '#type' => 'checkbox',
    'name' => 'params[buyers_comrat]',
    'default' => 'no',
    'switch' => true,
    'value' => 'yes',
    'checked' => ($plugin->buyers_comrat === 'yes'),
    '#label' => elgg_echo('agora:settings:buyers_comrat'),
    '#help' => elgg_echo('agora:settings:buyers_comrat:note'),
]);

// set users to notify for each transaction
echo elgg_view_field([
    '#type' => 'text',
    'name' => 'params[buyers_comrat_notify]',
    'value' => $plugin->buyers_comrat_notify,
    '#label' => elgg_echo('agora:settings:buyers_comrat_notify'),
    '#help' => elgg_echo('agora:settings:buyers_comrat_notify:note'),
    'style' => 'width: 60px;'
]);

echo elgg_view_field([
    '#type' => 'submit',
    'value' => elgg_echo('save'),
]);
