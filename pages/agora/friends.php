<?php
/**
 * Elgg Agora Classifieds plugin
 * @package Agora
 */

elgg_load_library('elgg:agora');

$page_owner = elgg_get_page_owner_entity();
if (!$page_owner) {
	forward('agora/all');
}

// Get category
$selected_category = get_input('category');
if ($selected_category == 'all') {
	$category = '';
} elseif ($selected_category == '') {
	$category = '';
	$selected_category = 'all';
} else {
	$category = $selected_category;
}

elgg_push_breadcrumb($page_owner->name, "agora/owner/$page_owner->username");
elgg_push_breadcrumb(elgg_echo('friends'));

// check if user can post classifieds
if (check_if_user_can_post_classifieds())   {
    elgg_register_title_button();
}

$title = elgg_echo('agora:friends');

$content = elgg_list_entities_from_relationship(array(
	'type' => 'object',
	'subtype' => 'agora',
	'full_view' => false,
	'limit' => 10,
	'relationship' => 'friend',
	'relationship_guid' => $page_owner->guid,
	'relationship_join_on' => 'container_guid',
));

if (!$content) {
	$content = elgg_echo('agora:none');
}

$params = array(
	'filter_context' => 'friends',
	'content' => $content,
	'title' => $title,
	'filter_override' => elgg_view('agora/nav', array('selected' => $vars['page'])),
);

$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);
