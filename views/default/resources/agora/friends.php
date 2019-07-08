<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

$page_owner = elgg_get_page_owner_entity();
if (!$page_owner) {
    forward('agora/all');
}

// Get category
$selected_category = elgg_extract('category', $vars, '');
if ($selected_category == 'all') {
    $category = '';
} elseif ($selected_category == '') {
    $category = '';
    $selected_category = 'all';
} else {
    $category = $selected_category;
}

elgg_push_breadcrumb(elgg_echo('agora'), 'agora/all');
elgg_push_breadcrumb($page_owner->name, "agora/owner/$page_owner->username");
elgg_push_breadcrumb(elgg_echo('friends'));

// check if user can post classifieds
if (AgoraOptions::canUserPostClassifieds()) {
    elgg_register_title_button();
}

$title = elgg_echo('agora:friends');

$content = elgg_list_entities(array(
    'type' => 'object',
    'subtype' => Agora::SUBTYPE,
    'full_view' => false,
    'relationship' => 'friend',
    'relationship_guid' => $page_owner->guid,
    'relationship_join_on' => 'container_guid',
    'no_results' => elgg_echo('agora:none'),
));

$params = array(
    'filter_context' => 'friends',
    'content' => $content,
    'title' => $title,
    'filter_override' => elgg_view('agora/nav', array('selected' => 'friends')),
);

$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);
