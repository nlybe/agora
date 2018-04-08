<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

$output = '';
$output .= '<div class="nearby_search_form">';

$output .= '<div class="nsf_element nsf_small">';
$output .= elgg_view('input/agora_categories', array(
    'name' => 's_category', 
    'id' => 's_category', 
    'value' => $vars['initial_category'], 
));
$output .= '</div>';

$output .= '<div class="nsf_element nsf_small">';
$output .= elgg_view('input/text', array(
    'name' => 's_keyword', 
    'placeholder' => elgg_echo("agora:search:keyword"),	
    'id' => 's_keyword', 
    'class' => 'elgg-input-text txt_small', 
    'value' => $vars['initial_keyword'], 
));
$output .= '</div>';

$output .= '<div class="nsf_element nsf_small">';
$output .= elgg_view('input/text', array(
    'name' => 's_price_min', 
    'placeholder' => elgg_echo("agora:search:price_min"),	
    'id' => 's_price_min', 
    'class' => 'elgg-input-text txt_small', 
    'value' => $vars['initial_price_min'], 
));
$output .= '</div>';

$output .= '<div class="nsf_element nsf_small">';
$output .= elgg_view('input/text', array(
    'name' => 's_price_max', 
    'placeholder' => elgg_echo("agora:search:price_max"),	
    'id' => 's_price_max', 
    'class' => 'elgg-input-text txt_small', 
    'value' => $vars['initial_price_max'], 
));
$output .= '</div>';

$output .= '<div class="nsf_element">';
$output .= elgg_view('input/hidden', array(
	'name' => 's_action', 
	'id' => 's_action', 
	'value' => $vars['s_action'] 
));
if (isset($vars['my_location'])) {
    $output .= elgg_view('input/hidden', array(
        'name' => 'user_location', 
        'id' => 'user_location', 
        'value' => $vars['my_location'], 
    ));
}
$output .=  elgg_view('input/submit', array(
	'value' => elgg_echo('agora:search:submit'),
	'class' => 'elgg-button elgg-button-submit nearby_btn', 
	'id' => 'nearby_btn', 
));
$output .= '</div>';
$output .= '</div>';

echo $output;
	
