<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

// chech if user is loggedin
if (!elgg_is_logged_in()) {
    register_error(elgg_echo("agora:error:action:invalid"));
    forward();
}

$guid = get_input('guid');
$entity = get_entity($guid);

if (!elgg_instanceof($entity, 'object', Agora::SUBTYPE) || !$entity->canEdit()) {
    register_error(elgg_echo("agora:error:action:invalid"));
    forward();
}

$container = $entity->getContainerEntity();

if ($entity->delete()) {
    system_message(elgg_echo("agora:delete:success"));
    if (elgg_instanceof($container, 'group')) {
        forward("agora/group/$container->guid/all");
    } else {
        forward("agora/owner/$container->username");
    }
}

forward();