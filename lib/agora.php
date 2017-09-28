<?php
/**
 * Elgg Agora Classifieds plugin
 * @package Agora
 */

//add classifieds form parameters
function agora_prepare_form_vars($classfd = null) {
	// input names => defaults
	$values = array(
		'title' => '',
		'description' => '',
		'access_id' => ACCESS_DEFAULT,
		'tags' => '',
		'container_guid' => elgg_get_page_owner_guid(),
		'entity' => $classfd,
		'price' => 0,
		'price_final' => 0,
		'currency' => '',
		'category' => '',
		'howmany' => '',
		'location' => '',
		'digital' => '',
		'tax_cost' => '',
		'shipping_cost' => '',
		'shipping_type' => '',
		'climage' => '',
		'guid' => null,
		'comments_on' => NULL,
	); 
	
	if ($classfd) {
		foreach (array_keys($values) as $field) {
			if (isset($classfd->$field)) {
					$values[$field] = $classfd->$field;
			}
		}
	}

	if (elgg_is_sticky_form('agora')) {
            $sticky_values = elgg_get_sticky_values('agora');
            foreach ($sticky_values as $key => $value) {
                $values[$key] = $value;
            }
	}

	elgg_clear_sticky_form('agora');

	return $values;
}

/**
 * Check if user can post cads
 *
 * @param object $user User object
 * @return true if current can post ads
 */
function check_if_user_can_post_classifieds($user = null) {
    $whocanpost = trim(elgg_get_plugin_setting('agora_uploaders', 'agora'));
    
    if (elgg_is_logged_in())    {
        if ($whocanpost === 'allmembers')   {
            return true;
        }
        else if ($whocanpost === 'admins')   {
            if (!$user) $user = elgg_get_logged_in_user_entity();
            if ($user->isAdmin()) {
                return true;
            } 
        }
    }
    
    return false;
}

/**
 * Check if all members can post ads
 * 
 * @return true if all members can post ads
 */
function check_if_all_members_can_post_classifieds() {
    $whocanpost = trim(elgg_get_plugin_setting('agora_uploaders', 'agora'));
    
    if (elgg_is_logged_in())    {
        if ($whocanpost === 'allmembers') 
            return true;
    }
    
    return false;
}

// check if posts for digital products are allowed
function digital_products_allowed() {
    $get_ads_digital = trim(elgg_get_plugin_setting('ads_digital', 'agora'));
    
	if(empty($get_ads_digital)) {
		return false;
	}  
	else if ($get_ads_digital == 'no') {
		return false;
	}  
    
    return $get_ads_digital;
}
  
// Currencies list according paypal api
function get_agora_paypal_currency_list() {
    $CurrOptions = array(
        'AUD'=>'Australian Dollar',
        'BRL'=>'Brazilian Real',
        'CAD'=>'Canadian Dollar',
        'CZK'=>'Czech Koruna',
        'DKK'=>'Danish Krone',
        'EUR'=>'Euro',
        'HKD'=>'Hong Kong Dollar',
        'HUF'=>'Hungarian Forint',
        'ILS'=>'Israeli New Sheqel',
        'JPY'=>'Japanese Yen',
        'MYR'=>'Malaysian Ringgit',
        'MXN'=>'Mexican Peso',
        'NOK'=>'Norwegian Krone',
        'NZD'=>'New Zealand Dollar',
        'PHP'=>'Philippine Peso',
        'PLN'=>'Polish Zloty',
        'GBP'=>'Pound Sterling',
        'SGD'=>'Singapore Dollar',
        'SEK'=>'Swedish Krona',
        'CHF'=>'Swiss Franc',
        'TWD'=>'Taiwan New Dollar',
        'THB'=>'Thai Baht',
        'TRY'=>'Turkish Lira',
        'USD'=>'U.S. Dollar',
    );
    
    return $CurrOptions;
}

