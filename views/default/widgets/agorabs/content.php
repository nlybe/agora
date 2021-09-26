<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

//the page owner
$owner = get_user($vars['entity']->owner_guid);

//the number of files to display
$num = (int) $vars['entity']->num_display;
if (!$num) {
    $num = 5;
}

// load list of bought items
$options_bought = [
    'type' => 'object',
    'subtype' => AgoraSale::SUBTYPE,
    'limit' => $num,
    'owner_guid' => $owner->guid,
];
$items_bought = elgg_get_entities($options_bought);

$item_guid = 0;
if (is_array($items_bought) && sizeof($items_bought) > 0) {
    foreach ($items_bought as $item) {
        $post = get_entity($item->container_guid);

        if ($post && $item_guid != $post->guid) {
            echo elgg_view_entity($post, ['full_view' => false]);
            $item_guid = $post->guid;
        }
    }
    
}

