<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

use Agora\AgoraOptions;

$plugin = elgg_get_plugin_from_id(AgoraOptions::PLUGIN_ID);

$ads_digital = $plugin->ads_digital;
if(empty($ads_digital)){
    $ads_digital = 'no';
}    
$pad = [
    "no" => elgg_echo('agora:settings:no'),
    "digitalplus" => elgg_echo('agora:settings:ads_digital:plus'),
    "digitalonly" => elgg_echo('agora:settings:ads_digital:only'),
]; 

echo elgg_view_field([
    '#type' => 'dropdown',
    'name' => 'params[ads_digital]',
    'value' => $ads_digital,
    'options_values' => $pad,
    '#label' => elgg_echo('agora:settings:ads_digital'),
    '#help' => elgg_echo('agora:settings:ads_digital:note'),
]);

echo elgg_view_field([
    '#type' => 'text',
    'name' => 'params[digital_file_types]',
    'value' => $plugin->digital_file_types,
    '#label' => elgg_echo('agora:settings:digital:file_types'),
    '#help' => elgg_echo('agora:settings:digital:file_types:note'),
]);

echo elgg_view_field([
    '#type' => 'submit',
    'value' => elgg_echo('save'),
]);