// Currencies list according PayULatam api
function get_agora_payulatam_currency_list() {
    $CurrOptions = array(
        'ARS'=>'Argentenian Peso',
        'BRL'=>'Brazilian Real',
        'COP'=>'Colombian Peso',
        'MXN'=>'Mexican Peso',
        'USD'=>'U.S. Dollar',
        'PEN'=>'Peruvian New Sol',
    );
    
    return $CurrOptions;
}

function get_agora_currency_sign($currency_code) {
    switch ($currency_code) {
        case "AUD":
            return "$";
            break;  
        case "ARS":
            return "$";
            break;              
        case "BRL":
            return "R$";
            break;  
        case "CAD":
            return "$";
            break;  
        case "CZK":
            return "Kč";
            break;  
        case "DKK":
            return "kr";
            break;  
        case "EUR":
            return "€";
            break;  
        case "HKD":
            return "$";
            break;  
        case "HUF":
            return "Ft";
            break;  
        case "ILS":
            return "₪";
            break; 
        case "JPY":
            return "¥";
            break; 
        case "MYR":
            return "RM";
            break; 
        case "MXN":
            return "$";
            break; 
        case "NOK":
            return "kr";
            break;         
        case "NZD":
            return "$";
            break;   
        case "PEN":
            return "S/. ";
            break;                 
        case "PHP":
            return "₱";
            break;   
        case "PLN":
            return "zł";
            break;   
        case "GBP":
            return "£";
            break;   
        case "SGD":
            return "$";
            break;   
        case "SEK":
            return "kr";
            break;   
        case "CHF":
            return "CHF";
            break;   
        case "TWD":
            return "NT$";
            break;   
        case "THB":
            return "฿";
            break;   
        case "TRY":
            return "TRY";
            break;   
        case "USD":
            return "$";
            break; 
        case "COP":				
            return "COP $";
            break; 				
        default:
            return "$";
    }   
}

// Get settings parameters
function agora_settings($name ='categories', $null = true){
	$type = elgg_get_plugin_setting($name,'agora');
	$fields = explode(",", $type);
	if($null){
		$field_values[NULL] = elgg_echo('agora:add:category:select');
	}
	foreach ($fields as $val){
		$key = elgg_get_friendly_title($val);
		if($key){
			$field_values[$key] = $val;
		}
	}
	return $field_values;
}

// Get category title
function agora_get_cat_name_settings($catname = null, $linked = false){
	$type = elgg_get_plugin_setting('categories','agora');
	$fields = explode(",", $type);
	foreach ($fields as $val){
		$key = elgg_get_friendly_title($val);
		if($key == $catname){
			if ($linked)	{
				$page = 'agora/all/';
				return '<a class="elgg-menu-item" href="'.elgg_get_site_url().$page.$key.'" title="">'.$val.'</a>';
			}
			else
				return $val;
		}
	}
	return null;
}

// check if admin has set terms of use
function check_if_admin_terms_classifieds() {
    $terms_of_use = trim(elgg_get_plugin_setting('terms_of_use', 'agora'));
    
	if (!empty($terms_of_use) && $terms_of_use !=null)   {
		return true;
	}
	
    return false;
}

// check if members can send private message to seller
function check_if_members_can_send_private_message() {
    $send_message = trim(elgg_get_plugin_setting('send_message', 'agora'));
    
    if ($send_message === AGORA_GENERAL_YES)   {
		return true;
	}
	
    return false;
}

// check if geolocation is enabled
function is_geolocation_enabled() {
    $ads_geolocation = trim(elgg_get_plugin_setting('ads_geolocation', 'agora'));
    
    if ($ads_geolocation === 'yes')   {
		return true;
	}
	
    return false;
}

// get ad user interest status
function get_ad_user_interest_status($status) {
        
    if ($status === AGORA_INTEREST_ACCEPTED)   {
		return '<span style="color:green;">('.elgg_echo('agora:interest:accepted').')</span>';
	}
	else if ($status === AGORA_INTEREST_REJECTED)   {
		return '<span style="color:red;">('.elgg_echo('agora:interest:rejected').')</span>';
	}
	
    return '';
}

