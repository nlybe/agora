<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */
 
$type = elgg_get_plugin_setting('categories','agora');
$fields = explode(",", $type);
$fields = array_map('trim', $fields);

if (!is_array($fields)) {
	return;
}

$options = [ 0 => elgg_echo('agora:add:category:select')];
// $options[0] = elgg_echo('agora:add:category:select');
foreach($fields as $val) {
	$options[$val] = $val;
}

echo elgg_view_field([
	'#type' => 'select',
	'id' => elgg_extract("id", $vars),
	'name' => elgg_extract("name", $vars),
	'options_values' => $options,
	'value' => elgg_extract("value", $vars),
]);

