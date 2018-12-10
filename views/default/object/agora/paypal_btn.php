<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

if (!AgoraOptions::isPaypalEnabled()) {
    return;
}

if (!elgg_is_active_plugin('paypal_api')) {
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

$custom = [];
$custom['entity_guid'] = $entity->getGUID();
$custom['user_guid'] = $user->getGUID();
$custom['time'] = time();

$final_price = $entity->getFinalPrice();
    
if (AgoraOptions::isPaypalAdaptivePaymentsEnabled()) {
    $form_vars = [];
    $form_vars['id'] = 'prepare_payment_form';
    $form_vars['action'] = PaypalApiOptions::getPaypalModeURL();
                   
    $vars['merchant_id'] = AgoraOptions::getPaypalAccount($entity->owner_guid);
    $vars['site_owner_commission'] = AgoraOptions::getAdaptivePaymentOwnerCommission($final_price);
    $vars['merchant_amount'] = $final_price;
    $vars['currency_code'] = $entity->currency;
    $vars['use_sandbox'] = PaypalApiOptions::getSandboxMode(AgoraOptions::isPaypalAdaptivePaymentsEnabled());
    $vars['entity_guid'] = $entity->getGUID();
    $vars['entity_plugin'] = 'agora';
    $vars['trackingId'] = json_encode($custom);
    echo elgg_view_form('paypal_api/adaptive', $form_vars, $vars);
}
else {
    $form_vars = [];
    $form_vars['action'] = PaypalApiOptions::getPaypalModeURL();

    $vars['currency_code'] = $entity->currency;
    $vars['business'] = AgoraOptions::getPaypalAccount($entity->owner_guid);
    $vars['return'] = $entity->getURL();
    $vars['cancel_return'] = $entity->getURL();

    $vars['amount'] = $final_price;
    $vars['item_name'] = $entity->title;
    $vars['item_number'] = $entity->getGUID();
    $vars['custom'] = json_encode($custom);
    
    // custom below receive a temporary value
    //$incomplete_custom = '_'.$user->getGUID().'_'.time();     OBS
    //$vars['custom'] = $entity->getGUID().$incomplete_custom;  OBS

    echo elgg_view_form('paypal_api/payment', $form_vars, $vars);
}
