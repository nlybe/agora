<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

elgg_load_library('elgg:agora');

$logged_in_user = elgg_get_logged_in_user_entity();
if (!$logged_in_user) {
	forward('agora/all');
}

elgg_push_breadcrumb(elgg_echo('agora:mypurchases'));

// check if user can post agora
if (check_if_user_can_post_classifieds())   {
    elgg_register_title_button();
}

$options = array(
	'type' => 'object',
	'subtype' => 'agorasales',
	'limit' => 10,
	'metadata_name_value_pairs' => array(
		array('name' => 'txn_buyer_guid', 'value' => $logged_in_user->guid, 'operand' => '='),
	),
	'metadata_name_value_pairs_operator' => 'AND',
);

$content = elgg_list_entities_from_metadata($options);

if (!$content) {
	$content = elgg_echo('agora:mypurchases:none');
}

$title = elgg_echo('agora:mypurchases');
$filter_context = 'mypurchases';

$vars = array(
	'filter_context' => $filter_context,
	'content' => $content,
	'title' => $title,
	'sidebar' => elgg_view('agora/sidebar'),
	'filter_override' => elgg_view('agora/nav', array('selected' => $vars['page'])),
);

$body = elgg_view_layout('content', $vars);

echo elgg_view_page($title, $body);

