<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

$guid = elgg_extract('guid', $vars, '');

elgg_call(ELGG_IGNORE_ACCESS, function () use ($guid) {

    $entity = get_entity($guid);
    
    echo elgg_view_entity($entity, ['full_view' => true ]);
});