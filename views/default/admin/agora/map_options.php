<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

echo elgg_view('agora/admin/tabs', ['map_options_selected' => true]);

echo elgg_view_module('info', elgg_echo('admin:agora:map_options'), elgg_view_form('agora/admin/map_options'));
