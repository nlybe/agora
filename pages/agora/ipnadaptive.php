<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */
 
// https://developer.paypal.com/docs/classic/adaptive-payments/integration-guide/APIPN/
// https://ccaples.com/mts/sdks/new_95/adaptivepayments-php-1.2.95_0/samples/

elgg_load_library('elgg:agora');
elgg_load_library('elgg:agora:ipnlistener');

// initiate the IPN listener
$listener = new IpnListener();
$listener->force_ssl_v3 = false; // POODLE security fix

// tell IPN listener to check if use sandbox paypal account
$usesandbox = trim(elgg_get_plugin_setting('usesandbox', 'agora'));
if ($usesandbox === 'yes')   {
    $listener->use_sandbox = true;
}
else {
    $listener->use_sandbox = false;
}

$errmsg = '';   // stores errors from fraud checks

// try to process the IPN POST
try {
    $listener->requirePostMethod();
    $verified = $listener->processIpn();
} catch (Exception $e) {
    $errmsg = $e->getMessage();
    exit(0);
}
	
if ($verified) {

    // 1. Make sure the payment status is "Completed" 
    if ($_POST['status'] != 'COMPLETED') { 
        // simply ignore any IPN that is not completed
        $errmsg = elgg_echo('agora:ipn:error1');
        //exit(0); 
    } 
/*
error_log('--->'.$_POST['transaction_type'].'<---');
error_log('--->'.$_POST['status'].'<---');
error_log('--->'.$_POST['memo'].'<---');
error_log('--->'.$_POST['trackingId'].'<---');
error_log('--->'.$_POST['payment_request_date'].'<---');
error_log('--->'.$_POST['sender_email'].'<---');
error_log('--->'.$errmsg.'<---');
error_log('g--->'.$_GET['trckd'].'<---');
*/

    if ($_GET['trckd'])  {
        // $pieces = explode("-", $_POST['memo']);
        $pieces = explode("-", $_GET['trckd']);
        $classfd_guid = $pieces[0];     // ad guid
        $buyer_guid = $pieces[1];        // buyer guid
        $classfd_owner_guid = $pieces[2];        // ad owner guid
        
        // get buyer user settings
        $buyer_profil = get_user($buyer_guid);
        if ($buyer_profil)  {
            // login for retrieving entities and saving purchase
            login(get_entity($buyer_guid)); 

            //get ad entity
            $classfd = get_entity($classfd_guid);

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
                $classfdsale->txn_date = $_POST['payment_request_date'];
                $classfdsale->txn_id = $_POST['pay_key'];
                $classfdsale->txn_method = AGORA_PAYPAL_METHOD_ADAPTIVE;

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
                    $message .= '<p>'.elgg_echo('agora:paypal:buyeremail').': '.$_POST['sender_email'].'</p>';
                    $message .= '<p>'.elgg_echo('agora:paypal:mccurrency').': '.$classfd->currency.'</p>';
                    $message .= '<p>'.elgg_echo('agora:paypal:mcgross').': '.$classfd->get_ad_price_with_shipping_cost().'</p>';
                    $message .= '<p>'.elgg_echo('agora:paypal:paymentdate').': '.$_POST['payment_request_date'].'</p>';
                    $message .= '<p>'.elgg_echo('agora:paypal:paymentstatus').': '.$_POST['status'].'</p>';
                    $message .= '<p>'.elgg_echo('agora:add:title').': <a href="'.elgg_get_site_url().'agora/view/'.$classfd->guid.'">'.$classfd->title.'</a></p>';
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

            logout();  // logout user
            
        }   // end of get_user($buyer_guid)
        else    {
            $errmsg = elgg_echo('agora:ipn:error6');
        }        
    }
    else    {
        $errmsg = elgg_echo('agora:ipn:error7');
    }
    
    if (!empty($errmsg)) {
        // send message to ad owner
        $email_body = "\n$errmsg\n\n";
        $email_body .= $listener->getTextReport();
        notify_user($classfd_owner_guid, $classfd_owner_guid, elgg_echo('agora:ipn:title'), $email_body);
    }
} else {
    // manually investigate the invalid IPN
    // $email_body = $listener->getTextReport();
    // notify_user($classfd_owner_guid, $classfd_owner_guid, elgg_echo('agora:ipn:title'), $email_body);
    exit(0);
}
