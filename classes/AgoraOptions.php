<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

class AgoraOptions {

    const PLUGIN_ID = 'agora';                      // current plugin ID
    const YES = 'yes';                              // general purpose yes
    const NO = 'no';                                // general purpose no
    const ON = 'on';                                // general purpose On
    const ICON = 'agora_forest_green';              // default map icon
    const PURCHASE_METHOD_PAYPAL = 'Paypal';        // paypal method of purchase
    const PURCHASE_METHOD_PAYPAL_ADAPTIVE = 'Paypal Adaptive';        // paypal adaptive method of purchase
    const PURCHASE_METHOD_OFFLINE = 'Offline';      // offline method of purchase
    const INTEREST = 'INTEREST';                    // define initial interest
    const INTEREST_ACCEPTED = 'ACCEPTED';           // define interest as accepted
    const INTEREST_REJECTED = 'REJECTED';           // define interest as rejected
    const DEFAULT_CURRENCY = 'EUR';                 // default currency
    const DEFAULT_TIMEZONE = 'UTC';                 // default timezone
    const UPLOADER_ALL = 'allmembers';              // who can posts ads: all
    const UPLOADER_ADMINS = 'admins';               // who can posts ads: admins
    const MAX_IMAGES_GALLERY = 10;                  // define maximum number of images in ads gallery
    const ADAPTIVE_DEFAULT_COMMISSION = 10;         // define default commission for adaptive payments
    
    /**
     * Get param value from settings
     * 
     * @return type
     */
    Public Static function getParams($setting_param = ''){
        if (!$setting_param) {
            return false;
        }
        
        return trim(elgg_get_plugin_setting($setting_param, self::PLUGIN_ID)); 
    }
    
    /**
     * Check if posts for digital products are allowed
     * 
     * @return boolean
     */
    Public Static function isDigitalProductsEnabled() {
        $get_ads_digital = trim(elgg_get_plugin_setting('ads_digital', self::PLUGIN_ID));

        if (empty($get_ads_digital)) {
            return false;
        } else if ($get_ads_digital == 'no') {
            return false;
        }

        return $get_ads_digital;
    }
    
    /**
     * Check if geolocation is enabled
     * 
     * @return boolean
     */
    function isGeolocationEnabled() {
        $ads_geolocation = trim(elgg_get_plugin_setting('ads_geolocation', self::PLUGIN_ID));

        if ($ads_geolocation === 'yes') {
            return true;
        }

        return false;
    }
    
    /**
     * Get the list of allowed image files
     * 
     * @return array
     */
    Public Static function getAllowedImageFiles() {
        $allowed_mime_types = [
            'image/png',    // .png
            'image/jpeg',   // .jpg
            'image/gif',    // .gif
        ];
        
        return $allowed_mime_types;
    }
    
    /**
     * Render price with currency
     * 
     * @param type $currency
     * @param type $price
     * @return string
     */
    Public Static function formatCost($price, $currency = '', $show_currency = true) {
        if (!$price) {
            return '';
        }
        
        $price = number_format((float)$price, 2, '.', '');
        
        if (!$currency) {
            $currency = self::getParams('default_currency');
        }
        
        if ($show_currency) {
            return self::getCurrencySign($currency).'&nbsp;'.$price;
        }
        
        return $price;
    }
    
