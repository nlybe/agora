<?php
/**
 * Elgg agora Classifieds plugin
 * @package Agora
 */

$num_display = $vars['entity']->num_display;
if($num_display == ''){
    $num_display = '5';
} 

$output = elgg_echo("agora:widget:num_display_items");

for ($i = 1; $i < 10; $i++) {
    $list .= elgg_format_element('option', ['value' => $i, 'selected' => ($num_display == $i?true:false)], $i);
}

$output .= elgg_format_element('select', ['name' => 'params[num_display]'], $list);

echo elgg_format_element('div', [], $output);

