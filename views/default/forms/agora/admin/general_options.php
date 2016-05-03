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

// set categories
$categories = elgg_view('input/text', array('name' => 'params[categories]', 'value' => $plugin->categories));
$categories .= "<span class='elgg-subtext'>" . elgg_echo('agora:settings:categories:note') . "</span>";
echo elgg_view_module("inline", elgg_echo('agora:settings:categories'), $categories);

// set default currency
$default_currency = $plugin->default_currency;
if(empty($default_currency)){
        $defaultdateformat = 'EUR';
}        

// get currency list	
$CurrOptions = get_all_currencies();
$currency = elgg_view('input/dropdown', array('name' => 'params[default_currency]', 'value' => $default_currency, 'options_values' => $CurrOptions));
$currency .= "<span class='elgg-subtext'>" . elgg_echo('agora:settings:defaultcurrency:note') . "</span>";
echo elgg_view_module("inline", elgg_echo('agora:settings:defaultcurrency'), $currency);


// set timezone
$default_timezone = $plugin->default_timezone;
if(empty($default_timezone)){
	$defaulttimezone = 'UTC';
}   

// get timezones list	
$timezones_list = agora_get_all_times_zones();
$timezone = elgg_view('input/dropdown', array('name' => 'params[default_timezone]', 'value' => $default_timezone, 'options_values' => $timezones_list));
$timezone .= "<span class='elgg-subtext'>" . elgg_echo('agora:settings:defaulttimezone:note') . "</span>";
echo elgg_view_module("inline", elgg_echo('agora:settings:defaulttimezone'), $timezone);  
  

// set who can post classifieds
$agora_uploaders = $plugin->agora_uploaders;
if(empty($agora_uploaders)){
        $agora_uploaders = 'allmembers';
}    
$agora_potential_uploaders = array(
    "admins" => elgg_echo('agora:settings:uploaders:admins'),
    "allmembers" => elgg_echo('agora:settings:uploaders:allmembers'),
); 

$uploaders = elgg_view('input/dropdown', array('name' => 'params[agora_uploaders]', 'value' => $agora_uploaders, 'options_values' => $agora_potential_uploaders));
$uploaders .= "<span class='elgg-subtext'>" . elgg_echo('agora:settings:uploaders:note') . "</span>";
echo elgg_view_module("inline", elgg_echo('agora:settings:uploaders'), $uploaders);

// set if user can buy more than once each ad
$multiple_ad_purchase = $plugin->multiple_ad_purchase;
if(empty($multiple_ad_purchase)){
	$multiple_ad_purchase = AGORA_GENERAL_NO;
}    
$multiple_ad_purchase_output = elgg_view('input/dropdown', array('name' => 'params[multiple_ad_purchase]', 'value' => $multiple_ad_purchase, 'options_values' => $potential_yes_no));
$multiple_ad_purchase_output .= "<span class='elgg-subtext'>" . elgg_echo('agora:settings:multiple_ad_purchase:note') . "</span>";
echo elgg_view_module("inline", elgg_echo('agora:settings:multiple_ad_purchase'), $multiple_ad_purchase_output);

// set if HTML tags are allowed on post description
$html_allowed = $plugin->html_allowed;
if(empty($html_allowed)){
	$html_allowed = AGORA_GENERAL_NO;
}    
$html_allowed_output = elgg_view('input/dropdown', array('name' => 'params[html_allowed]', 'value' => $html_allowed, 'options_values' => $potential_yes_no));
$html_allowed_output .= "<span class='elgg-subtext'>" . elgg_echo('agora:settings:html_allowed:note') . "</span>";
echo elgg_view_module("inline", elgg_echo('agora:settings:html_allowed'), $html_allowed_output);

// set maximum number of images in ad gallery
$max_images = $plugin->max_images;
if(empty($max_images)){
	$max_images = AGORA_GENERAL_NO;
}    
$max_images_output = elgg_view('input/text', array('name' => 'params[max_images]', 'value' => (intval($plugin->max_images) > 0?intval($plugin->max_images):AGORA_MAX_IMAGES_GALLERY), 'style' => 'width:50px; margin: 3px 0;'));
$max_images_output .= "<span class='elgg-subtext'>" . elgg_echo('agora:settings:max_images:note') . "</span>";
echo elgg_view_module("inline", elgg_echo('agora:settings:max_images'), $max_images_output);