    /**
     * Get currency sign for given currency
     * 
     * @param type $currency_code
     * @return string
     */
    Public Static function getCurrencySign($currency_code) {
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
    
    /**
    * Check if all members can post ads
    * 
    * @return true if all members can post ads
    */
    Public Static function canAllUsersPostClassifieds() {
        if (!elgg_is_logged_in()) {
           return false;
        }
        $whocanpost = self::getParams('agora_uploaders');

        if ($whocanpost === 'allmembers') {
            return true;
        }

        return false;
    }
    
    /**
     * Get Paypal account, depending on settings
     * 
     * @param type $owner_guid
     * @return type
     */
    function getPaypalAccount($owner_guid) {
        $whocanpost = self::getParams('agora_uploaders');
    
        if ($whocanpost === 'allmembers') {
            $owner = get_user($owner_guid);
            if (elgg_instanceof($owner, 'user')) {
                return trim($owner->getPrivateSetting("agora_paypal_account"));
            }
        } 
        else if ($whocanpost === 'admins' && elgg_is_active_plugin('paypal_api')) {
            return elgg_get_plugin_setting('merchant_id', PaypalApiOptions::PLUGIN_ID);
        }
        
        return false;
    }    
    
    /**
     * Get list of users to notify for transactions in settings
     * 
     * @return type
     */
    Public Static function getUserToNotify(){
        $users_to_notify = self::getParams('users_to_notify');
        $users_to_notify_arr = explode(",", $users_to_notify);
        
        return $users_to_notify_arr;
    }    
    
    /**
     * Check if user can post cads
     *
     * @param object $user User object
     * @return true if current can post ads
     */
    Public Static function canUserPostClassifieds() {
        if (elgg_is_logged_in()) {
            $whocanpost = self::getParams('agora_uploaders');
            
            if ($whocanpost === 'allmembers') {
                return true;
            } 
            else if ($whocanpost === 'admins' && elgg_is_admin_logged_in()) {
                return true;
            }
        }

        return false;
    }
    
    /**
     * Check if multiple purchase of same product is allowed
     * 
     * @return boolean
     */
    Public Static function isMultipleAdPurchaseEnabled() {
        $multiple_ad_purchase = self::getParams('multiple_ad_purchase');

        if ($multiple_ad_purchase === 'yes') {
            return true;
        }

        return false;
    }   
    
    /**
     * Check if reviews and ratings are enabled only for buyers
     * 
     * @return boolean
     */
    Public Static function allowedComRatOnlyForBuyers() {
        if (!elgg_is_active_plugin('ratings')) {
            return false;
        }

        $buyers_comrat = self::getParams('buyers_comrat');
        if ($buyers_comrat === self::YES) {
            return true;
        }

        return false;
    }
    
    /**
     C check if members can send private message to seller
     * 
     * @return boolean
     */
    Public Static function canMembersSendPrivateMessage() {
        $send_message = self::getParams('send_message');

        if ($send_message === AgoraOptions::YES) {
            return true;
        }

        return false;
    }    
    
    /**
     * Get paypal account, depending of settings
     * 
     * @return string
     */
    Public Static function getDefaultTimezone() {
        $timezone = self::getParams('default_timezone');
        if (empty($timezone)) {
            $timezone = 'UTC';
        }

        return $timezone;
    }

    /**
     * Get a list of all available currencies, according payment gateways
     * 
     * @return type
     */
    Public Static function getAllCurrencies() {
        $CurrOptions = self::getPaypalCurrencyList();
        
        // sort list alphabetically
        asort($CurrOptions); 

        return $CurrOptions;
    }   
    
    /**
     * Currencies list according paypal api
     * 
     * @return type
     */
    Public Static function getPaypalCurrencyList() {
        return [
            'AUD' => 'Australian Dollar',
            'BRL' => 'Brazilian Real',
            'CAD' => 'Canadian Dollar',
            'CZK' => 'Czech Koruna',
            'DKK' => 'Danish Krone',
            'EUR' => 'Euro',
            'HKD' => 'Hong Kong Dollar',
            'HUF' => 'Hungarian Forint',
            'ILS' => 'Israeli New Sheqel',
            'JPY' => 'Japanese Yen',
            'MYR' => 'Malaysian Ringgit',
            'MXN' => 'Mexican Peso',
            'NOK' => 'Norwegian Krone',
            'NZD' => 'New Zealand Dollar',
            'PHP' => 'Philippine Peso',
            'PLN' => 'Polish Zloty',
            'GBP' => 'Pound Sterling',
            'SGD' => 'Singapore Dollar',
            'SEK' => 'Swedish Krona',
            'CHF' => 'Swiss Franc',
            'TWD' => 'Taiwan New Dollar',
            'THB' => 'Thai Baht',
            'TRY' => 'Turkish Lira',
            'USD' => 'U.S. Dollar',
        ];
    }   
    
    /**
     * Get a list of common available currencies, according payment gateways.
     * At the moment only Paypal is enabled.
     * 
     * @return type
     */
    Public Static function getCommonGatewayCurrencies() {
//        if (AgoraOptions::isPaypalEnabled()) {
            $currencies = self::getPaypalCurrencyList();
//        } 
        
        return $currencies;
    }    
    
    /**
     * Get list of all timezones
     * 
     * @return type
     */
    Public Static function getAllTimesZones() {
        $zones_array = array();
        $timestamp = time();

        foreach (timezone_identifiers_list() as $key => $zone) {
            $zones_array[$zone] = $zone;

            //date_default_timezone_set($zone);
            //$zones_array[$key]['zone'] = $zone;
            //$zones_array[$key]['diff_from_GMT'] = 'UTC/GMT ' . date('P', $timestamp);
        }

        return $zones_array;
    }
    
    /**
     * Check if admin has set terms of use
     * 
     * @return boolean
     */
    Public Static function checkTermsClassifieds() {
        $terms_of_use = self::getParams('terms_of_use');

        if (!empty($terms_of_use) && $terms_of_use != null) {
            return true;
        }

        return false;
    }    
    
    /**
     * Check if purchase datetime is with period spcified in settings.
     * It's not used at the moment (20180416) as this options is missing in settings
     * 
     * @param type $created_time
     * @return boolean
     */
    Public Static function checkComratTime($created_time) {
        $buyers_comrat_expire = self::getParams('buyers_comrat_expire');
        
        $time_passed = time() - $created_time;
        $time_expire = $buyers_comrat_expire * 24 * 60 * 60;

        if ($time_passed <= $time_expire) {
            return true;
        }

        return false;
    }  
    
    /**
     * Get ad user interest status
     * 
     * @param type $status
     * @return string
     */
    Public Static function getAdUserInterestStatus($status) {

        if ($status === AgoraOptions::INTEREST_ACCEPTED) {
            return elgg_format_element('span', ['style' => 'color:green;'], elgg_echo('agora:interest:accepted'));
        } else if ($status === AgoraOptions::INTEREST_REJECTED) {
            return elgg_format_element('span', ['style' => 'color:red;'], elgg_echo('agora:interest:rejected'));
        }

        return '';
    }
    
    /**
     * Return a 5(n) length format string, used for invoicing
     * 
     * @param type $value
     * @param type $threshold
     * @return string
     */
    Public Static function addLeadingZero($value, $threshold = 5) {
        return sprintf('%0' . $threshold . 's', $value);
    }
    
    /**
     * Check if Paypal gateway is enabled
     * 
     * @return boolean
     */
    Public Static function isPaypalEnabled() {
        if (!elgg_is_active_plugin('paypal_api')) {
            return false;
        }

        $use_paypal = self::getParams('agora_paypal_enabled');
        if ($use_paypal === self::ON) {
            return true;
        }

        return false;
    }    
    
    /**
    * Check if adaptive payments is enabled. 
    * 
    * Returns true if all options below are true:
    * 1. paypal_api is enabled
    * 2. adaptive payment option is enabled on agora settings
    * 3. all fields on paypal_api settings are not empty
    * 4. the commission is numeric and between 0 and 100
    * 
    */ 
    Public Static function isPaypalAdaptivePaymentsEnabled() {
        if (!self::isPaypalEnabled()) {
            return false;
        }

        $adaptive_payments = self::getParams('agora_adaptive_payments');
        if ($adaptive_payments !== self::ON) {
            return false;
        }  
        else {
            $API_caller_username = trim(elgg_get_plugin_setting('paypal_API_caller_username', 'paypal_api'));
            $API_caller_passwd = trim(elgg_get_plugin_setting('paypal_API_caller_passwd', 'paypal_api'));
            $API_caller_signature = trim(elgg_get_plugin_setting('paypal_API_signature', 'paypal_api'));
            $API_app_id = trim(elgg_get_plugin_setting('paypal_API_app_id', 'paypal_api'));
            $commission = self::getParams('agora_adaptive_payments_commission');
              
            if (
                    !empty($API_caller_username) 
                    && !empty($API_caller_passwd) 
                    && !empty($API_caller_signature) 
                    && !empty($API_app_id) 
                    && (is_numeric($commission) 
                    && ($commission > 0) 
                    && ($commission < 100) 
                )) {
                    return true;
            }
        }

        return false;
   }
   
    /*
     * Get the owner commission amount for adaptive payments for a given price
     * 
     * Returns the commission
     */
    Public Static function getAdaptivePaymentOwnerCommission($classifieds_price) {
        $site_owner_commission = self::getParams('agora_adaptive_payments_commission');
        
        if (is_numeric($site_owner_commission)) {
            return $classifieds_price * $site_owner_commission / 100;
        }

        return 0;
    }   
   
    /**
     * Notify buyer for transaction
     * 
     * @param \ElggUser $buyer
     * @param type $ad
     * @return boolean
     */
    Public Static function notifyBuyer($buyer, $ad) {
        if (!($buyer instanceof \ElggUser)) {
            return false;
        }
        
        if (!elgg_instanceof($ad, 'object', Agora::SUBTYPE)) {
            return false;
        }

        $site = elgg_get_site_entity();
        $subject = elgg_echo('agora:sales:notification:buyer:subject', [$ad->title]);
        $message = elgg_echo('agora:sales:notification:buyer:body', [elgg_normalize_url("agora/my_purchases/{$ad->guid}")]);
        
        return notify_user($buyer->guid, $site->guid, $subject, $message);
    } 
    
    /**
     * Notify site administrators for transaction
     * 
     * @param \ElggUser $buyer
     * @param type $ad
     * @param type $entity
     * @return boolean
     */
    Public Static function notifyAdministrators($buyer, $ad, $entity) {
        if (!($buyer instanceof \ElggUser)) {
            return false;
        }
        
        if (!elgg_instanceof($ad, 'object', Agora::SUBTYPE)) {
            return false;
        }
        
        if (!elgg_instanceof($entity, 'object', AgoraSale::SUBTYPE)) {
            return false;
        }

        $users_to_notify = AgoraOptions::getUserToNotify();
        $subject = elgg_echo('agora:sales:notification:admin:subject', [$entity->title]);
        $message = elgg_echo('agora:sales:notification:admin:body', [
            elgg_view('output/url', ['href' => $entity->getURL(), 'title' => $entity->title])
        ]);

        foreach ($users_to_notify as $val) {
            $user_to_notify = get_user_by_username(trim($val));
            if ($user_to_notify) {
                notify_user($user_to_notify->guid, $buyer->guid, $subject, $message);
            }
        }
        
        return true;
    }    
    
}
