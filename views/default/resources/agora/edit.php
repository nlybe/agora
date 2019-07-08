<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

elgg_gatekeeper();

$guid = elgg_extract('guid', $vars, '');
$entity = get_entity($guid);

if (!elgg_instanceof($entity, 'object', Agora::SUBTYPE) || !$entity->canEdit()) {
    elgg_error_response(elgg_echo('agora:error:access:invalid'));
    forward(REFERRER);
}

$page_owner = elgg_get_page_owner_entity();
$title = elgg_echo('agora:edit');

elgg_push_breadcrumb(elgg_echo('agora'), 'agora/all');
if (elgg_instanceof($page_owner, 'group')) {
    elgg_push_breadcrumb($page_owner->name, "agora/group/$page_owner->guid");
} else {
    elgg_push_breadcrumb($page_owner->name, "agora/owner/$page_owner->username");
}
elgg_push_breadcrumb($entity->title, $entity->getURL());

$form_vars = array('name' => 'agoraForm', 'enctype' => 'multipart/form-data');

$vars = agora_prepare_form_vars($entity);
$content = elgg_view_form('agora/add', $form_vars, $vars);

$body = elgg_view_layout('content', array(
    'filter' => '',
    'content' => $content,
    'title' => $title,
));

echo elgg_view_page($title, $body);
