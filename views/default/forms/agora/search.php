<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */


$output .= elgg_format_element('div', ['class' => 'nsf_element nsf_small'], elgg_view_field([
    '#type' => 'agora_categories',
    'name' => 's_category', 
    'id' => 's_category', 
    'value' => $vars['initial_category'], 
]));

$output .= elgg_format_element('div', ['class' => 'nsf_element nsf_small'], elgg_view_field([
    '#type' => 'text',
    'name' => 's_keyword', 
    'placeholder' => elgg_echo("agora:search:keyword"),	
    'id' => 's_keyword', 
    'class' => 'elgg-input-text txt_small', 
    'value' => $vars['initial_keyword'], 
]));

$output .= elgg_format_element('div', ['class' => 'nsf_element nsf_small'], elgg_view_field([
    '#type' => 'text',
    'name' => 's_price_min', 
    'placeholder' => elgg_echo("agora:search:price_min"),	
    'id' => 's_price_min', 
    'class' => 'elgg-input-text txt_small', 
    'value' => $vars['initial_price_min'], 
]));

$output .= elgg_format_element('div', ['class' => 'nsf_element nsf_small'], elgg_view_field([
    '#type' => 'text',
    'name' => 's_price_max', 
    'placeholder' => elgg_echo("agora:search:price_max"),	
    'id' => 's_price_max', 
    'class' => 'elgg-input-text txt_small', 
    'value' => $vars['initial_price_max'], 
]));

$hidden .= elgg_view_field([
    '#type' => 'hidden',
    'name' => 's_action', 
    'id' => 's_action', 
    'value' => $vars['s_action'] 
]);
$hidden .= elgg_view_field([
    '#type' => 'hidden',
    'name' => 'sort_by', 
    'id' => 'sort_by', 
    'value' => $vars['sort_by'] 
]);
$hidden .= elgg_view_field([
    '#type' => 'submit',
    'id' => 'nearby_btn', 
    'value' => elgg_echo('agora:search:submit'),
    'class' => 'elgg-button elgg-button-submit nearby_btn',
]);
$output .= elgg_format_element('div', ['class' => 'nsf_element'], $hidden);

echo elgg_format_element('div', ['class' => 'agora_search_form'], $output);
	
