<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

//elgg_load_js('agora_search_js');

$output = '';
$output .= '<div class="agora_search_form">';

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
    'name' => 's_keyword', 
    'placeholder' => elgg_echo("agora:search:keyword"),	
    'id' => 's_keyword', 
    'class' => 'elgg-input-text txt_small', 
    'value' => $vars['initial_keyword'], 
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
$output .= elgg_view_field([
    '#type' => 'hidden',
    'name' => 'sort_by', 
    'id' => 'sort_by', 
    'value' => $vars['sort_by'] 
]);
$output .= elgg_view_field([
    '#type' => 'submit',
    'id' => 'nearby_btn', 
    'value' => elgg_echo('agora:search:submit'),
    'class' => 'elgg-button elgg-button-submit nearby_btn',
]);
$output .= '</div>';
$output .= '</div>';

echo $output;
	
