<?php
/**
 * Elgg Agora Classifieds plugin
 * @package Agora
 */

elgg_load_library('elgg:agora');

// set the default timezone to use
date_default_timezone_set(agora_get_default_timezone());

$interest_guid = get_input('interest_guid');
if (!$interest_guid) {	// if not interest guid
	$errmsg = elgg_echo('agora:set_accepted:interest_guid_missing');
}

// set ignore access for loading all entries
$ia = elgg_get_ignore_access();
elgg_set_ignore_access(true);

$interest = get_entity($interest_guid);
if (!elgg_instanceof($interest, 'object', 'agorainterest')) {	// if not agora interest entity
	$errmsg = elgg_echo('agora:set_accepted:interest_entity_missing');
}

// get classified entity
$classfd = get_entity($interest->int_ad_guid);
if (!elgg_instanceof($classfd, 'object', 'agora')) {	// if not agora interest entity
	$errmsg = elgg_echo('agora:set_accepted:agora_entity_missing');
}

//get buyer entity
$buyer_profil = get_user($interest->int_buyer_guid);
if (!elgg_instanceof($buyer_profil, 'user')) {	// if not user entity
	$errmsg = elgg_echo('agora:set_accepted:user_entity_missing');
}

if ($errmsg)	{
	register_error($errmsg);
}
else
{
	if ($classfd->canEdit())	{
		$classfdsale = new ElggObject;
		$classfdsale->subtype = "agorasales";
		$classfdsale->access_id = 0;
		$classfdsale->save();

		$transaction_date = date('Y-m-d H:i:s');
		
		// set object metadata
		$classfdsale->container_guid = $classfd->owner_guid;
		$classfdsale->owner_guid = $buyer_profil->guid;
		$classfdsale->txn_vguid = $classfd->guid;
		$classfdsale->txn_buyer_guid = $buyer_profil->guid;
		$classfdsale->txn_date = $transaction_date;
		$classfdsale->txn_id = 'Offline-'.$classfd->guid.'-'.$interest->int_buyer_guid;
		$classfdsale->txn_method = AGORA_PURCHASE_METHOD_OFFLINE;
		
		if ($classfdsale->save()) {
			$interest->int_status = AGORA_INTEREST_ACCEPTED;
			if ($interest->save()) {
				system_message(elgg_echo('agora:set_accepted:success'));
			}
			else {
				register_error(elgg_echo('agora:set_accepted:failed'));
			}		
			
			// reduce available inits
			$available_units = $classfd->howmany;
			if ($available_units && is_numeric($available_units)) {
				$classfd->howmany = $available_units - 1;
				$classfd->save();
			}	
			
			// notify seller
			$subject = elgg_echo('agora:paypal:sellersubject', array($buyer_profil->username));
			$message = '';
			$message .= '<p>'.elgg_echo('agora:paypal:accepteddate').': '.$transaction_date.'</p>';
			$message .= '<p>'.elgg_echo('agora:add:title').': <a href="'.elgg_get_site_url().'agora/view/'.$classfd->guid.'">'.$classfd->title.'</a></p>';
			$message .= '<p>'.elgg_echo('agora:buyerprofil').': <a href="'.elgg_get_site_url().'profile/'.$buyer_profil->username.'">'.$buyer_profil->username.'</a></p>';           
			notify_user($classfd->owner_guid, $buyer_profil->guid, $subject, $message);
			
		    // notify buyer
			$notify_buyer = agora_notify_buyer_for_transaction($buyer_profil->guid, $classfd);
			
			// notify users from settings
			$message = elgg_echo('agora:paypal:sellersubject', array('<a href="'.elgg_get_site_url().'profile/'.$buyer_profil->username.'">'.$buyer_profil->username.'</a>')).$message;
			$notify_users = agora_notify_users_for_transaction($classfd->owner_guid, $classfd);
			
		} else {
			$errmsg = elgg_echo('agora:ipn:error5');
		}	
	}
	else  {
		register_error(elgg_echo("agora:set_rejected:novalidaccess"));
	}

}

// restore ignore access
elgg_set_ignore_access($ia);

forward(REFERER);
