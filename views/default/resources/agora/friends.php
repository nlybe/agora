<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

use Elgg\Exceptions\Http\EntityPermissionsException;
use Agora\AgoraOptions;

$page_owner = elgg_get_page_owner_entity();
if (!$page_owner) {
    throw new EntityPermissionsException();
}

// Get category
$selected_category = elgg_extract('category', $vars, '');
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
    'full_view' => false,
    'relationship' => 'friend',
    'relationship_guid' => $page_owner->guid,
    'relationship_join_on' => 'container_guid',
    'no_results' => elgg_echo('agora:none'),
];

elgg_push_breadcrumb(elgg_echo('agora'), 'agora/all');
elgg_push_breadcrumb($page_owner->name, "agora/owner/$page_owner->username");
elgg_push_breadcrumb(elgg_echo('friends'));

$category = AgoraOptions::getCatName($s_category);
$title = elgg_echo('agora:friends');
if (!empty($s_category)) {
    elgg_push_breadcrumb($category);
    $options['metadata_name'] = "category";
    $options['metadata_value'] = $selected_category;
    $content = elgg_list_entities($options);
    $title .= ': ' . $category;
} 
else {
    $content = elgg_list_entities($options);
}

echo elgg_view_page($title, [
    'content' => $content,
    'sidebar' => elgg_view('agora/sidebar', [
        'selected' => $page_owner->username, 
        'category' => $selected_category,
        'page' => "agora/friends/$page_owner->username/", 
    ]),
]);
