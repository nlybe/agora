<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

use Agora\AgoraOptions;

if (!AgoraOptions::isPaypalEnabled()) {
    return;
}

if (!elgg_is_active_plugin('paypal_api')) {
    return;
}

$entity = elgg_extract('entity', $vars, '');
if (!$entity instanceof Agora) { 
    return;
}

$user = elgg_get_logged_in_user_entity();
if (!$user) {
    return;
}

$vars['currency'] = $entity->currency;
$vars['client-id'] = AgoraOptions::getPaypalAccount($entity->owner_guid);
$vars['amount'] = $entity->getFinalPrice();
$vars['item_reference_id'] = $entity->getGUID();
$vars['item_name'] = $entity->title;
$vars['custom_id'] = $entity->getGUID()."-".$user->getGUID()."-".time();

echo elgg_view('paypal_api/paypal_btn', $vars);