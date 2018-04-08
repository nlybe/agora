<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

// set the default timezone to use
date_default_timezone_set(AgoraOptions::getDefaultTimezone());

$subject = strip_tags(get_input('subject'));
$body = get_input('body');
$recipient_guid = get_input('recipient_guid');
$classified_guid = get_input('classified_guid');
$agora = get_entity($classified_guid);

elgg_make_sticky_form('messages');

if (!elgg_instanceof($agora, 'object', Agora::SUBTYPE)) {
    register_error(elgg_echo("agora:be_interested:failed"));
    forward(REFERER);
}

if (!$recipient_guid) {
    register_error(elgg_echo("messages:user:blank"));
    forward("messages/compose");
}

$user = get_user($recipient_guid);
if (!$user) {
    register_error(elgg_echo("messages:user:nonexist"));
    forward("messages/compose");
}

// Make sure the message field, send to field and title are not blank
if (!$body || !$subject) {
    register_error(elgg_echo("messages:blank"));
    forward(REFERER);
}

// Otherwise, 'send' the message 
$body = elgg_echo("agora:be_interested:adtitle", array($agora->getURL(), $agora->title)) . '<br /><br />' . $body;
$result = messages_send($subject, $body, $recipient_guid, 0, $reply);

// Save 'send' the message
if (!$result) {
    register_error(elgg_echo("messages:error"));
    forward(REFERER);
} 
else {
    elgg_clear_sticky_form('messages');

    // save interest transaction
    $entity = new ElggObject;
    $entity->subtype = AgoraInterest::SUBTYPE;
    $entity->access_id = 0;
    $entity->save();

    // set object metadata
    $entity->container_guid = $agora->container_guid;
    $entity->owner_guid = elgg_get_logged_in_user_guid();
    $entity->int_ad_guid = $agora->guid;
    $entity->int_buyer_guid = elgg_get_logged_in_user_guid();
    $entity->int_status = AgoraOptions::INTEREST;
    $entity->int_message_guid = $result;

    if ($entity->save()) {
        system_message(elgg_echo("agora:be_interested:success"));
        system_message(elgg_echo("agora:be_interested:success_message"));
    } else {
        register_error(elgg_echo("agora:be_interested:error"));
        forward(REFERER);
    }
}

forward(REFERER);


