<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

use Agora\AgoraOptions;

elgg_push_context('widgets');

// set the default timezone to use
date_default_timezone_set(AgoraOptions::getDefaultTimezone());

// the page owner
$owner = get_entity($vars['entity']->owner_guid);
if ($owner instanceof \ElggUser) {
    $url = "agora/owner/{$owner->username}";
} else {
    $url = "agora/group/{$owner->guid}/all";
}

//the number of files to display
$num = (int) $vars['entity']->num_display;
if (!$num) {
    $num = 5;
}

$options = [
    'type' => 'object',
    'subtype' => 'agora',
    'container_guid' => $vars['entity']->owner_guid,
    'limit' => $num,
    'full_view' => false,
    'pagination' => false,
];
$content = elgg_list_entities($options);

if (!$content) {
    $content = elgg_format_element('p', [], elgg_echo('agora:none'));
}
echo $content;

$more_link = elgg_view('output/url', [
    'href' => $url,
    'text' => elgg_echo("agora:widget:viewall"),
    'is_trusted' => true,
]);
echo elgg_format_element('span', ['class' => 'elgg-widget-more'], $more_link);

