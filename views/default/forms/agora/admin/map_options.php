<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

elgg_load_library('elgg:agora');

$plugin = elgg_get_plugin_from_id('agora');

$potential_yes_no = array(
    AGORA_GENERAL_YES => elgg_echo('agora:settings:yes'),
    AGORA_GENERAL_NO => elgg_echo('agora:settings:no'),
); 

// enable/disable ads geolocation
$ads_geolocation = $plugin->ads_geolocation;
if(empty($ads_geolocation)){
        $ads_geolocation = AGORA_GENERAL_YES;
}    
$ads_geolocation_output = elgg_view('input/dropdown', array('name' => 'params[ads_geolocation]', 'value' => $ads_geolocation, 'options_values' => $potential_yes_no));
$ads_geolocation_output .= "<span class='elgg-subtext'>" . elgg_echo('agora:settings:ads_geolocation:note') . "</span>";
echo elgg_view_module("inline", elgg_echo('agora:settings:ads_geolocation'), $ads_geolocation_output);

// initial choice for loading map

$initial_load = $plugin->initial_load;
if (!$initial_load)
	$initial_load = 'all';
	
$options = array();
$options[elgg_echo('agora:settings:initial_load:all')] = 'all';
$options[elgg_echo('agora:settings:initial_load:newest')] = 'newest';
$options[elgg_echo('agora:settings:initial_load:mylocation')] = 'location';
	
$initial = '<div class="amap_settings_box">';
$initial .= '<div class="elgg-subtext">'.elgg_echo('agora:settings:initial_load:note').'</div>';
$initial .= elgg_view('input/radio', array('name' => 'params[initial_load]', 'value' => $initial_load, 'options' => $options));
$initial .= '</div>';

// no of newest groups
$initial .= '<div class="amap_settings_box">';
$initial .= "<div class='txt_label'>" . elgg_echo('agora:settings:initial_load:newest_no') . ": </div>";
$initial .= elgg_view('input/text', array('name' => 'params[newest_no]', 'value' => (is_numeric($plugin->newest_no)?$plugin->newest_no:AMAP_MA_NEWEST_NO_DEFAULT), 'class' => 'txt_small'));
$initial .= "<span class='elgg-subtext'>".elgg_echo('agora:settings:initial_load:newest_no:note')."</span>";
$initial .= '</div>';

// default radius
$initial .= '<div class="amap_settings_box">';
$initial .= "<div class='txt_label'>" . elgg_echo('agora:settings:initial_load:mylocation_radius') . ": </div>";
$initial .= elgg_view('input/text', array('name' => 'params[mylocation_radius]', 'value' => (is_numeric($plugin->mylocation_radius)?$plugin->mylocation_radius:AMAP_MA_RADIUS_DEFAULT), 'class' => 'txt_small'));
$initial .= "<span class='elgg-subtext'>".elgg_echo('agora:settings:initial_load:mylocation_radius:note')."</span>";
$initial .= '</div>';
echo elgg_view_module("inline", elgg_echo('agora:settings:initial_load:title'), $initial);

// show list on sidebar
$sidebar_list = $plugin->sidebar_list;
if(empty($sidebar_list)){
	$sidebar_list = AGORA_GENERAL_YES;
}    
$sidebar_list_view = '<div class="amap_settings_box">';
$sidebar_list_view .= elgg_view('input/dropdown', array('name' => 'params[sidebar_list]', 'value' => $sidebar_list, 'options_values' => $potential_yes_no));
$sidebar_list_view .= "<span class='elgg-subtext'>" . elgg_echo('agora:settings:sidebar_list:note') . "</span>";
$sidebar_list_view .= '</div>';
echo elgg_view_module("inline", elgg_echo('agora:settings:sidebar_list'), $sidebar_list_view);


// set default icon
$markericon = $plugin->markericon;
if(empty($markericon)){
        $markericon = 'smiley';
}    
$potential_icon = array(
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
); 

$map_icon = elgg_view('input/dropdown', array('name' => 'params[markericon]', 'value' => $markericon, 'options_values' => $potential_icon));
$map_icon .= "<span class='elgg-subtext'>" . elgg_echo('agora:settings:markericon:note') . "</span>";
echo elgg_view_module("inline", elgg_echo('agora:settings:markericon'), $map_icon);

echo elgg_view('input/submit', array('value' => elgg_echo("save")));
