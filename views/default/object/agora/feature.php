<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

$label = elgg_extract('label', $vars, '');
$text = elgg_extract('text', $vars, '');

if (!isset($text)) {
    return;
}

if ($label) {
    $content .= elgg_format_element('div', ['class' => 'f_label'], $label); 
}

$content .= elgg_format_element('div', ['class' => 'f_text'], $text); 

// echo elgg_format_element('div', ['class' => 'list_features'], $content); 
echo elgg_format_element('div', ['class' => 'elgg-image-block clearfix list_features'], $content); 
