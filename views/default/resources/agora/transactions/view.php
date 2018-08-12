<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

$guid = elgg_extract('guid', $vars, '');
//elgg_entity_gatekeeper($guid, 'object', AgoraSale::SUBTYPE);

$ia = elgg_get_ignore_access();
elgg_set_ignore_access(true);

$entity = get_entity($guid);
    
$title = $entity->title;

// get current user guid, if loggedin
$user = elgg_get_logged_in_user_entity();

elgg_set_ignore_access($ia);
echo elgg_view_entity($entity, ['full_view' => true ]);
