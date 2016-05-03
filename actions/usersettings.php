<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

$user_guid = (int) get_input("user_guid", elgg_get_logged_in_user_guid());
$agora_paypal_account = get_input("agora_paypal_account");
$agora_payulatam_merchantId = get_input("agora_payulatam_merchantId");
$agora_payulatam_accountId = get_input("agora_payulatam_accountId");
$agora_payulatam_apikey = get_input("agora_payulatam_apikey");
$agora_payulatam_lang = get_input("agora_payulatam_lang");

if(!empty($user_guid)){
	if(($user = get_user($user_guid)) && $user->canEdit()){
		$error_count = 0;
		if(!empty($agora_paypal_account)){
			if(!($user->setPrivateSetting("agora_paypal_account", $agora_paypal_account))){
				$error_count++;
			}				
		} 
		else {
			$user->removePrivateSetting("agora_paypal_account");
		}	
		
		if(!empty($agora_payulatam_merchantId)){
			if(!($user->setPrivateSetting("agora_payulatam_merchantId", $agora_payulatam_merchantId))){
				$error_count++;
			}				
		} 
		else {
			$user->removePrivateSetting("agora_payulatam_merchantId");
		}	
		
		if(!empty($agora_payulatam_accountId)){
			if(!($user->setPrivateSetting("agora_payulatam_accountId", $agora_payulatam_accountId))){
				$error_count++;
			}				
		} 
		else {
			$user->removePrivateSetting("agora_payulatam_accountId");
		}	
		
		if(!empty($agora_payulatam_apikey)){
			if(!($user->setPrivateSetting("agora_payulatam_apikey", $agora_payulatam_apikey))){
				$error_count++;
			}				
		} 
		else {
			$user->removePrivateSetting("agora_payulatam_apikey");
		}
		
		if(!empty($agora_payulatam_apikey)){
			if(!($user->setPrivateSetting("agora_payulatam_lang", $agora_payulatam_lang))){
				$error_count++;
			}				
		} 
		else {
			$user->removePrivateSetting("agora_payulatam_lang");
		}
			
		if($error_count == 0){
			system_message(elgg_echo("agora:usersettings:update:success"));
		} else {
			register_error(elgg_echo("agora:usersettings:update:error"));
		}
	} else {
		register_error(elgg_echo("InvalidClassException:NotValidElggStar", array($user_guid, "ElggUser")));
	}
} else {
	register_error(elgg_echo("InvalidParameterException:MissingParameter"));
}	

forward(REFERER);
	
