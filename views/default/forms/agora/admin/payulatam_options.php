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

// enable/disable payulatam gateway
$payulatam_enabled = $plugin->payulatam_enabled;
if(empty($payulatam_enabled)){
	$payulatam_enabled = AGORA_GENERAL_YES;
}    

$payulatam_enabled_output = elgg_view('input/dropdown', array('name' => 'params[payulatam_enabled]', 'value' => $payulatam_enabled, 'options_values' => $potential_yes_no));
$payulatam_enabled_output .= "<span class='elgg-subtext'>" . elgg_echo('agora:settings:payulatam_enabled:note') . "</span>";
echo elgg_view_module("inline", elgg_echo('agora:settings:payulatam_enabled'), $payulatam_enabled_output);

// payulatam merchant id
$payulatam_merchantId = elgg_view('input/text', array('name' => 'params[payulatam_merchantId]', 'value' => $plugin->payulatam_merchantId));
$payulatam_merchantId .= "<span class='elgg-subtext'>" . elgg_echo('agora:settings:payulatam_merchantId:note') . "</span>";
echo elgg_view_module("inline", elgg_echo('agora:settings:payulatam_merchantId'), $payulatam_merchantId);

// payulatam merchant id
$payulatam_accountId = elgg_view('input/text', array('name' => 'params[payulatam_accountId]', 'value' => $plugin->payulatam_accountId));
$payulatam_accountId .= "<span class='elgg-subtext'>" . elgg_echo('agora:settings:payulatam_accountId:note') . "</span>";
echo elgg_view_module("inline", elgg_echo('agora:settings:payulatam_accountId'), $payulatam_accountId);

// payulatam api key
$payulatam_apikey = elgg_view('input/text', array('name' => 'params[payulatam_apikey]', 'value' => $plugin->payulatam_apikey));
$payulatam_apikey .= "<span class='elgg-subtext'>" . elgg_echo('agora:settings:payulatam_apikey:note') . "</span>";
echo elgg_view_module("inline", elgg_echo('agora:settings:payulatam_apikey'), $payulatam_apikey);

// paylatam default language
$potential_langs = get_payulatam_langs(); 
$payulatam_lang = $plugin->payulatam_lang;
if(empty($payulatam_lang)){
	$payulatam_lang = 'es';
}    

$payulatam_lang_output = elgg_view('input/dropdown', array('name' => 'params[payulatam_lang]', 'value' => $payulatam_lang, 'options_values' => $potential_langs));
$payulatam_lang_output .= "<span class='elgg-subtext'>" . elgg_echo('agora:settings:payulatam_lang:note') . "</span>";
echo elgg_view_module("inline", elgg_echo('agora:settings:payulatam_lang'), $payulatam_lang_output);

// set if use paypal sandbox
$payulatam_testmode = $plugin->payulatam_testmode;
if(empty($payulatam_testmode)){
        $payulatam_testmode = AGORA_GENERAL_NO;
}    
 
$payulatam_testmode = elgg_view('input/dropdown', array('name' => 'params[payulatam_testmode]', 'value' => $payulatam_testmode, 'options_values' => $potential_yes_no));
$payulatam_testmode .= "<span class='elgg-subtext'>" . elgg_echo('agora:settings:payulatam_testmode:note') . "</span>";
echo elgg_view_module("inline", elgg_echo('agora:settings:payulatam_testmode'), $payulatam_testmode);

echo elgg_view('input/submit', array('value' => elgg_echo("save")));
