<?php
/**
 * Elgg Agora Classifieds plugin
 * @package Agora
 */

elgg_load_library('elgg:agora');

$object = $vars['item']->getObjectEntity();
$excerpt = elgg_get_excerpt($object->description);

$thumbnail = elgg_view('output/img', array(
	'src' => agora_getImageUrl($object, 'small'),
	'class' => "elgg-photo",
));

$ad_img = elgg_view('output/url', array(
	'href' => $object->getURL(),
	'text' => $thumbnail,
));
	
$message = '<div style="float:left; margin-right: 5px;">'.$ad_img.'</div>'.$excerpt;


echo elgg_view('river/elements/layout', array(
	'item' => $vars['item'],
	'message' => $message,
	'attachments' => elgg_view('output/url', array('href' => $object->address)),
));

