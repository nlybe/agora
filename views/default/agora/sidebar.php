<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

use Agora\AgoraOptions;

$showall = elgg_extract('showall', $vars, true);

$selected = $vars['selected']!='all' ?'owner/'.$vars['selected']:'all';
$page = 'agora/'.$selected.'/';

// Categories
$type = elgg_get_plugin_setting('categories','agora');
$fields = explode(",", $type);

if ($showall) {
	$list .= elgg_format_element('li', ['class' => ($vars['category'] == 'all'?'elgg-state-selected':'')], elgg_view('output/url', [
		'href' => elgg_normalize_url($page),
		'text' => elgg_echo("agora:categories:all"),
		'text' => elgg_echo("agora:categories:all"),
	]));
}
foreach ($fields as $val){
	$key = elgg_get_friendly_title($val);
	if($key){
		$list .= elgg_format_element('li', ['class' => ($vars['category'] == $key?'elgg-state-selected':'')], elgg_view('output/url', [
			'href' => elgg_normalize_url($page.$key),
			'text' => $val,
			'text' => $val,
		]));
	}
}
echo elgg_view_module('aside', elgg_echo('agora:settings:categories'), elgg_format_element('ul', ['class' => 'elgg-menu elgg-menu-page elgg-menu-page-default'], $list));

// commends block
echo elgg_view('page/elements/comments_block', [
    'subtypes' => 'agora',
    'owner_guid' => elgg_get_page_owner_guid(),
]);

// tags block
echo elgg_view('page/elements/tagcloud_block', [
    'subtypes' => 'agora',
    'owner_guid' => elgg_get_page_owner_guid(),
]);



