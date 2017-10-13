<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */
 
elgg_load_library('elgg:agora');

$user = elgg_extract("user", $vars);

if(!empty($user) && $user->canEdit()){

	// start making the form
	$form_body = '<br />';

	if (check_if_all_members_can_post_classifieds()) {		// enable payment gateways only if all members can post classifieds (in admin area settings)
		if (agora_check_if_paypal_is_enabled()) { 
			$agora_paypal_account = $user->getPrivateSetting("agora_paypal_account");
			$form_body .= '
				<div class="elgg-module elgg-module-info">
					<div class="elgg-head">
						<h3>'.elgg_echo("agora:usersettings:paypal_settings").'</h3>
					</div>
					<div id="elgg-body">
						<label>'. elgg_echo("agora:usersettings:paypal").':</label>
						<span class="custom_fields_more_info" id="more_info_paypal"></span>
						<span class="custom_fields_more_info_text" id="text_more_info_paypal">
							' . elgg_echo("agora:usersettings:paypal:note").'
						</span>
						'.elgg_view("input/text", array("name" => "agora_paypal_account", "value" => $agora_paypal_account, "class" => "medium")).'
					</div>	
				</div>
			';
		}
			
	}

	if (!empty($form_body)) {
		$form_body .= "<div class=''>";
		$form_body .= elgg_view("input/hidden", array("name" => "user_guid", "value" => $user->getGUID()));
		$form_body .= elgg_view("input/submit", array("value" => elgg_echo("save")));
		$form_body .= "</div>";
		
		echo elgg_view("input/form", array("body" => $form_body, "action" => "action/agora/usersettings", "class" => "elgg-form-alt", 'enctype' => 'multipart/form-data'));
	} else {
		echo elgg_echo("agora:usersettings:no_settings");
	}
}
else {
	echo elgg_echo("agora:usersettings:no_fornormaluseryet");
}
	
?>
