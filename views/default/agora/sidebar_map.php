<?php
/**
 * Elgg Maps of Groups plugin
 * @package groupsmap 
 */

$entity = elgg_extract('entity', $vars);
$owner = $entity->getOwnerEntity();

$box_color = elgg_extract('box_color', $vars);

// If image support get the icon.
$icon = '';
$icon = elgg_view('output/url', array(
	'href' => $entity->getURL(),
	'text' => elgg_view('output/img', array( 'src' => agora_getImageUrl($entity, 'tiny'), 'class' => "elgg-photo")),
));
		
$output = '<div class="map_entity_block '.$box_color.'" onclick="myClick('.$entity->guid.')">';
$output .= $icon;
$output .= '<a class="arrow" onclick="myClick('.$entity->guid.')">'.$entity->title.'</a>';
$output .= '<br />'.$entity->location;
if ($entity->getVolatileData('distance_from_user')) {
	$output .= '<br />'.$entity->getVolatileData('distance_from_user');
}
$output .= '</div>';

echo $output;