// enable reviews and ratings only for buyers
$buyers_comrat = $plugin->buyers_comrat;
if(empty($buyers_comrat)){
	$buyers_comrat = AGORA_GENERAL_NO;
}    
$buyers_comrat_output = elgg_view('input/dropdown', array('name' => 'params[buyers_comrat]', 'value' => $buyers_comrat, 'options_values' => $potential_yes_no));
$buyers_comrat_output .= "<span class='elgg-subtext'>" . elgg_echo('agora:settings:buyers_comrat:note') . "</span>";
$buyers_comrat_output .= "<br />";
$buyers_comrat_output .= "<span class=''>" . elgg_echo('agora:settings:buyers_comrat_expire') . "</span>";
$buyers_comrat_output .= elgg_view('input/text', array('name' => 'params[buyers_comrat_expire]', 'value' => (intval($plugin->buyers_comrat_expire) > 0?intval($plugin->buyers_comrat_expire):AGORA_COMRAT_EXPIRATION_DAYS), 'style' => 'width:50px; margin: 3px 0;'));
$buyers_comrat_output .= "<span class='elgg-subtext'>" . elgg_echo('agora:settings:buyers_comrat_expire:note') . "</span>";
$buyers_comrat_output .= "<br />";
$buyers_comrat_output .= "<span class=''>" . elgg_echo('agora:settings:buyers_comrat_notify') . "</span>";
$buyers_comrat_output .= elgg_view('input/text', array('name' => 'params[buyers_comrat_notify]', 'value' => (intval($plugin->buyers_comrat_notify) > 0?intval($plugin->buyers_comrat_notify):AGORA_COMRAT_NOTIFICATION_DAYS), 'style' => 'width:50px; margin: 3px 0;'));
$buyers_comrat_output .= "<span class='elgg-subtext'>" . elgg_echo('agora:settings:buyers_comrat_notify:note') . "</span>";
$buyers_comrat_output .= "<br />";
$buyers_comrat_output .= "<span class=''>" . elgg_echo('agora:settings:buyers_comrat_notify_by') . "</span>";
$buyers_comrat_output .= elgg_view('input/text', array('name' => 'params[buyers_comrat_notify_by]', 'value' => $plugin->buyers_comrat_notify_by, 'style' => 'width:100px; margin: 3px 0;'));
$buyers_comrat_output .= "<span class='elgg-subtext'>" . elgg_echo('agora:settings:buyers_comrat_notify_by:note') . "</span>";
echo elgg_view_module("inline", elgg_echo('agora:settings:buyers_comrat'), $buyers_comrat_output);

// set if members can send private message to seller
$send_message = $plugin->send_message;
if(empty($send_message)){
	$send_message = AGORA_GENERAL_YES;
}    
$send_message_output = elgg_view('input/dropdown', array('name' => 'params[send_message]', 'value' => $send_message, 'options_values' => $potential_yes_no));
$send_message_output .= "<span class='elgg-subtext'>" . elgg_echo('agora:settings:send_message:note') . "</span>";
echo elgg_view_module("inline", elgg_echo('agora:settings:send_message'), $send_message_output);

// set users to notify for each transaction
$users_to_notify = elgg_view('input/text', array('name' => 'params[users_to_notify]', 'value' => $plugin->users_to_notify));
$users_to_notify .= "<span class='elgg-subtext'>" . elgg_echo('agora:settings:users_to_notify:note') . "</span>";
echo elgg_view_module("inline", elgg_echo('agora:settings:users_to_notify'), $users_to_notify);

// terms of use
$terms_of_use = elgg_view('input/longtext', array('name' => 'params[terms_of_use]', 'value' => $plugin->terms_of_use));
$terms_of_use .= "<span class='elgg-subtext'>" . elgg_echo('agora:settings:terms_of_use:note') . "</span>";
echo elgg_view_module("inline", elgg_echo('agora:settings:terms_of_use'), $terms_of_use);

echo elgg_view('input/submit', array('value' => elgg_echo("save")));
