<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

elgg_load_library('elgg:agora');

$guid = elgg_extract('guid', $vars, '');
$entity = get_entity($guid);

if (!elgg_instanceof($entity, 'object', Agora::SUBTYPE) || !$entity->canEdit()) {
    register_error(elgg_echo('agora:error:access:invalid'));
    forward(REFERRER);
}

$page_owner = elgg_get_page_owner_entity();
$title = elgg_echo('agora:edit');

elgg_push_breadcrumb($entity->title, $entity->getURL());
elgg_push_breadcrumb($title);

$form_vars = array('name' => 'agoraForm', 'enctype' => 'multipart/form-data');

$vars = agora_prepare_form_vars($entity);
$content = elgg_view_form('agora/add', $form_vars, $vars);

$body = elgg_view_layout('content', array(
    'filter' => '',
    'content' => $content,
    'title' => $title,
));

echo elgg_view_page($title, $body);
