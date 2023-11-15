<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

use Agora\AgoraOptions;

$page_owner = elgg_get_page_owner_entity();
if (!$page_owner) {
    forward('agora/all');
}

// Get category
$selected_category = elgg_extract('category', $vars, 'all');
if ($selected_category == 'all') {
    $s_category = '';
} else {
    $s_category = $selected_category;
}

// check if user can post classifieds
if (AgoraOptions::canUserPostClassifieds()) {
    elgg_register_title_button('agora', 'add', 'object', 'agora');
}

$options = [
    'type' => 'object',
    'subtype' => Agora::SUBTYPE,
    'container_guid' => $page_owner->guid,
    'full_view' => false,
    'view_toggle_type' => false,
    'no_results' => elgg_echo('agora:none'),
];

$category = AgoraOptions::getCatName($s_category);

$title = elgg_echo('agora:owner', [$page_owner->name]);
elgg_push_breadcrumb(elgg_echo('agora'), 'agora/all');
if (!empty($s_category)) {
    elgg_push_breadcrumb($page_owner->name, "agora/owner/$page_owner->username");
    elgg_push_breadcrumb($category);
    $options['metadata_name'] = "category";
    $options['metadata_value'] = $selected_category;
    $content = elgg_list_entities($options);
    $title .= ': ' . $category;
} 
else {
    elgg_push_breadcrumb($page_owner->name);
    $content = elgg_list_entities($options);
}

$filter_value = '';
if ($page_owner->getGUID() == elgg_get_logged_in_user_guid()) {
    $filter_value = 'mine';
}

// build sidebar categories url
if ($page_owner instanceof \ElggGroup) {
    $sidebar_cats_url = $page_owner->getGUID() . '/all';
} 
else {
    $sidebar_cats_url = $page_owner->username;
}

$vars = [
    'filter_value' => $filter_value,
    'filter_override' => elgg_view('agora/nav', ['selected' => $vars['page'], 'page_owner_guid' => $page_owner->getGUID()]),
    'content' => $content,
    'title' => $title,
    'sidebar' => elgg_view('agora/sidebar', ['selected' => $sidebar_cats_url, 'category' => $selected_category]),
];

$body = elgg_view_layout('default', $vars);

echo elgg_view_page($title, $body);
