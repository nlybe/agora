<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 *
 * All hooks are here
 */
 
/**
 * Call from Paypal must be always accessible, even in walled garden Elgg sites
 *
 * @param string $hook
 * @param string $type
 * @param array $return_value
 * @param array $params
 * @return array
 */
function agora_walled_garden_hook($hook, $type, $return_value, $params) {
    $add = array();
    $add[] = 'ipn';
    $add[] = 'agora/ipn';

    if (is_array($return_value))
        $add = array_merge($add, $return_value);

    return $add;
}

/**
 * Appends input fields for posting ads
 *
 * @param string $hook        "members:config"
 * @param string $type        "tabs"
 * @param array  $returnvalue array that build navigation tabs
 * @param array  $params      unused
 * @return array
 */
function agora_input_list($hook, $type, $return, $params) {
//    usort($return,'AgoraOptions::invenDescSort');
    usort($return, function ($item1, $item2) {
        if ($item1['priority'] == $item2['priority']) {
            return 0;
        }
        return $item1['priority'] < $item2['priority'] ? -1 : 1;
    });
    
    return $return;
}

/**
 * Format and return the URL for agora objects
 *
 * @param string $hook
 * @param string $type
 * @param string $url
 * @param array  $params
 * @return string URL of agora.
 */
function agora_set_url($hook, $type, $url, $params) {
    $entity = $params['entity'];
    if (elgg_instanceof($entity, 'object', Agora::SUBTYPE)) {
        $friendly_title = elgg_get_friendly_title($entity->title);
        return "agora/view/{$entity->guid}/$friendly_title";
    }
    else if (elgg_instanceof($entity, 'object', AgoraSale::SUBTYPE)) {
        $friendly_title = elgg_get_friendly_title($entity->title);
        return "agora/transactions/view/{$entity->guid}/$friendly_title";
    }   
}

/**
 * Add a menu item to an ownerblock
 * 
 * @param type $hook
 * @param type $type
 * @param type $return
 * @param type $params
 * @return \ElggMenuItem
 */
function agora_owner_block_menu($hook, $type, $return, $params) {
    if (elgg_instanceof($params['entity'], 'user')) {
        $url = "agora/owner/{$params['entity']->username}";
        $item = new ElggMenuItem('agora', elgg_echo('agora'), $url);
        $return[] = $item;
    } else {
        if ($params['entity']->agora_enable != 'no') {
            $url = "agora/group/{$params['entity']->guid}/all";
            $item = new ElggMenuItem('agora', elgg_echo('agora:group'), $url);
            $return[] = $item;
        }
    }

    return $return;
}

/**
 * Cron function for sending notification to buyers for review of the the ad they bought with link and login
 * 
 * @param type $hook
 * @param type $entity_type
 * @param type $returnvalue
 * @param type $params
 * @return boolean
 */
function agora_review_reminder_cron_hook($hook, $entity_type, $returnvalue, $params) {

    elgg_load_library('elgg:agora');

    if (AgoraOptions::allowedComRatOnlyForBuyers()) {

        $buyers_comrat_notify_by = trim(elgg_get_plugin_setting('buyers_comrat_notify_by', 'agora'));
        $notifier = get_user_by_username($buyers_comrat_notify_by);
        if (elgg_instanceof($notifier, 'user')) {
            $notifier_guid = $notifier->guid;
        }
        else {
            $notifier_guid = elgg_get_site_entity()->guid;
        }

        $ts_upper_days = trim(elgg_get_plugin_setting('buyers_comrat_notify', 'agora'));
        $ts_lower_days = $ts_upper_days + 1;

        $ts_upper = strtotime("-$ts_upper_days days");
        $ts_lower = strtotime("-$ts_lower_days days");

        // set ignore access for loading all entries
        $ia = elgg_get_ignore_access();
        elgg_set_ignore_access(true);

        $options = array(
            'type' => 'object',
            'subtype' => AgoraSale::SUBTYPE,
            'limit' => 0,
            'created_time_lower' => $ts_lower,
            'created_time_upper' => $ts_upper,
        );
        $getsales = elgg_get_entities($options);

        foreach ($getsales as $gs) {
            $entity = get_entity($gs->txn_vguid);
            if (!elgg_instanceof($entity, 'object', Agora::SUBTYPE)) {
                return false;
            }

            if (!check_if_user_commented_this_ad($entity->guid, $gs->txn_buyer_guid)) {
                notify_user($gs->txn_buyer_guid, $notifier_guid, elgg_echo('agora:comments:notify:subject'), elgg_echo('agora:comments:notify:body', array($entity->title, $entity->getURL()))
                );
            }
        }

        // restore ignore access
        elgg_set_ignore_access($ia);
    }

    return false;
}

/**
 * This is triggered PayPal IPN verification. 
 * 
 * @param type $hook
 * @param type $type
 * @param type $return
 * @param type $params
 * @return type
 */
