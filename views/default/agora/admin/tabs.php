<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

$tabs = [];

$tabs[] = [
	'text' => elgg_echo('admin:agora:basic_options'),
    'href' => '/admin/plugin_settings/agora',
	'selected' => elgg_extract('basic_options_selected', $vars, false),
];

$tabs[] = [
    'text' => elgg_echo('admin:agora:paypal_options'),
    'href' => 'admin/agora/paypal_options',
	'selected' => elgg_extract('paypal_options_selected', $vars, false),
];

// $tabs[] = [
//     'text' => elgg_echo('admin:agora:map_options'),
//     'href' => 'admin/agora/map_options',
// 	'selected' => elgg_extract('map_options_selected', $vars, false),
// ];

$tabs[] = [
    'text' => elgg_echo('admin:agora:ratings_options'),
    'href' => 'admin/agora/ratings_options',
	'selected' => elgg_extract('ratings_options_selected', $vars, false),
];

$tabs[] = [
    'text' => elgg_echo('admin:agora:digital_options'),
    'href' => 'admin/agora/digital_options',
	'selected' => elgg_extract('digital_options_selected', $vars, false),
];

$tabs[] = [
    'text' => elgg_echo('admin:agora:transactions_log'),
    'href' => 'admin/agora/transactions_log',
	'selected' => elgg_extract('transactions_log_selected', $vars, false),
];

echo elgg_view('navigation/tabs', ['tabs' => $tabs]);
