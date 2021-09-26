<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

$output .= elgg_format_element('div', ['class' => 'nsf_element nsf_small'], elgg_view_field([
	'#type' => 'agora_sort_by',
    'name' => 'sort_by', 
    'id' => 'sort_by', 
    'value' => $vars['sort_by'], 
]));

$hidden .= elgg_view_field([
	'#type' => 'hidden',
	'name' => 's_action', 
	'id' => 's_action', 
	'value' => $vars['s_action'] 
]);
$hidden .= elgg_view_field([
	'#type' => 'hidden',
	'name' => 's_category', 
	'id' => 's_category', 
	'value' => $vars['initial_category'] 
]);
$hidden .= elgg_view_field([
	'#type' => 'hidden',
	'name' => 's_keyword', 
	'id' => 's_keyword', 
	'value' => $vars['initial_keyword'] 
]);
$hidden .= elgg_view_field([
	'#type' => 'hidden',
	'name' => 's_price_min', 
	'id' => 's_price_min', 
	'value' => $vars['initial_price_min'] 
]);
$hidden .= elgg_view_field([
	'#type' => 'hidden',
	'name' => 's_price_max', 
	'id' => 's_price_max', 
	'value' => $vars['initial_price_max'] 
]);
$output .= elgg_format_element('div', ['class' => 'nsf_element'], $hidden);

echo elgg_format_element('div', ['class' => 'agora_sort_by'], $output);
	
