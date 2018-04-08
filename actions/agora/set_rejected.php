<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

$interest_guid = get_input('interest_guid');
if (!$interest_guid) { // if not interest guid
    $errmsg = elgg_echo('agora:set_rejected:interest_guid_missing');
}

// set ignore access for loading all entries
$ia = elgg_get_ignore_access();
elgg_set_ignore_access(true);

$interest = get_entity($interest_guid);
if (!elgg_instanceof($interest, 'object', AgoraInterest::SUBTYPE)) { // if not agora interest entity
    $errmsg = elgg_echo('agora:set_rejected:interest_entity_missing');
}

// get classified entity
$entity = get_entity($interest->int_ad_guid);
if (!elgg_instanceof($entity, 'object', Agora::SUBTYPE)) { // if not agora interest entity
    $errmsg = elgg_echo('agora:set_rejected:agora_entity_missing');
}

if ($errmsg) {
    register_error($errmsg);
} 
else {
    if ($entity->canEdit()) {
        $interest->int_status = AgoraOptions::INTEREST_REJECTED;

        if ($interest->save()) {
            system_message(elgg_echo("agora:set_rejected:success"));
        } else {
            register_error(elgg_echo("agora:set_rejected:failed"));
        }
    } else {
        register_error(elgg_echo("agora:error:action:invalid"));
    }
}

// restore ignore access
elgg_set_ignore_access($ia);

forward(REFERER);
