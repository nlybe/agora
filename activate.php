<?php
/**
 * Elgg Agora Classifieds plugin
 * @package Agora
 */

$subtypes = array(
	'agora' => 'Agora',
	'agoraimg' => 'AgoraImage',
	'agorasales' => 'Sales',
	'agorainterest' => 'Interest',
);

foreach ($subtypes as $subtype => $class) {
	if (!update_subtype('object', $subtype, $class)) {
		add_subtype('object', $subtype, $class);
	}
}
