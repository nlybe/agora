<?php
/**
 * Elgg Agora Classifieds plugin
 * @package Agora
 */

$recipient_guid = elgg_extract('recipient_guid', $vars, 0);
$subject = elgg_extract('subject', $vars, '');
$body = elgg_extract('body', $vars, '');
$classified_guid = elgg_extract('classified_guid', $vars, '');

echo elgg_view_field([
	'#type' => 'text',
	'name' => 'body',
	'value' => $body,
	'class' => 'beinterested',
	'#label' => elgg_echo("messages:message"),
]);

echo elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('agora:interest:send'),
]);

echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'subject',
	'value' => $subject,  
]);
echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'recipient_guid',
	'value' => $recipient_guid,
]);
echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'classified_guid',
	'value' => $classified_guid,
]);
	