// general purpose trim function
function agora_trim_value(&$value)
{ 
    $value = trim($value);
}

// check if user has purchased a specific ad
function check_if_user_purchased_this_ad($classfd_guid, $user_guid) {
	$options = array(
		'type' => 'object',
		'subtype' => 'agorasales',
		'limit' => 0,
		'metadata_name_value_pairs' => array(
			array('name' => 'txn_vguid','value' => $classfd_guid, 'operand' => '='),
			array('name' => 'txn_buyer_guid', 'value' => $user_guid, 'operand' => '='),
		),
		'metadata_name_value_pairs_operator' => 'AND',
	);

	$getbuyers = elgg_get_entities_from_metadata($options);

	return $getbuyers;
}

// check if user has commented a specific ad
function check_if_user_commented_this_ad($classfd_guid, $user_guid) {
	$noComments = 0;
	$options = array(
		'type' => 'object',
		'subtype' => 'comment',
		'container_guid' => $classfd_guid,
		'owner_guid' => $user_guid,
		'count' => true,
	);

	$noComments = elgg_get_entities($options);
	
	return $noComments;
}

// check if multiple purchase of same product is allowed
function multiple_ad_purchase_enabled() {
    $multiple_ad_purchase = trim(elgg_get_plugin_setting('multiple_ad_purchase', 'agora'));
    
    if ($multiple_ad_purchase === 'yes')   {
		return true;
	}
	
    return false;
}

// check if html tags on desctription are allowed
function agora_html_allowed() {
    $html_allowed = trim(elgg_get_plugin_setting('html_allowed', 'agora'));
    
    if ($html_allowed === 'yes')   {
		return true;
	}
	
    return false;
}

// check if html tags on desctription are allowed
function agora_get_ad_description($description) {
    if (!$description)
		return false;
    
    if (agora_html_allowed())
		return $description;
	else
		return strip_tags($description);
}

// check if user has purchased a specific ad
function get_digital_filename($classfd_guid) {
	$file_ext = 'agora/file-'.$classfd_guid.'.zip';
	$options = array(
			'type' => 'object',
			'limit' => 0,
			'metadata_name_value_pairs' => array(
				array('name' => 'agora_guid', 'value' => $classfd_guid, 'operand' => '='),
				array('name' => 'filename', 'value' => $file_ext, 'operand' => '='),
			),
			'metadata_name_value_pairs_operator' => 'AND',
	);

	$files = elgg_get_entities_from_metadata($options);

	if (!$files) {
		return false;
	}

	if (count($files) > 0) { 
		$file = get_entity($files[0]->guid);
		return $file->originalfilename;
	}
	
	return false;
}

// get MD5 hash
function get_MD5_hash($ApiKey, $merchantId, $referenceCode, $amount, $currency) {
	$txtstring = $ApiKey.'~'.$merchantId.'~'.$referenceCode.'~'.$amount.'~'.$currency;

	return md5($txtstring);
}

// check if use sandbox paypal account
function agora_use_sandbox_paypal($method) {
	$usesandbox = trim(elgg_get_plugin_setting('usesandbox', 'agora'));
	if ($usesandbox === 'yes' && $method == AGORA_PAYPAL_METHOD_SIMPLE)   {
		return 'data-env="sandbox"';
	}
	if ($usesandbox === 'yes' && $method == AGORA_PAYPAL_METHOD_ADAPTIVE)   {
		return true;
	}	
	
	return '';
}

// check if use payulatam testmode
function use_testmode_payulatam() {
	$usesandbox = trim(elgg_get_plugin_setting('payulatam_testmode', 'agora'));
	if ($usesandbox === 'yes')   {
		return 1;
	}

	return 0;
}

