<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

use Agora\AgoraOptions;

$guid = elgg_extract('guid', $vars, '');
$entity = get_entity($guid);

if (!$entity instanceof \Agora) { 
   return elgg_error_response(elgg_echo('agora:error:invalid:entity'), REFERRER);
}

if (!$entity->canEdit() || !AgoraOptions::canMembersSendPrivateMessage()) {
    return elgg_error_response(elgg_echo('Invalid access to this page'), REFERRER);
}

$title = elgg_echo('agora:requests', [$entity->title]);
elgg_push_breadcrumb(elgg_echo('agora'), 'agora/all');
$container = $entity->getContainerEntity();
if ($container instanceof \ElggGroup) {
    elgg_push_breadcrumb($container->name, "agora/group/$container->guid");
} else {
    elgg_push_breadcrumb($container->name, "agora/owner/$container->username");
}
elgg_push_breadcrumb($entity->title, $entity->getURL());

echo elgg_view_page($title, [
    'content' => $entity->getRequests(true),
    'sidebar' => false,
    'filter' => false,
]);
