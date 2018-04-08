<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

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
$filter_context = 'my_purchases';

$vars = array(
    'filter_context' => $filter_context,
    'content' => $content,
    'title' => $title,
    'sidebar' => elgg_view('agora/sidebar'),
    'filter_override' => elgg_view('agora/nav', array('selected' => $vars['page'])),
);

$body = elgg_view_layout('content', $vars);

echo elgg_view_page($title, $body);

