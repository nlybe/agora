<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

//elgg_load_js('agora_map_search_js');

$output = '';
$output .= '<div class="nearby_search_form">';
$output .= '<div class="nsf_element nsf_medium">';
$output .= elgg_view_field([
    '#type' => 'text',
    'name' => 's_location', 
    'placeholder' => elgg_echo("agora:location"),	
    'id' => 'autocomplete',
    'class' => 'elgg-input-text txt_medium', 
    'value' => (isset($vars['initial_location'])?$vars['initial_location']:''),
    //'value' => (isset($vars['initial_load']) && $vars['initial_load'] == 'location' && isset($vars['user_location'])?$vars['user_location']:''),
]);
if (isset($vars['my_location'])) {
    $output .= '<label class="mtm float-alt">'.elgg_view_field([
        '#type' => 'checkbox',
        'name' => 'my_location', 
        'value' => 'show', 
        'id' => 'my_location',
    ]).elgg_echo("agora:my_location").'</label>';
}
$output .= '</div>';
$output .= '<div class="nsf_element nsf_small">';
$output .= elgg_view_field([
    '#type' => 'text',
	'name' => 's_radius', 
	'placeholder' => amap_ma_get_unit_of_measurement_string(AMAP_MA_PLUGIN_ID),	
	'id' => 's_radius', 
	'class' => 'elgg-input-text txt_small', 
	'value' => (isset($vars['initial_radius'])?$vars['initial_radius']:''),
]);
$output .= '<label class="mtm float-alt">'.elgg_view_field([
    '#type' => 'checkbox',
    'name' => 'showradius', 
    'value' => 'show', 
    'id' => 'showradius',
]).elgg_echo("agora:showradius").'</label>';
$output .= '</div>';

$output .= '<div class="nsf_element nsf_small">';
$output .= elgg_view_field([
    '#type' => 'text',
    'name' => 's_keyword', 
    'placeholder' => elgg_echo("agora:search:keyword"),	
    'id' => 's_keyword', 
    'class' => 'elgg-input-text txt_small', 
    'value' => $vars['initial_keyword'], 
]);
$output .= '</div>';

$output .= '<div class="nsf_element nsf_small">';
$output .= elgg_view_field([
    '#type' => 'agora_categories',
    'name' => 's_category', 
    'id' => 's_category', 
    'value' => $vars['initial_category'], 
]);
$output .= '</div>';

$output .= '<div class="nsf_element nsf_small">';
$output .= elgg_view_field([
    '#type' => 'text',
    'name' => 's_price_min', 
    'placeholder' => elgg_echo("agora:search:price_min"),	
    'id' => 's_price_min', 
    'class' => 'elgg-input-text txt_small', 
    'value' => $vars['initial_price_min'], 
]);
$output .= '</div>';

$output .= '<div class="nsf_element nsf_small">';
$output .= elgg_view_field([
    '#type' => 'text',
    'name' => 's_price_max', 
    'placeholder' => elgg_echo("agora:search:price_max"),	
    'id' => 's_price_max', 
    'class' => 'elgg-input-text txt_small', 
    'value' => $vars['initial_price_max'], 
]);
$output .= '</div>';

$output .= '<div class="nsf_element">';
$output .= elgg_view_field([
    '#type' => 'hidden',
	'name' => 's_action', 
	'id' => 's_action', 
	'value' => $vars['s_action'] 
]);
if (isset($vars['my_location'])) {
    $output .= elgg_view_field([
        '#type' => 'hidden',
        'name' => 'user_location', 
        'id' => 'user_location', 
        'value' => $vars['my_location'], 
    ]);
}
$output .= elgg_view_field([
    '#type' => 'submit',
    'id' => 'nearby_btn', 
    'value' => elgg_echo('agora:search:submit'),
    'class' => 'elgg-button elgg-button-submit nearby_btn',
]);
$output .= '</div>';
$output .= '</div>';

$output .= '
    <script language="javascript">
    $(document).ready(function(){ 
        autocomplete = new google.maps.places.Autocomplete(
            /** @type {HTMLInputElement} */(document.getElementById(\'autocomplete\')),
            { types: [\'geocode\'] });
        // When the user selects an address from the dropdown,
        // populate the address fields in the form.
        google.maps.event.addListener(autocomplete, \'place_changed\', function() {
        });    
    });
    </script>
';	

echo $output;
	
