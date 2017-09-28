<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

elgg_load_library('elgg:agora');

// load list of bought items
$options = array(
	'type' => 'object',
	'subtype' => 'agorasales',
	'limit' => 10,
);

$content = elgg_list_entities($options);

$title = elgg_echo('agora:settings:tabs:transactions_log');
$body = elgg_view_layout('one_column', array(
	'filter_context' => 'all',
	'content' => $content,
	'title' => $title,
	'sidebar' => '',
	'filter_override' => '',
));

echo $body;


