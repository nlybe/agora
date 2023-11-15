<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

use Agora\AgoraOptions;

$group_guid = elgg_extract('guid', $vars);

elgg_entity_gatekeeper($group_guid, 'group');

elgg_group_tool_gatekeeper('agora', $group_guid);

$group = get_entity($group_guid);

// Get category
$selected_category = elgg_extract('category', $vars, '');
if ($selected_category == 'all') {
    $s_category = '';
} elseif ($selected_category == '') {
    $s_category = '';
    $selected_category = 'all';
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
    'container_guid' => $group->guid,
    'full_view' => false,
    'view_toggle_type' => false,
    'no_results' => elgg_echo('agora:none'),
];

$category = AgoraOptions::getCatName($s_category);

$title = elgg_echo('agora:owner', [$group->name]);
elgg_push_breadcrumb(elgg_echo('agora'), 'agora/all');
if (!empty($s_category)) {
    elgg_push_breadcrumb($group->name, "agora/group/$group->guid");
    elgg_push_breadcrumb($category);
    $options['metadata_name'] = "category";
    $options['metadata_value'] = $selected_category;
    $content = elgg_list_entities($options);
    $title .= ': ' . $category;
} 
else {
    elgg_push_breadcrumb($group->name);
    $content = elgg_list_entities($options);
}

// build sidebar categories url
$sidebar_cats_url = $vars['page'] . '/' . $group->getGUID() . '/all';

$vars = [
    'filter_value' =>'',
    'filter_override' => elgg_view('agora/nav', ['selected' => $vars['page'], 'page_owner_guid' => $group->getGUID()]),
    'content' => $content,
    'title' => $title,
    'sidebar' => elgg_view('agora/sidebar', ['selected' => $sidebar_cats_url, 'category' => $selected_category]),
];

// don't show filter if out of filter context
$vars['filter'] = false;

$body = elgg_view_layout('default', $vars);

echo elgg_view_page($title, $body);
