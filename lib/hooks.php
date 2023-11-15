<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 *
 * All hooks are here
 */
 
use Agora\AgoraOptions;

/**
 * Appends input fields for posting ads
 *
 * @param \Elgg\Hook $hook The hook object
 * @return array
 */
function agora_input_list(\Elgg\Hook $hook) {
    $return = $hook->getValue();
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
 * @param \Elgg\Hook $hook The hook object
 * @return string URL of agora
 */
function agora_set_url(\Elgg\Hook $hook) {
    $entity = $hook->getEntityParam();
    if ($entity instanceof \Agora) { 
        $friendly_title = elgg_get_friendly_title($entity->title);
        return "agora/view/{$entity->guid}/$friendly_title";
    }
    else if ($entity instanceof \AgoraSale) {
        $friendly_title = elgg_get_friendly_title($entity->title);
        return "agora/transactions/view/{$entity->guid}/$friendly_title";
    }   
}

/**
 * Add a menu item to an ownerblock
 * 
 * @param \Elgg\Hook $hook The hook object
 * @return \ElggMenuItem
 */
function agora_owner_block_menu(\Elgg\Hook $hook) {
    $entity = $hook->getEntityParam();
	$return = $hook->getValue();

    if ($entity instanceof \ElggUser) {
        $url = "agora/owner/{$entity->username}";
        $item = new ElggMenuItem('agora', elgg_echo('agora'), $url);
        $return[] = $item;
    } else {
        if ($entity->agora_enable != 'no') {
            $url = "agora/group/{$entity->guid}";
            $item = new ElggMenuItem('agora', elgg_echo('agora:group'), $url);
            $return[] = $item;
        }
    }

    return $return;
}

/**
 * Cron function for sending notification to buyers for review of the the ad they bought with link and login
 * 
 * @param \Elgg\Hook $hook The hook object
 * @return boolean
 */
function agora_review_reminder_cron_hook(\Elgg\Hook $hook) {
    if (!AgoraOptions::allowedComRatOnlyForBuyers()) {
        return;
    }

    $buyers_comrat_notify_by = trim(elgg_get_plugin_setting('buyers_comrat_notify_by', 'agora'));
    $notifier = get_user_by_username($buyers_comrat_notify_by);
    if ($notifier instanceof \ElggUser) {
        $notifier_guid = $notifier->guid;
    }
    else {
        $notifier_guid = elgg_get_site_entity()->guid;
    }

    elgg_call(ELGG_IGNORE_ACCESS, function () use ($notifier_guid) {
        $ts_upper_days = trim(elgg_get_plugin_setting('buyers_comrat_notify', 'agora'));
        $ts_lower_days = $ts_upper_days + 1;
        $ts_upper = strtotime("-$ts_upper_days days");
        $ts_lower = strtotime("-$ts_lower_days days");

        $options = [
            'type' => 'object',
            'subtype' => AgoraSale::SUBTYPE,
            'limit' => 0,
            'created_time_lower' => $ts_lower,
            'created_time_upper' => $ts_upper,
        ];
        $getsales = elgg_get_entities($options);

        foreach ($getsales as $gs) {
            $entity = get_entity($gs->txn_vguid);
            if (!$entity instanceof \Agora) { 
                return;
            }

            if (!check_if_user_commented_this_ad($entity->guid, $gs->txn_buyer_guid)) {
                notify_user($gs->txn_buyer_guid, $notifier_guid, elgg_echo('agora:comments:notify:subject'), elgg_echo('agora:comments:notify:body', [$entity->title, $entity->getURL()])                    );
            }
        }
    });
}

/**
 * This is triggered PayPal IPN verification. 
 * 
 * @param \Elgg\Hook $hook The hook object * 
 * @return type
 */
function agora_paypal_successful_payment_hook(\Elgg\Hook $hook) {
    $return = $hook->getValue();
    $transaction_params = $hook->getParams();
    $transaction = $transaction_params['transaction'];
    
    if (!$transaction) {
        error_log(elgg_echo('paypal_api:error:empty_ipn'));
        return $return;
    }

    elgg_call(ELGG_IGNORE_ACCESS, function () use ($transaction) {
        $purchase_units = $transaction->purchase_units;
        if (is_array($purchase_units) && count($purchase_units) > 0) {
            foreach ($purchase_units as $unit) {
                $ad = get_entity($unit->reference_id);
                if (!$ad instanceof \Agora) {
                    continue;
                }

                $custom = explode("-", $unit->custom_id);
                $buyer = get_entity($custom[1]);
                if (!($buyer instanceof \ElggUser)) {
                    continue;
                }
            
                $entity = new AgoraSale();
                $entity->subtype = AgoraSale::SUBTYPE;
                $entity->access_id = ACCESS_PRIVATE;
                $entity->owner_guid = $buyer->guid;
                $entity->container_guid = $ad->guid;
                $entity->title = elgg_echo("agora:transaction:title", [$ad->title]);
                $entity->description = serialize($transaction);
                $entity->transaction_id = $transaction->id;
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
            }
        }
    });
    
    return $return;
}

/**
 * Manage menu items options to agora entities menu
 * 
 * @param \Elgg\Hook $hook 'register', 'menu:entity' *
 * @return void|ElggMenuItem[]
 */
function agora_menu_setup(\Elgg\Hook $hook) {
    $return = $hook->getValue();
    $entity = $hook->getEntityParam();
    if (!$entity instanceof \Agora) {
        return;
    }

    $user = elgg_get_logged_in_user_entity();
    if (!$user) {
        return;
    }

    if ($entity->canEdit()) {
        $return[] = \ElggMenuItem::factory([
            'name' => 'agora_sales',
            'text' => elgg_echo("agora:sales:short"),
            'title' => elgg_echo("agora:sales:short:note", [$entity->title]),
            'href' =>  elgg_normalize_url("agora/sales/{$entity->guid}"),
            'priority' => 110,
            'icon' => 'chart-line',
        ]);

        if (AgoraOptions::canMembersSendPrivateMessage()) {
            $return[] = \ElggMenuItem::factory([
                'name' => 'agora_interest',
                'text' => elgg_echo("agora:requests"),
                'title' => elgg_echo("agora:requests:note", [$entity->title]),
                'href' =>  elgg_normalize_url("agora/requests/{$entity->guid}"),
                'priority' => 120,
                'icon' => 'exchange-alt ',
            ]);
        }
    }

    return $return;
}

/**
 * Manage menu items options to agora_sale entities menu
 * 
 * @param \Elgg\Hook $hook 'register', 'menu:entity' *
 * @return void|ElggMenuItem[]
 */
function agorasale_menu_setup(\Elgg\Hook $hook) {
    $entity = $hook->getEntityParam();
    if (!$entity instanceof \AgoraSale) {
        return;
    }

    $user = elgg_get_logged_in_user_entity();
    if (!$user) {
        return;
    }

    $return = $hook->getValue();        
    if (!$user->isAdmin()) {
        // Prevend sale deletion from non-admin users
        $return->remove('delete');
    }
    else {
        // Change url/action for delete for just in case
        $return['delete']->setHref(elgg_generate_action_url('agorasale/delete', [
            'guid' => $entity->guid,
        ]));
    }    

    return $return;
}

// /**
//  * Edit delete actions
//  * 
//  * @param \Elgg\Hook $hook 'register', 'menu:entity' *
//  * @return void|ElggMenuItem[]
//  */
// function agora_delete_action(\Elgg\Hook $hook) {
//     $entity = $hook->getEntityParam();
//     if (!$entity instanceof \AgoraSale || !$entity instanceof \Agora) {
//         return;
//     }

//     $user = elgg_get_logged_in_user_entity();
//     if (!$user) {
//         return;
//     }

//     $return = $hook->getValue();        
//     if (!$user->isAdmin()) {
//         // Prevend sale deletion from non-admin users
//         $return->remove('delete');
//     }

//     return $return;
// }

/**
 * Register menu items in user settings
 *
 * @param \Elgg\Hook $hook 'register', 'menu:page' *
 * @return void|ElggMenuItem[]
 */
function agora_notifications_page_menu(\Elgg\Hook $hook) {
	
	if (!elgg_in_context('settings') || !elgg_get_logged_in_user_guid()) {
		return;
	}

	$user = elgg_get_page_owner_entity();
	if (!$user) {
		$user = elgg_get_logged_in_user_entity();
	}
	
	$return = $hook->getValue();
	$return[] = \ElggMenuItem::factory([        
        "name" => "agora",
        "text" => elgg_echo("agora:usersettings:settings"),
        "href" => elgg_normalize_url("agora/user/" . $user->username),
        'section' => 'configure',
        "context" => "settings",
	]);
		
	return $return;
}