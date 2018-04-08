<?php
/**
 * Elgg Agora Classifieds plugin
 * @package Agora
 */

$interest_guid = elgg_extract('interest_guid', $vars, false);

$render .= elgg_view('input/hidden', array('name' => 'interest_guid', 'value' => $interest_guid));
$render .= elgg_view('input/submit', array('value' => elgg_echo('agora:interest:accept')));
    
echo elgg_format_element('div', ['class' => 'elgg-foot'], $render);