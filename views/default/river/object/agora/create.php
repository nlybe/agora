<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

elgg_load_library('elgg:agora');

$object = $vars['item']->getObjectEntity();
$excerpt = elgg_get_excerpt($object->description);

$icon = elgg_view_entity_icon($object, 'medium', ['img_class' => 'elgg-photo']);
$message = elgg_format_element('div', ['style' => 'float:left; margin-right: 5px;'], $icon).$excerpt;

echo elgg_view('river/elements/layout', array(
    'item' => $vars['item'],
    'message' => $message,
    'attachments' => elgg_view('output/url', array('href' => $object->address)),
));

