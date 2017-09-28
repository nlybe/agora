<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

elgg_load_library('elgg:agora');

$plugin = elgg_get_plugin_from_id('agora');

// enable/disable ads geolocation
$ads_digital = $plugin->ads_digital;
if(empty($ads_digital)){
	$ads_digital = 'no';
}    
$potential_ads_digital = array(
    "no" => elgg_echo('agora:settings:no'),
    "digitalplus" => elgg_echo('agora:settings:ads_digital:plus'),
    "digitalonly" => elgg_echo('agora:settings:ads_digital:only'),
); 
$ads_digital_output = elgg_view('input/dropdown', array('name' => 'params[ads_digital]', 'value' => $ads_digital, 'options_values' => $potential_ads_digital));
$ads_digital_output .= "<span class='elgg-subtext'>" . elgg_echo('agora:settings:ads_digital:note') . "</span>";
echo elgg_view_module("inline", elgg_echo('agora:settings:ads_digital'), $ads_digital_output);


// set allowed file type
$digital_file_types = elgg_view('input/text', array('name' => 'params[digital_file_types]', 'value' => $plugin->digital_file_types));
$digital_file_types .= "<span class='elgg-subtext'>" . elgg_echo('agora:settings:digital:file_types:note') . "</span>";
echo elgg_view_module("inline", elgg_echo('agora:settings:digital:file_types'), $digital_file_types);


echo elgg_view('input/submit', array('value' => elgg_echo("save")));
