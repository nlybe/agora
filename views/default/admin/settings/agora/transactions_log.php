<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

$content = elgg_list_entities([
    'type' => 'object',
    'subtype' => AgoraSale::SUBTYPE,
]);

$title = elgg_echo('agora:settings:tabs:transactions_log');
$body = elgg_view_layout('one_column', array(
    'filter_context' => 'all',
    'content' => $content,
    'title' => $title,
    'sidebar' => false,
    'filter_override' => '',
));

echo $body;


