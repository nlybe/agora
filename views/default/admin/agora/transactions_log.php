<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

echo elgg_view('agora/admin/tabs', ['transactions_log_selected' => true]);

$content = elgg_list_entities([
    'type' => 'object',
    'subtype' => AgoraSale::SUBTYPE,
]);

// OBS
// $title = elgg_echo('agora:settings:tabs:transactions_log');
// $body = elgg_view_layout('one_column', array(
//     'filter_context' => 'all',
//     'content' => $content,
//     'title' => $title,
//     'sidebar' => false,
//     'filter_override' => '',
// ));

echo elgg_view_module('info', elgg_echo('admin:agora:transactions_log'), $content);


