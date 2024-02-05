<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

use Elgg\Exceptions\Http\EntityNotFoundException;
use Agora\AgoraOptions;

$user = elgg_get_logged_in_user_entity();
if (!$user) {
    throw new EntityNotFoundException();
}

elgg_push_breadcrumb(elgg_echo('agora'), 'agora/all');
elgg_push_breadcrumb(elgg_echo('agora:my_purchases'));

$content = elgg_list_entities([
    'type' => 'object',
    'subtype' => AgoraSale::SUBTYPE,
    'owner_guid' => $user->guid,
    'no_results' => elgg_echo('agora:purchases:none'),
    'is_buyer' => true,
]);

$title = elgg_echo('agora:my_purchases');

$vars = [
    'content' => $content,
    'title' => $title,
];


echo elgg_view_page($title, [
    'content' => $content,
    'filter' => false,
]);
