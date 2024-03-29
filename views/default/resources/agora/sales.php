<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

elgg_gatekeeper();

$guid = elgg_extract('guid', $vars, '');
$entity = get_entity($guid);

if (!$entity instanceof \Agora) { 
    return elgg_error_response(elgg_echo('agora:error:invalid:entity'), REFERRER);
}

if (!$entity->canEdit()) {
    return elgg_error_response(elgg_echo('Invalid access to this page'), REFERRER);
}

elgg_push_breadcrumb(elgg_echo('agora'), 'agora/all');
$container = $entity->getContainerEntity();
if ($container instanceof \ElggGroup) {
    elgg_push_breadcrumb($container->name, "agora/group/$container->guid");
} else {
    elgg_push_breadcrumb($container->name, "agora/owner/$container->username");
}
elgg_push_breadcrumb($entity->title, $entity->getURL());

echo elgg_view_page(elgg_echo('agora:sales', [$entity->title]), [
    'content' => $entity->getSales(true),
    'sidebar' => false,
    'filter' => false,
]);
