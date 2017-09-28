<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

$tabs = array(
	'all' => array(
			'title' => elgg_echo('agora:label:all'),
			'url' => 'agora/all',
			'selected' => $vars['selected'] == 'all',
	),
);
   
if (elgg_is_logged_in()) {
	$user = elgg_get_logged_in_user_entity(); // get current user
	
	$filter_context = '';
	if ($vars['page_owner_guid'] == elgg_get_logged_in_user_guid()) {
		$selected = 'owner';
	}	
	
	$tabs['owner'] = array(
		'title' => elgg_echo('agora:label:owner'),
		'url' => 'agora/owner/'.$user->username,
		'selected' => $selected,
	);
	$tabs['friends'] = array(
		'title' => elgg_echo('agora:label:friends'),
		'url' => 'agora/friends/'.$user->username,
		'selected' => $vars['selected'] == 'friends',
	);	

}

if (is_geolocation_enabled()) {
	$user = elgg_get_logged_in_user_entity(); // get current user
	$tabs['map'] = array(
		'title' => elgg_echo('agora:label:map'),
		'url' => 'agora/map',
		'selected' => $vars['selected'] == 'map',
	);
}

if (elgg_is_logged_in()) {
	$tabs['mypurchases'] = array(
		'title' => elgg_echo('agora:label:mypurchases'),
		'url' => 'agora/mypurchases/'.$user->username,
		'selected' => $vars['selected'] == 'mypurchases',
	);		
}

echo elgg_view('navigation/tabs', array('tabs' => $tabs));
