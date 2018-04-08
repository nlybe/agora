<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

elgg_load_library('elgg:agora');

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

// check if user can post classifieds
if (AgoraOptions::canUserPostClassifieds()) {
    elgg_register_title_button();
}

$options = array(
    'type' => 'object',
    'subtype' => 'agora',
    'container_guid' => $page_owner->guid,
    'limit' => 10,
    'full_view' => false,
    'view_toggle_type' => false
);

$crumbs_title = $page_owner->name;
$title = elgg_echo('agora:owner', array($page_owner->name));
if (!empty($category)) {
    if (elgg_instanceof($page_owner, 'group')) {
        elgg_push_breadcrumb($crumbs_title, "agora/group/$page_owner->guid/all");
    } else {
        elgg_push_breadcrumb($crumbs_title, "agora/owner/$page_owner->username");
    }
    elgg_push_breadcrumb(agora_get_cat_name_settings($category));
    $options['metadata_name'] = "category";
    $options['metadata_value'] = $selected_category;
    $content = elgg_list_entities_from_metadata($options);
    $title .= ': ' . agora_get_cat_name_settings($category);
    //$title = elgg_echo('agora').': '.agora_get_cat_name_settings($category);
} 
else {
    if (elgg_instanceof($page_owner, 'group')) {
        elgg_push_breadcrumb($crumbs_title);
    } 
    else {
        elgg_push_breadcrumb($crumbs_title);
    }
    $content = elgg_list_entities($options);
}

if (!$content) {
    $content = elgg_echo('agora:none');
}

$filter_context = '';
if ($page_owner->getGUID() == elgg_get_logged_in_user_guid()) {
    $filter_context = 'mine';
}

// build sidebar categories url
if (elgg_instanceof($page_owner, 'group')) {
    $sidebar_cats_url = $vars['page'] . '/' . $page_owner->getGUID() . '/all';
} 
else {
    $sidebar_cats_url = $vars['page'] . '/' . $page_owner->username;
}

$vars = array(
    'filter_context' => $filter_context,
    'filter_override' => elgg_view('agora/nav', array('selected' => $vars['page'], 'page_owner_guid' => $page_owner->getGUID())),
    'content' => $content,
    'title' => $title,
    'sidebar' => elgg_view('agora/sidebar', array('selected' => $sidebar_cats_url, 'category' => $selected_category)),
);

// don't show filter if out of filter context
if ($page_owner instanceof ElggGroup) {
    $vars['filter'] = false;
}

$body = elgg_view_layout('content', $vars);

echo elgg_view_page($title, $body);
