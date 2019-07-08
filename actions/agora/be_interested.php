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
    return elgg_error_response(elgg_echo('agora:be_interested:failed'));
}

if (!$recipient_guid) {
    return elgg_error_response(elgg_echo('messages:user:blank'));
}

$user = get_user($recipient_guid);
if (!$user) {
    return elgg_error_response(elgg_echo('messages:user:nonexist'));
}

// Make sure the message field, send to field and title are not blank
if (!$body || !$subject) {
    return elgg_error_response(elgg_echo('messages:blank'));
}

// Otherwise, 'send' the message 
$body = elgg_echo("agora:be_interested:adtitle", array($agora->getURL(), $agora->title)) . '<br /><br />' . $body;
$result = messages_send($subject, $body, $recipient_guid, 0, $reply);

// Save 'send' the message
if (!$result) {
    return elgg_error_response(elgg_echo('messages:error'));
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
        return elgg_ok_response('', elgg_echo('agora:be_interested:success'), REFERER);
    } else {
        return elgg_error_response(elgg_echo('agora:be_interested:error'));
    }
}

forward(REFERER);


