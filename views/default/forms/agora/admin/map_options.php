<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

if (!elgg_is_active_plugin('amap_maps_api')) {
    echo elgg_echo('agora:plugins:amap_maps_api:missing');
    return;
}

$plugin = elgg_get_plugin_from_id(AgoraOptions::PLUGIN_ID);
$pyn = array(
    AgoraOptions::YES => elgg_echo('agora:settings:yes'),
    AgoraOptions::NO => elgg_echo('agora:settings:no'),
);

echo elgg_view_field([
    '#type' => 'dropdown',
    'name' => 'params[ads_geolocation]',
    'value' => $plugin->ads_geolocation?$plugin->ads_geolocation:AgoraOptions::NO,
    'options_values' => $pyn,
    '#label' => elgg_echo('agora:settings:ads_geolocation'),
    '#help' => elgg_echo('agora:settings:ads_geolocation:note'),
]);

echo elgg_view_field([
    '#type' => 'dropdown',
    'name' => 'params[sidebar_list]',
    'value' => $plugin->sidebar_list?$plugin->sidebar_list:AgoraOptions::NO,
    'options_values' => $pyn,
    '#label' => elgg_echo('agora:settings:sidebar_list'),
    '#help' => elgg_echo('agora:settings:sidebar_list:note'),
]);

echo elgg_view_field([
    '#type' => 'dropdown',
    'name' => 'params[markericon]',
    'value' => $plugin->markericon?$plugin->markericon:AgoraOptions::ICON,
    'options_values' => [
        "ad_image" => elgg_echo('agora:settings:markericon:ad_image'),
        "agora_blue" => elgg_echo('agora:settings:markericon:agora_blue'),
        "agora_royal_blue" => elgg_echo('agora:settings:markericon:agora_royal_blue'),
        "agora_forest_green" => elgg_echo('agora:settings:markericon:agora_forest_green'),
        "agora_grey" => elgg_echo('agora:settings:markericon:agora_grey'),
        "agora_orange" => elgg_echo('agora:settings:markericon:agora_orange'),
        "agora_pink" => elgg_echo('agora:settings:markericon:agora_pink'),
        "agora_purple" => elgg_echo('agora:settings:markericon:agora_purple'),
        "agora_red" => elgg_echo('agora:settings:markericon:agora_red'),
        "agora_violet_red" => elgg_echo('agora:settings:markericon:agora_violet_red'),
        "agora_yellow" => elgg_echo('agora:settings:markericon:agora_yellow'),
    ],
    '#label' => elgg_echo('agora:settings:markericon'),
    '#help' => elgg_echo('agora:settings:markericon:note'),
]);

// initial choice for loading map
$initial_load = $plugin->initial_load;
if (!$initial_load) {
    $initial_load = 'all';
}
$options = array();
$options[elgg_echo('agora:settings:initial_load:all')] = 'all';
$options[elgg_echo('agora:settings:initial_load:newest')] = 'newest';
$options[elgg_echo('agora:settings:initial_load:mylocation')] = 'location';
	
$initial = '<div class="amap_settings_box">';
$initial .= '<div class="elgg-subtext">'.elgg_echo('agora:settings:initial_load:note').'</div>';
$initial .= elgg_view_field([
    '#type' => 'radio',
    'name' => 'params[initial_load]', 
    'value' => $initial_load, 
    'options' => $options,
]);
$initial .= '</div>';

// no of newest groups
$initial .= '<div class="amap_settings_box">';
$initial .= "<div class='txt_label'>" . elgg_echo('agora:settings:initial_load:newest_no') . ": </div>";
$initial .= elgg_view_field([
    '#type' => 'text',
    'name' => 'params[newest_no]', 
    'value' => (is_numeric($plugin->newest_no)?$plugin->newest_no:AMAP_MA_NEWEST_NO_DEFAULT), 
    'class' => 'txt_small',
]);
$initial .= "<span class='elgg-subtext'>".elgg_echo('agora:settings:initial_load:newest_no:note')."</span>";
$initial .= '</div>';

// default radius
$initial .= '<div class="amap_settings_box">';
$initial .= "<div class='txt_label'>" . elgg_echo('agora:settings:initial_load:mylocation_radius') . ": </div>";
$initial .= elgg_view_field([
    '#type' => 'text',
    'name' => 'params[mylocation_radius]', 
    'value' => (is_numeric($plugin->mylocation_radius)?$plugin->mylocation_radius:AMAP_MA_RADIUS_DEFAULT), 
    'class' => 'txt_small',
]);
$initial .= "<span class='elgg-subtext'>".elgg_echo('agora:settings:initial_load:mylocation_radius:note')."</span>";
$initial .= '</div>';
echo elgg_view_module("inline", elgg_echo('agora:settings:initial_load:title'), $initial);

echo elgg_view_field([
    '#type' => 'submit',
    'value' => elgg_echo('save'),
]);
