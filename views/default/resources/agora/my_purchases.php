<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

use Agora\AgoraOptions;

$user = elgg_get_logged_in_user_entity();
if (!$user) {
    forward('agora/all');
}

elgg_push_breadcrumb(elgg_echo('agora:my_purchases'));

if (AgoraOptions::canUserPostClassifieds()) {
    elgg_register_title_button();
}

$content = elgg_list_entities([
    'type' => 'object',
    'subtype' => AgoraSale::SUBTYPE,
    'owner_guid' => $user->guid,
    'no_results' => elgg_echo('agora:purchases:none'),
    'is_buyer' => true,
]);

$title = elgg_echo('agora:my_purchases');
$filter_value = 'my_purchases';

$vars = [
    'filter_value' => $filter_value,
    'content' => $content,
    'title' => $title,
    'sidebar' => elgg_view('agora/sidebar'),
    'filter_override' => elgg_view('agora/nav', ['selected' => 'my_purchases']),
];

$body = elgg_view_layout('default', $vars);

echo elgg_view_page($title, $body);