// get payulatam url, depending if it's testmode or not
function get_payulatam_submiturl() {
	$usesandbox = trim(elgg_get_plugin_setting('payulatam_testmode', 'agora'));
	if ($usesandbox === 'yes')   {
		return 'https://stg.gateway.payulatam.com/ppp-web-gateway/';
	}

	return 'https://gateway.payulatam.com/ppp-web-gateway';
}

// get payulatam available langs
function get_payulatam_langs() {
	$potential_langs = array(
		'en' => elgg_echo('agora:payulatam:english'),
		'es' => elgg_echo('agora:payulatam:spanish'),
		'pt' => elgg_echo('agora:payulatam:portugues'),
	); 

	return $potential_langs;
}

// check if Paypal gateway is enabled
function agora_check_if_paypal_is_enabled() {
    $use_paypal = trim(elgg_get_plugin_setting('paypal_enabled', 'agora'));

    if ($use_paypal === AGORA_GENERAL_YES) 
		return true;
	
    return false;
}

// check if PayU Latam gateway is enabled
function agora_check_if_payulatam_is_enabled() {
    $use_payulatam = trim(elgg_get_plugin_setting('payulatam_enabled', 'agora'));

    if ($use_payulatam === AGORA_GENERAL_YES) 
		return true;
	
    return false;
}

// get a list of all available currencies, according payment gateways
function get_all_currencies() {
	$currency_paypal = get_agora_paypal_currency_list();   
	$currency_payulatam = get_agora_payulatam_currency_list();   
	$CurrOptions = array_merge($currency_paypal, $currency_payulatam);
	asort($CurrOptions); // sort list alphabetically
	
    return $CurrOptions;
}

// get a list of all timezones
function agora_get_all_times_zones() {
	$zones_array = array();
	$timestamp = time();

	foreach(timezone_identifiers_list() as $key => $zone) {
		$zones_array[$zone] = $zone;

		//date_default_timezone_set($zone);
		//$zones_array[$key]['zone'] = $zone;
		//$zones_array[$key]['diff_from_GMT'] = 'UTC/GMT ' . date('P', $timestamp);
	}

	return $zones_array;
}

// get paypal account, depending of settings
function agora_get_default_timezone() {
	$timezone = trim(elgg_get_plugin_setting('default_timezone', 'agora'));
	if (empty($timezone))	{
		$timezone = 'UTC';
	}

	return $timezone;
}

// get a list of common available currencies, according payment gateways
function get_common_gateway_currencies() {
	if (agora_check_if_paypal_is_enabled() && agora_check_if_payulatam_is_enabled())	{
		$currency_paypal = get_agora_paypal_currency_list();   
		$currency_payulatam = get_agora_payulatam_currency_list();   
		
		foreach ($currency_paypal as $key_paypal => $value_paypal) {
			foreach ($currency_payulatam as $key_payulatam => $value_payulatam) {
				if ($key_paypal == $key_payulatam)	{
					//print_r($key_paypal .'-'. $key_payulatam.' - '.$value.'<br />');
					$tmparray[$key_paypal] = $value_paypal;
				}
			}  
		}	
		asort($tmparray); // sort list alphabetically
	}
	else if (agora_check_if_paypal_is_enabled())	{
		$tmparray = get_agora_paypal_currency_list();
	}
	else if (agora_check_if_payulatam_is_enabled())	{
		$tmparray = get_agora_payulatam_currency_list(); 
	}	
	else {	// if non payment gateway is enabled, return all available currencies
		$tmparray = get_all_currencies(); 
	}
	
    return $tmparray;
}

// get paypal account, depending on settings
function agora_get_paypal_account($classifieds_owner_guid) {
	$paypal_acount = '';
	$whocanpost = trim(elgg_get_plugin_setting('agora_uploaders', 'agora'));
	
	if ($whocanpost === 'allmembers')   {
		$vowner = get_user($classifieds_owner_guid);
		if (elgg_instanceof($vowner, 'user')) {   
			$paypal_acount = trim($vowner->getPrivateSetting("agora_paypal_account"));
		}
	}
	else if ($whocanpost === 'admins')   {
		$paypal_acount = trim(elgg_get_plugin_setting('paypal_account', 'agora'));
	}

	return $paypal_acount;
}

