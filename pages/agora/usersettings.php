<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */
	
gatekeeper();

$username = get_input("username");

if(!empty($username)){
	$user = get_user_by_username($username);
} else {
	$user = elgg_get_logged_in_user_entity();
}

if(!empty($user) && $user->canEdit()){
	// set correct context
	elgg_push_context("settings");
	
	// make breadcrumb
	elgg_push_breadcrumb(elgg_echo("settings"), "settings/user/" . $user->username);
	elgg_push_breadcrumb(elgg_echo("agora:usersettings:settings"));
	
	// set page owner
	elgg_set_page_owner_guid($user->getGUID());
	
	// build page elements
	$title_text = elgg_echo("agora:usersettings:title");
	
    $body = elgg_view("forms/agora/usersettings", array("user" => $user));
	
	// build page
	$params = array(
		"title" => $title_text,
		"content" => $body
	);
	
	// draw page
	echo elgg_view_page($title_text, elgg_view_layout("one_sidebar", $params));
	
	// reset context
	elgg_pop_context();
} else {
	register_error(elgg_echo("agora:usersettings:error:user"));
	forward();
}
	
