<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

if (!AgoraOptions::isPaypalEnabled()) {
    return;
}

$entity = elgg_extract('entity', $vars, '');
if (!elgg_instanceof($entity, 'object', Agora::SUBTYPE)) {
    return;
}

$user = elgg_get_logged_in_user_entity();
if (!$user) {
    return;
}

$form_vars = [];
$form_vars['action'] = PaypalApiOptions::getPaypalModeURL();

$vars['currency_code'] = $entity->currency;
$vars['business'] = AgoraOptions::getPaypalAccount($entity->owner_guid);
$vars['return'] = $entity->getURL();
$vars['cancel_return'] = $entity->getURL();

$vars['amount'] = $entity->getFinalPrice();
$vars['item_name'] = $entity->title;
$vars['item_number'] = $entity->getGUID();

// custom below receive a temporary value
$incomplete_custom = '_'.$user->getGUID().'_'.time();
$vars['custom'] = $entity->getGUID().$incomplete_custom;    

echo elgg_view_form('paypal_api/payment', $form_vars, $vars);

