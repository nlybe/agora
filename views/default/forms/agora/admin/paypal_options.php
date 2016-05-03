<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

elgg_load_library('elgg:agora');

$plugin = elgg_get_plugin_from_id('agora');

$potential_yes_no = array(
    AGORA_GENERAL_YES => elgg_echo('agora:settings:yes'),
    AGORA_GENERAL_NO => elgg_echo('agora:settings:no'),
); 

// enable/disable paypal gateway
$paypal_enabled = $plugin->paypal_enabled;
if(empty($paypal_enabled)){
        $paypal_enabled = AGORA_GENERAL_YES;
}    

$paypal_enabled_output = elgg_view('input/dropdown', array('name' => 'params[paypal_enabled]', 'value' => $paypal_enabled, 'options_values' => $potential_yes_no));
$paypal_enabled_output .= "<span class='elgg-subtext'>" . elgg_echo('agora:settings:paypal_enabled:note') . "</span>";
echo elgg_view_module("inline", elgg_echo('agora:settings:paypal_enabled'), $paypal_enabled_output);


// paypal account
$paypal_account = elgg_view('input/text', array('name' => 'params[paypal_account]', 'value' => $plugin->paypal_account));
$paypal_account .= "<span class='elgg-subtext'>" . elgg_echo('agora:settings:paypal_account:note') . "</span>";
echo elgg_view_module("inline", elgg_echo('agora:settings:paypal_account'), $paypal_account);

// set if use paypal sandbox
$usesandbox = $plugin->usesandbox;
if(empty($usesandbox)){
        $usesandbox = AGORA_GENERAL_NO;
}    
 
$usesandbox = elgg_view('input/dropdown', array('name' => 'params[usesandbox]', 'value' => $usesandbox, 'options_values' => $potential_yes_no));
$usesandbox .= "<span class='elgg-subtext'>" . elgg_echo('agora:settings:sandbox:note') . "</span>";
echo elgg_view_module("inline", elgg_echo('agora:settings:sandbox'), $usesandbox);

if (elgg_is_active_plugin("amap_paypal_api"))	{

	// adaptive payments enable option
	$agora_adaptive_payments = $plugin->agora_adaptive_payments;
	if(empty($agora_adaptive_payments)){
			$agora_adaptive_payments = 'no';
	}   

	$en_agora_adaptive_payments = elgg_view('input/dropdown', array('name' => 'params[agora_adaptive_payments]', 'value' => $agora_adaptive_payments, 'options_values' => $potential_yes_no));
	$en_agora_adaptive_payments .= "<span class='elgg-subtext'>" . elgg_echo('agora:paypal:adaptive_payments:note', array(elgg_get_site_url()."admin/plugin_settings/amap_paypal_api")) . "</span>";
	$en_agora_adaptive_payments .= "<p style='margin: 10px 0 0 0;'>" . elgg_echo('agora:paypal:agora_adaptive_payments_commission');
	$en_agora_adaptive_payments .= elgg_view('input/text', array('name' => 'params[agora_adaptive_payments_commission]', 'value' => $plugin->agora_adaptive_payments_commission, 'style' => 'width: 50px;'));
	$en_agora_adaptive_payments .= " (%)  <span class='elgg-subtext'>".elgg_echo('agora:paypal:agora_adaptive_payments_commission:note')."</span></p>";
	
	echo elgg_view_module("inline", elgg_echo('agora:paypal:adaptive_payments'), $en_agora_adaptive_payments);
	
	echo '<div>'.elgg_echo('agora:paypal:agora_adaptive_payments_important').'</div>';
}	
	

echo elgg_view('input/submit', array('value' => elgg_echo("save")));
