<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

$tab = get_input('tab', 'general_options');

echo elgg_view('navigation/tabs', array(
	'tabs' => array(
		array(
			'text' => elgg_echo('agora:settings:tabs:general_options'),
			'href' => '/admin/settings/agora',
			'selected' => ($tab == 'general_options'),
		),
		array(
			'text' => elgg_echo('agora:settings:tabs:paypal_options'),
			'href' => '/admin/settings/agora?tab=paypal_options',
			'selected' => ($tab == 'paypal_options'),
		),
		array(		
			'text' => elgg_echo('agora:settings:tabs:payulatam_options'),
			'href' => '/admin/settings/agora?tab=payulatam_options',
			'selected' => ($tab == 'payulatam_options'),
		),			
		array(
			'text' => elgg_echo('agora:settings:tabs:map_options'),
			'href' => '/admin/settings/agora?tab=map_options',
			'selected' => ($tab == 'map_options'),
		),
		array(
			'text' => elgg_echo('agora:settings:tabs:digital_options'),
			'href' => '/admin/settings/agora?tab=digital_options',
			'selected' => ($tab == 'digital_options'),
		),		
		array(
			'text' => elgg_echo('agora:settings:tabs:transactions_log'),
			'href' => '/admin/settings/agora?tab=transactions_log',
			'selected' => ($tab == 'transactions_log'),
		),		
	)
));

switch ($tab) {
	case 'paypal_options':
		echo elgg_view('admin/settings/agora/paypal_options');
		break;

	case 'payulatam_options':
		echo elgg_view('admin/settings/agora/payulatam_options');
		break;		

	case 'map_options':
		echo elgg_view('admin/settings/agora/map_options');
		break;
		
	case 'digital_options':
		echo elgg_view('admin/settings/agora/digital_options');
		break;		

	case 'transactions_log':
		echo elgg_view('admin/settings/agora/transactions_log');
		break;
		
	default:
	case 'general_options':
		echo elgg_view('admin/settings/agora/general_options');
		break;
}
