<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */
 
$options = [];
$options['newest'] = elgg_echo('agora:add:category:newest');
$options['s_price_min'] = elgg_echo('agora:add:category:s_price_min');
$options['s_price_max'] = elgg_echo('agora:add:category:s_price_max');

echo elgg_view_field([
	'#type' => 'select',
	'id' => elgg_extract("id", $vars),
	'name' => elgg_extract("name", $vars),
	'options_values' => $options,
	'value' => elgg_extract("value", $vars),
	'onchange' => "this.form.submit()",
]);

