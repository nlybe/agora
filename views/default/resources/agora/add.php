<?php
/**
 * Elgg Agora Classifieds plugin
 * @package Agora
 */

elgg_load_library('elgg:agora');

// check if user can post classifieds
if (!AgoraOptions::canUserPostClassifieds()) { 
    register_error(elgg_echo('agora:add:noaccessforpost'));  
    forward(REFERER);      
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




