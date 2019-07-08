<?php
/**
 * Elgg Agora Classifieds plugin
 * @package Agora
 */

$interest_guid = elgg_extract('interest_guid', $vars, false);

$render .= elgg_view_field([
    '#type' => 'hidden',
    'name' => 'interest_guid', 
    'value' => $interest_guid,
]);
$render .= elgg_view_field([
    '#type' => 'submit',
    'value' => elgg_echo('agora:interest:reject'),
    'class' => 'elgg-button-delete'
]);
echo elgg_format_element('div', ['class' => 'elgg-foot'], $render);
