<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

$guid = elgg_extract('guid', $vars, '');
$entity = get_entity($guid);

if (!elgg_instanceof($entity, 'object', Agora::SUBTYPE)) {
    register_error(elgg_echo('agora:error:invalid:entity'));
    forward(REFERRER);
}

if (!$entity->canEdit() || !AgoraOptions::canMembersSendPrivateMessage()) {
    register_error(elgg_echo('Invalid access to this page'));
    forward(REFERRER);
}

$title = elgg_echo('agora:requests', [$entity->title]);
elgg_push_breadcrumb($entity->title, $entity->getURL());

$content = $entity->getRequests(true);
$body = elgg_view_layout('content', array(
    'filter' => '',
    'content' => $content,
    'title' => $title,
));

echo elgg_view_page($title, $body);