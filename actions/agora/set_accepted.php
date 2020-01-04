<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

// set the default timezone to use
date_default_timezone_set(AgoraOptions::getDefaultTimezone());

$interest_guid = get_input('interest_guid');
if (!$interest_guid) { // if not interest guid
    $errmsg = elgg_echo('agora:set_accepted:interest_guid_missing');
}

// set ignore access for loading all entries
$ia = elgg_get_ignore_access();
elgg_set_ignore_access(true);

$interest = get_entity($interest_guid);
if (!elgg_instanceof($interest, 'object', AgoraInterest::SUBTYPE)) { // if not agora interest entity
    $errmsg = elgg_echo('agora:set_accepted:interest_entity_missing');
}

// get classified entity
$classfd = get_entity($interest->int_ad_guid);
if (!elgg_instanceof($classfd, 'object', Agora::SUBTYPE)) { // if not agora interest entity
    $errmsg = elgg_echo('agora:set_accepted:agora_entity_missing');
}

//get buyer entity
$buyer = get_user($interest->int_buyer_guid);
if (!($buyer instanceof \ElggUser)) { // if not user entity
    $errmsg = elgg_echo('agora:set_accepted:user_entity_missing');
}

if ($errmsg) {
    // restore ignore access
    elgg_set_ignore_access($ia);
    return elgg_error_response(elgg_echo($errmsg));
} 
else if ($classfd->canEdit()) {
    $entity = new AgoraSale();
    $entity->subtype = AgoraSale::SUBTYPE;
    $entity->access_id = ACCESS_PRIVATE;
    $entity->owner_guid = $buyer->guid;
    $entity->container_guid = $classfd->guid;
    $entity->title = $classfd->title;
//    $entity->description = serialize($transactions_params);
    $entity->transaction_id = 'Offline-' . $classfd->guid . '-' . $interest->int_buyer_guid;
    $entity->txn_method = AgoraOptions::PURCHASE_METHOD_OFFLINE;
    $entity->buyer_name = $buyer->name;
    $entity->bill_number = AgoraSale::getNewInvoiceNumber();
    
    if ($entity->save()) {
        $interest->int_status = AgoraOptions::INTEREST_ACCEPTED;
        if ($interest->save()) {
            // reduce availability
            if (is_numeric($classfd->howmany) && $classfd->howmany>0) {
                $classfd->howmany--;
            }        

            // notify seller
            $subject = elgg_echo('agora:paypal:sellersubject', array($buyer->username));
            $message = '<p>' . elgg_echo('agora:paypal:accepteddate') . ': ' . $transaction_date . '</p>';
            $message .= '<p>' . elgg_echo('agora:add:title') . ': <a href="' . elgg_get_site_url() . 'agora/view/' . $classfd->guid . '">' . $classfd->title . '</a></p>';
            $message .= '<p>' . elgg_echo('agora:buyerprofil') . ': <a href="' . elgg_get_site_url() . 'profile/' . $buyer->username . '">' . $buyer->username . '</a></p>';
            notify_user($classfd->owner_guid, $buyer->guid, $subject, $message);

            // notify buyer
            $subject = elgg_echo('agora:paypal:buyersubject', array($classfd->title));
            $message = '<p>' . elgg_echo('agora:paypal:buyerbody') . '</p>';
            $message .= '<p>' . elgg_echo('agora:paypal:title') . ': <a href="' . elgg_get_site_url() . 'agora/view/' . $classfd->guid . '">' . $classfd->title . '</a></p>';
            notify_user($buyer->guid, $classfd->owner_guid, $subject, $message);

            // notify admins
            $users_to_notify = AgoraOptions::getUserToNotify();
            $subject = elgg_echo('agora:paypal:buyersubject', array($classfd->title));
            $message = '<p>' . elgg_echo('agora:paypal:buyerbody') . '</p>';
            $message .= '<p>' . elgg_echo('agora:paypal:title') . ': <a href="' . elgg_get_site_url() . 'agora/view/' . $classfd->guid . '">' . $classfd->title . '</a></p>';
            
            $users_to_notify_guids = [];
            foreach ($fields as $val) {
                $user_to_notify = get_user_by_username(trim($val));
                if ($user_to_notify) {
                    $users_to_notify_guids[] = $user_to_notify->guid;
                }
            }  
            notify_user($users_to_notify_guids, $classfd->owner_guid, $subject, $message);

            // restore ignore access
            elgg_set_ignore_access($ia);
    
            return elgg_ok_response('', elgg_echo('agora:set_accepted:success'), REFERER);
        } else {
            // restore ignore access
            elgg_set_ignore_access($ia);
    
            return elgg_error_response(elgg_echo('agora:set_accepted:failed'));
        }        
    } 
    else {
        // restore ignore access
        elgg_set_ignore_access($ia);

        return elgg_error_response(elgg_echo('agora:error:offline:failed'));
    }     
} 
else {
    // restore ignore access
    elgg_set_ignore_access($ia);
    
    return elgg_error_response(elgg_echo('agora:error:action:invalid'));
}

forward(REFERER);