// generate standard paypal button for a given paypal account, classified and buyer
function agora_generate_paypal_button($paypal_acount, $classifieds, $buyer) {
	$buybuttton = '  
			<script src="'.elgg_get_site_url().'/mod/agora/assets/paypal-button.min.js?merchant='.$paypal_acount.'" 
				data-button="buynow" 
				data-name="'.$classifieds->title.'" 
				data-number="'.$classifieds->guid.'-'.$buyer->guid.'-'.$classifieds->container_guid.'" 
				data-quantity="1" 
				data-amount="'.$classifieds->get_ad_price_with_shipping_cost().'" 
				data-currency="'.$classifieds->currency.'"
				data-return="'.elgg_get_site_url().'agora/view/'.$classifieds->guid.'"
				data-callback="'.elgg_get_site_url().'agora/ipn/"
				'.agora_use_sandbox_paypal(AGORA_PAYPAL_METHOD_SIMPLE).'
			></script>
	';

	return $buybuttton;
}

// generate payulatam button for a given classified and buyer
function agora_generate_payulatam_button($classifieds, $buyer) {
	$whocanpost = trim(elgg_get_plugin_setting('agora_uploaders', 'agora'));
	
	if ($whocanpost === 'allmembers')   {
		$vowner = get_user($classifieds->owner_guid);
		if (elgg_instanceof($vowner, 'user')) {   
			$payulatam_merchantId = trim($vowner->getPrivateSetting("agora_payulatam_merchantId"));
			$payulatam_accountId = trim($vowner->getPrivateSetting("agora_payulatam_accountId"));
			$payulatam_apikey = trim($vowner->getPrivateSetting("agora_payulatam_apikey"));
			$payulatam_lang = trim($vowner->getPrivateSetting("agora_payulatam_lang"));	
		}
	}
	else if ($whocanpost === 'admins')   {
		$payulatam_merchantId = trim(elgg_get_plugin_setting('payulatam_merchantId', 'agora'));
		$payulatam_accountId = trim(elgg_get_plugin_setting('payulatam_accountId', 'agora'));
		$payulatam_apikey = trim(elgg_get_plugin_setting('payulatam_apikey', 'agora'));
		$payulatam_lang = trim(elgg_get_plugin_setting('payulatam_lang', 'agora'));
	}	
	
	$buybuttton = '';
	if ($payulatam_merchantId && $payulatam_accountId && $payulatam_apikey) {
		$buybuttton .= '  
			<form method="post" class="payul_form" action="'.get_payulatam_submiturl().'">
			  <input type="image" class="payul_button" border="0" alt="" src="http://www.payulatam.com/img_botones_herramientas/boton_pagar_mediano.png" onClick="this.form.urlOrigen.value = window.location.href;"/>
			  <input name="merchantId" type="hidden" value="'.$payulatam_merchantId.'"/>
			  <input name="accountId" type="hidden" value="'.$payulatam_accountId.'"/>
			  <input name="ApiKey" type="hidden" value="'.$payulatam_apikey.'"/>
			  <input name="description" type="hidden" value="'.$classifieds->title.'"/>
			  <input name="referenceCode" type="hidden" value="'.$classifieds->guid.'"/>
			  <input name="extra1" value="'.$buyer->guid.'" type="hidden"/>
			  <input name="extra2" value="'.$classifieds->container_guid.'" type="hidden"/>
			  <input name="amount" type="hidden" value="'.$classifieds->get_ad_price_with_shipping_cost().'"/>
			  <input name="tax" type="hidden" value="'.$classifieds->get_ad_tax_cost().'"/>
			  <input name="taxReturnBase" type="hidden" value="'.($classifieds->get_ad_tax_cost()==0?0:$classifieds->price).'"/> 
			  <input name="shipmentValue" value="'.$classifieds->get_ad_shipping_cost().'" type="hidden"/>
			  <input name="currency" type="hidden" value="'.$classifieds->currency.'"/>
			  <input name="lng" type="hidden" value="'.$payulatam_lang.'"/>
			  <input name="sourceUrl" id="urlOrigen" value="" type="hidden"/>
			  <input name="buttonType" value="SIMPLE" type="hidden"/>
			  <input name="buyerEmail" value="'.$buyer->email.'" type="hidden"/>
			  <input name="responseUrl" value="'.elgg_get_site_url().'agora/view/'.$classifieds->guid.'" type="hidden"/>
			  <input name="confirmationUrl" value="'.elgg_get_site_url().'agora/ipnpayulatam/" type="hidden"/>
			  <input name="signature" value="'.get_MD5_hash($payulatam_apikey, $payulatam_merchantId, $classifieds->guid, $classifieds->get_ad_price_with_shipping_cost(), $classifieds->currency).'" type="hidden"/>
			 <input name="test" value="'.use_testmode_payulatam().'" type="hidden"/>
			</form>
		';
	}

	return $buybuttton;
}

