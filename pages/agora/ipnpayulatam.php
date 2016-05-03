<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

elgg_load_library('elgg:agora');

//error_log('-------------------------->'.$_POST['response_code_pol'].'<---');
//error_log('-------------------------->'.$_POST['reference_sale'].'<---');
//error_log('-------------------------->'.$_POST['extra1'].'<---');
//error_log('-------------------------->'.$_POST['extra2'].'<---');

// 1. Make sure the payment Transaction approved.
if ($_POST['response_code_pol'] == 1) { 
	
	if ($_POST['reference_sale'] && $_POST['extra1'] && $_POST['extra2'])  {
		$classfd_guid = $_POST['reference_sale'];     // ad guid
		$buyer_guid = $_POST['extra1'];        // buyer guid
		$classfd_owner_guid = $_POST['extra2'];        // ad owner guid
		
		// get buyer user settings
		$buyer_profil = get_user($buyer_guid);
			
		if ($buyer_profil)  {
			// login for retrieving entities and saving purchase
			login(get_entity($buyer_guid)); 

			//get ad entity
			$classfd = get_entity($classfd_guid);
			if (elgg_instanceof($classfd, 'object', 'agora')) {
			
				// 3. Make sure the amount(s) paid match
				if ($_POST['value'] != $classfd->get_ad_price_with_shipping_cost()) {
					$errmsg .= elgg_echo('agora:ipn:error2');
					$errmsg .= $_POST['value']."\n";
				}      

				// 4. Make sure the currency code matches
				if ($_POST['currency'] != $classfd->currency) {
					$errmsg .= elgg_echo('agora:ipn:error3');
					$errmsg .= $_POST['currency']."\n";
				}  

				// 5. Ensure the transaction is not a duplicate / this user hasn't buy this ad again
				$options = array(
						'type' => 'object',
						'subtype' => 'agorasales',
						'limit' => 0,
						'metadata_name_value_pairs' => array(
							array('name' => 'txn_vguid','value' => $classfd_guid, 'operand' => '='),
							array('name' => 'txn_buyer_guid', 'value' => $buyer_guid, 'operand' => '='),
						),
						'metadata_name_value_pairs_operator' => 'AND',
				);
				$getbuyers = elgg_get_entities_from_metadata($options);
				if ($getbuyers) { 
					$errmsg .= elgg_echo('agora:ipn:error4');
				}        


				if (!empty($errmsg)) {
					// send message to ad owner
					$email_body = "\n$errmsg\n\n";
					$email_body .= $listener->getTextReport();
					notify_user($classfd_owner_guid, $classfd_owner_guid, elgg_echo('agora:ipn:title'), $email_body);
				} else {
					$classfdsale = new ElggObject;
					$classfdsale->subtype = "agorasales";
					$classfdsale->access_id = 0;
					$classfdsale->save();

					// set object metadata
					$classfdsale->container_guid = $classfd_owner_guid;
					$classfdsale->owner_guid = $buyer_guid;
					$classfdsale->txn_vguid = $classfd_guid;
					$classfdsale->txn_buyer_guid = $buyer_guid;
					$classfdsale->txn_date = $_POST['transaction_date'];
					// obs $classfdsale->txn_id = $_POST['reference_sale'];
					$classfdsale->txn_id = $_POST['reference_pol'];
					$classfdsale->txn_method = AGORA_PURCHASE_METHOD_PAYULATAM;

					if ($classfdsale->save()) {
						// reduce available inits
						$available_units = $classfd->howmany;
						if ($available_units && is_numeric($available_units)) {
							$classfd->howmany = $available_units - 1;
							$classfd->save();
						}
						
						// notify seller
						$subject = elgg_echo('agora:paypal:sellersubject', array($buyer_profil->username));
						$message = '';
						$message .= '<p>'.elgg_echo('agora:paypal:buyeremail').': '.$_POST['email_buyer'].'</p>';
						$message .= '<p>'.elgg_echo('agora:paypal:country').': '.$_POST['billing_country'].'</p>';
						$message .= '<p>'.elgg_echo('agora:payulatam:name').': '.$_POST['nickname_buyer'].'</p>';
						$message .= '<p>'.elgg_echo('agora:paypal:mccurrency').': '.$_POST['currency'].'</p>';
						$message .= '<p>'.elgg_echo('agora:paypal:mcgross').': '.$_POST['value'].'</p>';
						$message .= '<p>'.elgg_echo('agora:paypal:paymentdate').': '.$_POST['transaction_date'].'</p>';
						$message .= '<p>'.elgg_echo('agora:paypal:paymentstatus').': '.$_POST['state_pol'].'</p>';
						$message .= '<p>'.elgg_echo('agora:add:title').': <a href="'.elgg_get_site_url().'agora/view/'.$classfd->guid.'">'.$_POST['description'].'</a></p>';
						$message .= '<p>'.elgg_echo('agora:buyerprofil').': <a href="'.elgg_get_site_url().'profile/'.$buyer_profil->username.'">'.$buyer_profil->username.'</a></p>';           
						notify_user($classfd_owner_guid, $buyer_guid, $subject, $message);
						
 					    // notify buyer
					    $notify_buyer = agora_notify_buyer_for_transaction($buyer_guid, $classfd);
						
						// notify users from settings
						$notify_users = agora_notify_users_for_transaction($classfd_owner_guid, $classfd);
					
					} else {
						$errmsg = elgg_echo('agora:ipn:error5');
					}
				}   
			}   // end of elgg_instanceof($classfd, 'object', 'agora')   
			else    {
				$errmsg = elgg_echo('agora:ipn:error8');
			}			

			logout();  // logout user
			
		}   // end of get_user($buyer_guid)
		else    {
			$errmsg = elgg_echo('agora:ipn:error6');
		}        
	}
	else    {
		$errmsg = elgg_echo('agora:ipn:error7');
		error_log(elgg_echo('agora:ipn:error7'));
	}

	if (!empty($errmsg)) {
		// send message to ad owner
		$email_body = "\n$errmsg\n\n";
		$email_body .= $listener->getTextReport();
		notify_user($classfd_owner_guid, $classfd_owner_guid, elgg_echo('agora:ipn:title'), $email_body);
	}
}
else  {
	error_log('Transaction failed, PayULatam error code: '.$_POST['response_code_pol']);
}














