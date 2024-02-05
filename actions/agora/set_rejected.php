<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

use Agora\AgoraOptions;

$interest_guid = get_input('interest_guid');
if (!$interest_guid) { // if not interest guid
    $errmsg = elgg_echo('agora:set_rejected:interest_guid_missing');
}

elgg_call(ELGG_IGNORE_ACCESS, function () use ($interest_guid) {
    $interest = get_entity($interest_guid);
    if (!$interest instanceof \AgoraInterest) {
        $errmsg = elgg_echo('agora:set_rejected:interest_entity_missing');
    }
    
    // get classified entity
    $entity = get_entity($interest->int_ad_guid);
    if (!$entity instanceof \Agora) { 
        $errmsg = elgg_echo('agora:set_rejected:agora_entity_missing');
    }    

    if ($errmsg) {
        return elgg_error_response(elgg_echo($errmsg));
    } 
    else {
        if ($entity->canEdit()) {
            $interest->int_status = AgoraOptions::INTEREST_REJECTED;
    
            if ($interest->save()) {
                return elgg_ok_response('', elgg_echo('agora:set_rejected:success'), REFERRER);
            } 
            
            return elgg_error_response(elgg_echo('agora:set_rejected:failed'), REFERRER);
        } 
        
        return elgg_error_response(elgg_echo('agora:error:action:invalid'), REFERRER);
    }    
});