/*
 * Check if adaptive payments is enabled. 
 * 
 * Returns true if all options below are true:
 * 1. amap_paypal_api is enabled
 * 2. adaptive payment option is enabled on agora settings
 * 3. all fields on amap_paypal_api settings are not empty
 * 4. the commission is numeric and between 0 and 100
 * 
 */ 
function agora_check_if_paypal_adaptive_payments_is_enabled() {
	if (elgg_is_active_plugin("amap_paypal_api"))	{
		$agora_adaptive_payments = trim(elgg_get_plugin_setting('agora_adaptive_payments', 'agora'));
		
		if(empty($agora_adaptive_payments) || $agora_adaptive_payments == AGORA_GENERAL_NO) {
			return false;
		}  
		else {
			$API_caller_username = trim(elgg_get_plugin_setting('paypal_API_caller_username', 'amap_paypal_api'));
			$API_caller_passwd = trim(elgg_get_plugin_setting('paypal_API_caller_passwd', 'amap_paypal_api'));
			$API_caller_signature = trim(elgg_get_plugin_setting('paypal_API_signature', 'amap_paypal_api'));
			$API_app_id = trim(elgg_get_plugin_setting('paypal_API_app_id', 'amap_paypal_api'));
			$commission = trim(elgg_get_plugin_setting('agora_adaptive_payments_commission', 'agora'));
			
			if (!empty($API_caller_username) && !empty($API_caller_passwd) && !empty($API_caller_signature) && !empty($API_app_id) 
				&& (is_numeric($commission) && ($commission > 0)  && ($commission < 100) ))	{
				return true;
			}
		}
		
	}
	
	return false;
}	

/*
 * Get the owner commission amount for adaptive payments for a given price
 * 
 * Returns the commission
 */
function agora_get_adaptive_payment_owner_commission($classifieds_price) {
	$site_owner_commission = trim(elgg_get_plugin_setting('agora_adaptive_payments_commission', 'agora')); 
	
	$commission = 0;
	if (is_numeric($site_owner_commission))
		$commission = $classifieds_price * $site_owner_commission / 100;
		
	
	return $commission;
}	

// Notify buyer for transaction he/she just made
function agora_notify_buyer_for_transaction($buyer_profil_guid, $classfd) {
	$subject = elgg_echo('agora:paypal:buyersubject', array($classfd->title));
	$message = '';
	$message .= '<p>'.elgg_echo('agora:paypal:buyerbody').'</p>';
	$message .= '<p>'.elgg_echo('agora:paypal:title').': <a href="'.elgg_get_site_url().'agora/view/'.$classfd->guid.'">'.$classfd->title.'</a></p>';
	notify_user($buyer_profil_guid, $classfd->owner_guid, $subject, $message); 
	
	return true;
}


