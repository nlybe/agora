<?php
/**
 * Elgg Agora Classifieds plugin
 * @package Agora
 */

elgg_gatekeeper();

// check if user can post classifieds
if (!AgoraOptions::canUserPostClassifieds()) { 
    return elgg_error_response(elgg_echo('agora:add:noaccessforpost'));
}

elgg_push_breadcrumb(elgg_echo('agora'), 'agora/all');
$page_owner = elgg_get_page_owner_entity();
if (elgg_instanceof($page_owner, 'group')) {
    elgg_push_breadcrumb($page_owner->name, "agora/group/$page_owner->guid");
} else {
    elgg_push_breadcrumb($page_owner->name, "agora/owner/$page_owner->username");
}

$title = elgg_echo('agora:add');
elgg_push_breadcrumb($title);

$form_vars = array('name' => 'agoraForm', 'enctype' => 'multipart/form-data');
$vars = agora_prepare_form_vars();
$content = elgg_view_form('agora/add', $form_vars, $vars);

$body = elgg_view_layout('content', array(
    'content' => $content,
    'title' => $title,
    'sidebar' => '',
    'filter' => '',
));

echo elgg_view_page($title, $body);
