<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

use Elgg\Exceptions\Http\EntityNotFoundException;
use Agora\AgoraOptions;

$page_owner = elgg_get_page_owner_entity();
if (!$page_owner) {
    throw new EntityNotFoundException();
}

// Get category
$selected_category = elgg_extract('category', $vars, 'all');
$s_category = $selected_category == 'all'?'':$selected_category;

$user = elgg_get_logged_in_user_entity();
if ($user) {
    elgg_register_menu_item('title', [
        'name' => 'my_purchases',
        'icon' => 'money-check',
        'text' => elgg_echo('agora:label:my_purchases'),
        'href' => elgg_generate_url('my_purchases:object:agora', [
			'username' => $user->username,
		]),
        'link_class' => 'elgg-button elgg-button-action',
    ]);
}

// check if user can post classifieds
if (AgoraOptions::canUserPostClassifieds()) {
    elgg_register_title_button('add', 'object', 'agora');
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

// build sidebar categories url
if ($page_owner instanceof \ElggGroup) {
    $sidebar_cats_url = $page_owner->getGUID() . '/all';
} 
else {
    $sidebar_cats_url = $page_owner->username;
}

echo elgg_view_page($title, [
    'content' => $content,
    'sidebar' => elgg_view('agora/sidebar', [
        'selected' => $sidebar_cats_url, 
        'category' => $selected_category,
        'page' => "agora/owner/$sidebar_cats_url/", 
    ]),
]);