// Notify users defined in settings for each transaction
function agora_notify_users_for_transaction($classfd_owner_guid, $classfd) {
	$users_to_notify = elgg_get_plugin_setting('users_to_notify','agora');
	$fields = explode(",", $users_to_notify);
	
	$subject = elgg_echo('agora:paypal:buyersubject', array($classfd->title));
	$message = '';
	$message .= '<p>'.elgg_echo('agora:paypal:buyerbody').'</p>';
	$message .= '<p>'.elgg_echo('agora:paypal:title').': <a href="'.elgg_get_site_url().'agora/view/'.$classfd->guid.'">'.$classfd->title.'</a></p>';	
	
	foreach ($fields as $val){
		$user_to_notify = get_user_by_username(trim($val));
		
		if($user_to_notify){
			$res = notify_user($user_to_notify->guid, $classfd_owner_guid, $subject, $message);  
		}
	}
	
	return true;
}

// check reviews and ratings are enabled only for buyers
function comrat_only_buyers_enabled() {
    $buyers_comrat = trim(elgg_get_plugin_setting('buyers_comrat', 'agora'));

    if ($buyers_comrat === AGORA_GENERAL_YES) 
		return true;
	
    return false;
}

// this function is based on standard Elgg core function elgg_view_comments, in order to add custom code for displaying reviews and review/rating form
function agora_elgg_view_comments($entity, $add_comment = true, array $vars = array()) {
	if (!($entity instanceof ElggEntity)) {
		return false;
	}

	$vars['entity'] = $entity;
	$vars['show_add_form'] = $add_comment;
	$vars['class'] = elgg_extract('class', $vars, "{$entity->getSubtype()}-comments");
	
	$output = elgg_trigger_plugin_hook('comments', $entity->getType(), $vars, false);
	if ($output) {
		return $output;
	} else {
		return elgg_view('agora/comments', $vars);
	}
}

// get star ratings for a given ad
function agora_get_ad_ratings($entity) {
	if (!elgg_instanceof($entity, 'object', 'agora')) {
		return false;
	}

	$ratings = array();
	$options = array(
		'guid' => $entity->getGUID(),
		'annotation_name' => AGORA_STAR_RATING_ANNOTATION,
		'limit' => 0,
	);
	
	$stars = elgg_get_annotations($options);
	
	if ($stars)	{
		$i = 0;
		$rate_sum = 0;
		foreach ($stars as $st)	{
			if (is_numeric($st->value)) {
				$i++;
				$rate_sum += $st->value;
			}
		}
		
		if ($i > 0)	{
			$ratings[0] = $rate_sum;	// sum of points
			$ratings[1] = $i;			// no of ratings
			$ratings[2] = number_format($ratings[0]/$ratings[1], 2, '.', '');	// rating
			
			return $ratings;
		}
	}
	
	return false;
}

// check reviews and ratings are enabled only for buyers
function check_comrat_time($created_time) {
    $buyers_comrat_expire = trim(elgg_get_plugin_setting('buyers_comrat_expire', 'agora'));

	$time_passed = time() - $created_time;
	$time_expire = $buyers_comrat_expire*24*60*60;
	 
    if ($time_passed <= $time_expire) 
		return true;
	
    return false;
}

// get the url of ad image
function agora_getImageUrl($entity, $size = 'medium') {

	if (!elgg_instanceof($entity, 'object', 'agora')) {
		return false;
	}
	
	// Get the size
	$size = elgg_strtolower($size);
	if (!in_array($size, array('master', 'large', 'medium', 'small', 'tiny'))) {
		$size = 'medium';
	}

	$image_url = "agora/image/$entity->guid/$size/".time().".jpg";
	
	return elgg_normalize_url($image_url);
}
