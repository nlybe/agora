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

echo elgg_view_module('info', elgg_echo('admin:agora:transactions_log'), $content);


