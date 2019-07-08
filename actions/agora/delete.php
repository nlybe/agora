<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

// chech if user is loggedin
if (!elgg_is_logged_in()) {
    return elgg_error_response(elgg_echo('agora:error:action:invalid'));
}

$guid = get_input('guid');
$entity = get_entity($guid);

if (!elgg_instanceof($entity, 'object', Agora::SUBTYPE) || !$entity->canEdit()) {
    return elgg_error_response(elgg_echo('agora:error:action:invalid'));
}

$container = $entity->getContainerEntity();

if ($entity->delete()) {
    if (elgg_instanceof($container, 'group')) {
        $forward_url = "agora/group/$container->guid";
    } else {
        $forward_url = "agora/owner/$container->username";
    }
    return elgg_ok_response('', elgg_echo('agora:delete:success'), $forward_url);
}

forward();