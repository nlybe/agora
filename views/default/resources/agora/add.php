<?php
/**
 * Elgg Agora Classifieds plugin
 * @package Agora
 */

use Agora\AgoraOptions;

elgg_gatekeeper();

// check if user can post classifieds
if (!AgoraOptions::canUserPostClassifieds()) { 
    return elgg_error_response(elgg_echo('agora:add:noaccessforpost'));
}

elgg_push_breadcrumb(elgg_echo('agora'), 'agora/all');
$page_owner = elgg_get_page_owner_entity();
if ($page_owner instanceof \ElggGroup) {
    elgg_push_breadcrumb($page_owner->name, "agora/group/$page_owner->guid");
} else {
    elgg_push_breadcrumb($page_owner->name, "agora/owner/$page_owner->username");
}

$title = elgg_echo('agora:add');
elgg_push_breadcrumb($title);

$form_vars = ['name' => 'agoraForm', 'enctype' => 'multipart/form-data'];
$vars = agora_prepare_form_vars();
$content = elgg_view_form('agora/add', $form_vars, $vars);

$body = elgg_view_layout('default', [
    'content' => $content,
    'title' => $title,
    'sidebar' => '',
    'filter' => '',
]);

echo elgg_view_page($title, $body);
