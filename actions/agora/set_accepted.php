<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

use Agora\AgoraOptions;

// set the default timezone to use
date_default_timezone_set(AgoraOptions::getDefaultTimezone());

$interest_guid = get_input('interest_guid');
if (!$interest_guid) { // if not interest guid
    $errmsg = elgg_echo('agora:set_accepted:interest_guid_missing');
}

elgg_call(ELGG_IGNORE_ACCESS, function () use ($interest_guid) {
    $interest = get_entity($interest_guid);
    if (!$interest instanceof \AgoraInterest) {
        $errmsg = elgg_echo('agora:set_accepted:interest_entity_missing');
    }
    
    // get classified entity
    $entity = get_entity($interest->int_ad_guid);
    if (!$entity instanceof \Agora) {   // if not agora interest entity    
        $errmsg = elgg_echo('agora:set_accepted:agora_entity_missing');
    }
    
    //get buyer entity
    $buyer = get_user($interest->int_buyer_guid);
    if (!($buyer instanceof \ElggUser)) { // if not user entity
        $errmsg = elgg_echo('agora:set_accepted:user_entity_missing');
    }
    
    if ($errmsg) {
        return elgg_error_response(elgg_echo($errmsg));
    } 
    else if ($entity->canEdit()) {
        $sale = new AgoraSale();
        $sale->access_id = ACCESS_PRIVATE;
        $sale->owner_guid = $buyer->guid;
        $sale->container_guid = $entity->guid;
        $sale->title = $entity->title;
        $sale->transaction_id = 'Offline-' . $entity->guid . '-' . $interest->int_buyer_guid;
        $sale->txn_method = AgoraOptions::PURCHASE_METHOD_OFFLINE;
        $sale->buyer_name = $buyer->name;
        $sale->bill_number = AgoraSale::getNewInvoiceNumber();
        
        if ($sale->save()) {
            $interest->int_status = AgoraOptions::INTEREST_ACCEPTED;
            if ($interest->save()) {
                // reduce availability
                if (is_numeric($entity->howmany) && $entity->howmany>0) {
                    $entity->reduceItems();
                }        
    
                // notify seller
                $subject = elgg_echo('agora:paypal:sellersubject', [$buyer->username]);
                $message = '<p>' . elgg_echo('agora:paypal:accepteddate') . ': ' . $transaction_date . '</p>';
                $message .= '<p>' . elgg_echo('agora:add:title') . ': <a href="' . elgg_get_site_url() . 'agora/view/' . $entity->guid . '">' . $entity->title . '</a></p>';
                $message .= '<p>' . elgg_echo('agora:buyerprofil') . ': <a href="' . elgg_get_site_url() . 'profile/' . $buyer->username . '">' . $buyer->username . '</a></p>';
                notify_user($entity->owner_guid, $buyer->guid, $subject, $message);
    
                // notify buyer
                $subject = elgg_echo('agora:paypal:buyersubject', [$entity->title]);
                $message = '<p>' . elgg_echo('agora:paypal:buyerbody') . '</p>';
                $message .= '<p>' . elgg_echo('agora:paypal:title') . ': <a href="' . elgg_get_site_url() . 'agora/view/' . $entity->guid . '">' . $entity->title . '</a></p>';
                notify_user($buyer->guid, $entity->owner_guid, $subject, $message);
    
                // notify admins
                $users_to_notify = AgoraOptions::getUserToNotify();
                $subject = elgg_echo('agora:paypal:buyersubject', [$entity->title]);
                $message = '<p>' . elgg_echo('agora:paypal:buyerbody') . '</p>';
                $message .= '<p>' . elgg_echo('agora:paypal:title') . ': <a href="' . elgg_get_site_url() . 'agora/view/' . $entity->guid . '">' . $entity->title . '</a></p>';
                
                $users_to_notify_guids = [];
                foreach ($users_to_notify as $val) {
                    $user_to_notify = get_user_by_username(trim($val));
                    if ($user_to_notify) {
                        $users_to_notify_guids[] = $user_to_notify->guid;
                    }
                }  
                notify_user($users_to_notify_guids, $entity->owner_guid, $subject, $message);
    
                return elgg_ok_response('', elgg_echo('agora:set_accepted:success'), REFERRER);
            } 
                
            return elgg_error_response(elgg_echo('agora:set_accepted:failed'),REFERRER);
        } 
        
        return elgg_error_response(elgg_echo('agora:error:offline:failed'),REFERRER);
    } 
    
    return elgg_error_response(elgg_echo('agora:error:action:invalid'), REFERRER);
});
