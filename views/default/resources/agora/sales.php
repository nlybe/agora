<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

elgg_gatekeeper();

$guid = elgg_extract('guid', $vars, '');
$entity = get_entity($guid);

if (!$entity instanceof Agora) { 
    elgg_error_response(elgg_echo('agora:error:invalid:entity'));
    forward(REFERRER);
}

if (!$entity->canEdit()) {
    elgg_error_response(elgg_echo('Invalid access to this page'));
    forward(REFERRER);
}

$title = elgg_echo('agora:sales', [$entity->title]);

elgg_push_breadcrumb(elgg_echo('agora'), 'agora/all');
$container = $entity->getContainerEntity();
if ($container instanceof \ElggGroup) {
    elgg_push_breadcrumb($container->name, "agora/group/$container->guid");
} else {
    elgg_push_breadcrumb($container->name, "agora/owner/$container->username");
}
elgg_push_breadcrumb($entity->title, $entity->getURL());

// load list buyers
$body = elgg_view_layout('default', [
    'filter' => '',
    'content' => $entity->getSales(true),
    'title' => $title,
]);

echo elgg_view_page($title, $body);