function agora_paypal_successful_payment_hook($hook, $type, $return, $params) {
    $transactions_params = $params['txn'];
    if (!$transactions_params) {
        error_log(elgg_echo('paypal_api:error:empty_ipn'));
        return $return;
    }
    
    // get company guid (item number)
    $item_number = $transactions_params['item_number'];
    $ad = get_entity($item_number);
    if (!elgg_instanceof($ad, 'object', Agora::SUBTYPE)) {
        error_log(elgg_echo('paypal_api:error:invalid:entity:guid', [$item_number]));
        return $return;
    }    
    
    $custom_arr = json_decode($transactions_params['custom'], true);
    $buyer_guid = $custom_arr['user_guid'];
    
    $buyer = get_entity($buyer_guid);
    if (!($buyer instanceof \ElggUser)) {
        error_log(elgg_echo('paypal_api:error:invalid:user:guid'));
        return $return;
    }    
    
    $ia = elgg_get_ignore_access();
    elgg_set_ignore_access(true);
    
    $entity = new AgoraSale();
    $entity->subtype = AgoraSale::SUBTYPE;
    $entity->access_id = ACCESS_PRIVATE;
    $entity->owner_guid = $buyer_guid;
    $entity->container_guid = $item_number;
    $entity->title = $transactions_params['item_name']?elgg_echo('agora:sales:title', [str_replace("+", " ", $transactions_params['item_name'])]):'';
    $entity->description = serialize($transactions_params);
    $entity->transaction_id = $transactions_params['txn_id'];
    $entity->txn_method = AgoraOptions::PURCHASE_METHOD_PAYPAL;
    $entity->buyer_name = $buyer->name;
    $entity->bill_number = AgoraSale::getNewInvoiceNumber();
    $entity->save();
    
    // reduce availability
    $ad->reduceItems();

    // notify buyer
    AgoraOptions::notifyBuyer($buyer, $ad);
    
    // notify site admins
    AgoraOptions::notifyAdministrators($buyer, $ad, $entity);
    
    elgg_set_ignore_access($ia);
    
    system_message(elgg_echo('agora:sales:success'));
    
    return $return;
}

/**
 * This is triggered PayPal IPN Adaptive verification. 
 * 
 * @param type $hook
 * @param type $type
 * @param type $return
 * @param type $params
 * @return type
 */
function agora_paypal_adaptive_successful_payment_hook($hook, $type, $return, $params) {
    $transactions_params = $params['txn'];
    if (!$transactions_params) {
        error_log(elgg_echo('paypal_api:error:empty_ipn'));
        return $return;
    }
    
    $tracking_id_arr = json_decode(urldecode($transactions_params['tracking_id']), true);
    foreach ($tracking_id_arr as $k => $v) {
        error_log($k.': '.$v);
    }
    
    // get guid (entity_guid)
    $ad = get_entity($tracking_id_arr['entity_guid']);
    if (!elgg_instanceof($ad, 'object', Agora::SUBTYPE)) {
        error_log(elgg_echo('paypal_api:error:invalid:entity:guid', [$tracking_id_arr['entity_guid']]));
        return $return;
    }    
    
    $buyer_guid = $tracking_id_arr['user_guid'];
    
    $buyer = get_entity($buyer_guid);
    if (!($buyer instanceof \ElggUser)) {
        error_log(elgg_echo('paypal_api:error:invalid:user:guid'));
        return $return;
    }    
    
    $ia = elgg_get_ignore_access();
    elgg_set_ignore_access(true);
    
    $entity = new AgoraSale();
    $entity->subtype = AgoraSale::SUBTYPE;
    $entity->access_id = ACCESS_PRIVATE;
    $entity->owner_guid = $buyer_guid;
    $entity->container_guid = $tracking_id_arr['entity_guid'];
    $entity->title = elgg_echo('agora:sales:title', [$ad->title]);
    $entity->description = serialize($transactions_params);
    $entity->transaction_id = $transactions_params['pay_key'];
    $entity->txn_method = AgoraOptions::PURCHASE_METHOD_PAYPAL_ADAPTIVE;
    $entity->buyer_name = $buyer->name;
    $entity->bill_number = AgoraSale::getNewInvoiceNumber();
    $entity->save();
    
    // reduce availability
    $ad->reduceItems();

    // notify buyer
    AgoraOptions::notifyBuyer($buyer, $ad);
    
    // notify site admins
    AgoraOptions::notifyAdministrators($buyer, $ad, $entity);
    
    elgg_set_ignore_access($ia);
    
    system_message(elgg_echo('agora:sales:success'));
    
    return $return;
}

/**
 * Manage menu items options to agora entities menu
 * 
 * @param type $hook
 * @param type $type
 * @param type $return
 * @param type $params
 * @return type
 */
function agora_menu_setup($hook, $type, $return, $params) {
    
    $entity = $params['entity'];
    if (!elgg_instanceof($entity, 'object', Agora::SUBTYPE))	{
        return $return;
    }
    
    $user = elgg_get_logged_in_user_entity();
    if (!($user instanceof \ElggUser)) {
        return $return;
    }
    
    if ($entity->canEdit()) {
        $options = array(
            'name' => 'agora_sales',
            'text' => elgg_echo("agora:sales:short"),
            'title' => elgg_echo("agora:sales:short:note", [$entity->title]),
            'href' =>  elgg_normalize_url("agora/sales/{$entity->guid}"),
            'priority' => 110,
        );
        $return[] = ElggMenuItem::factory($options);
        
        if (AgoraOptions::canMembersSendPrivateMessage()) {
            $options = array(
                'name' => 'agora_interest',
                'text' => elgg_echo("agora:requests"),
                'title' => elgg_echo("agora:requests:note", [$entity->title]),
                'href' =>  elgg_normalize_url("agora/requests/{$entity->guid}"),
                'priority' => 120,
            );
            $return[] = ElggMenuItem::factory($options);
        }
    }
    
    return $return;
}