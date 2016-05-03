<?php
/**
 * Elgg Agora Classifieds plugin
 * @package Agora
 */

elgg_load_library('elgg:agora');

$full = elgg_extract('full_view', $vars, FALSE);
$item = elgg_extract('entity', $vars, FALSE);

$owner = $item->getOwnerEntity();
$owner_icon = elgg_view_entity_icon($owner, 'small');
$tu = $item->time_updated;

$author_text = elgg_echo('byline', array($owner->name));
$date = elgg_view_friendly_time($item->time_created);

$post = get_entity($item->txn_vguid);
$buyer = get_user($item->txn_buyer_guid);
$seller = get_entity($item->container_guid);

// don't show filter if out of filter context
if (elgg_instanceof($seller, 'group')) {
	$seller = get_user($item->owner_guid);
}

if ($post) {
	$content =  '';
	$agora_title = elgg_view('output/url', array(
		'href' => "agora/view/{$post->guid}/" . elgg_get_friendly_title($post->title),
		'text' => '<strong>'.$post->title.'</strong>',
	));
	
	$thumbnail = elgg_view('output/img', array(
		'src' => agora_getImageUrl($post, 'small'),
		'class' => "elgg-photo",
	));
	
	$agora_img = elgg_view('output/url', array(
		'href' => $post->getURL(),
		'text' => $thumbnail,
	));	
		
	$content = '<div>';
	$content .= $agora_title;
	$content .= '<br /><strong>'.elgg_echo('agora:settings:transactions:date').'</strong>: '.elgg_view_friendly_time($item->time_created);
	$content .= '&nbsp;&nbsp;<strong>'.elgg_echo('agora:settings:transactions:seller').'</strong>: <a href="'.elgg_get_site_url().'profile/'.$seller->username.'">'.$seller->username.'</a>';
	$content .= '&nbsp;&nbsp;<strong>'.elgg_echo('agora:settings:transactions:buyer').'</strong>: <a href="'.elgg_get_site_url().'profile/'.$buyer->username.'">'.$buyer->username.'</a>';
	if ($item->txn_method)
		$content .= '&nbsp;&nbsp;<strong>'.elgg_echo('agora:settings:transactions:method').'</strong>: '.$item->txn_method;
	$content .= '</div	>';


/*
$metadata = elgg_view_menu('entity', array(
	'entity' => $vars['entity'],
	'handler' => 'badge',
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz',
));
*/

	$params = array(
			'entity' => $item,
			//'metadata' => $metadata,
			'content' => $content,
	);

	$params = $params + $vars;
	$body = elgg_view('object/elements/summary', $params);
	
	echo elgg_view_image_block($agora_img, $body);
}
else {
	//$body = '';
}





