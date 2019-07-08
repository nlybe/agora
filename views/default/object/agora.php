<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

elgg_require_js("agora/gallery");

$full = elgg_extract('full_view', $vars, false);
$entity = elgg_extract('entity', $vars, false);

if (!$entity) {
    return;
}

$owner = $entity->getOwnerEntity();
$owner_icon = elgg_view_entity_icon($owner, 'small');

// set the default timezone to use
date_default_timezone_set(AgoraOptions::getDefaultTimezone());
$tu = $entity->time_updated;

// check if this user has bought this ad
$isbuyer = $entity->userPurchasedAd(elgg_get_logged_in_user_entity()->guid, true);

if (elgg_is_logged_in()) {
    if ($entity->isSoldOut()) {
        $paypal_btn = elgg_view('output/img', array(
            'src' => elgg_normalize_url('mod/agora/graphics/soldout.png'),
            'alt' => 'soldout',
            'class' => 'soldout',
        ));            
    }
    else if ($entity->getFinalPrice()>0 && (!$isbuyer || ($isbuyer && AgoraOptions::isMultipleAdPurchaseEnabled()))) {
        $paypal_btn = elgg_view('object/agora/paypal_btn', [
            'entity' => $entity,
        ]);
    }

    if ($isbuyer) {
        $paypal_btn .= elgg_format_element('div', ['class' => 'bought'], elgg_echo('agora:messagetobuyer'));
    }    
}

if ($entity->digital) {
    $digital_icon = elgg_view('output/img', array(
        'src' => elgg_normalize_url('mod/agora/graphics/downloadable_file_tiny.png'),
        'alt' => elgg_echo("agora:download:downloadable_file"),
        'class' => 'downloadable_file',
    ));
}

if (AgoraOptions::allowedComRatOnlyForBuyers()) { // get ratings if enabled for buyers
    $entity_ratings = elgg_view('ratings/ratings', ['entity' => $entity]);
}

// not visible at the moment on elgg3 version
$subtitle = ($entity_ratings?'<br />'.$entity_ratings:'');

if (elgg_is_active_plugin('amap_maps_api') && AgoraOptions::isGeolocationEnabled() && $entity->location) {
    $clocation = elgg_view('output/url', array(
        'href' => elgg_normalize_url("agora/map?guid={$entity->guid}"),
        'text' => $entity->location,
    ));
}
    
if ($full && !elgg_in_context('gallery')) {
    $params = array(
        'entity' => $entity,
        'title' => false,
    );
    $params = $params + $vars;
    $summary = elgg_view('object/elements/summary', $params);
     
    // button with total price
    if ($entity->price_final > 0 && !$entity->isSoldOut()) {
        $buy_btn = elgg_format_element('div', ['class' => 'total_price'], elgg_echo('agora:object:total_cost', [AgoraOptions::formatCost($entity->price_final, $entity->currency)]));
    }
    $content .= elgg_format_element('div', ['class' => 'agoraprint'], $buy_btn.$paypal_btn);

    if ($entity->price) {
        $content .= elgg_view('object/agora/feature', [
            'label' => elgg_echo('agora:price'), 
            'text' => AgoraOptions::formatCost($entity->price, $entity->currency),
        ]);
        if ($entity->tax_cost) {
            $content .= elgg_view('object/agora/feature', [
                'label' => elgg_echo('agora:add:tax_cost'), 
                'text' => AgoraOptions::formatCost($entity->getTaxCost(), $entity->currency),
            ]);
        }
    }
    if ($entity->shipping_cost) {
        $content .= elgg_view('object/agora/feature', [
            'label' => elgg_echo('agora:add:shipping_cost'), 
            'text' => AgoraOptions::formatCost($entity->getShippingCost(), $entity->currency),
        ]);
    }

    if ($entity->category) {
        $content .= elgg_view('object/agora/feature', [
            'label' => elgg_echo('agora:category'), 
            'text' => $entity->category,
        ]);
    }
    if ($clocation) {
        $content .= elgg_view('object/agora/feature', [
            'label' => elgg_echo('agora:location'), 
            'text' => $clocation,
        ]);
    }
    if (is_numeric($entity->howmany)) {
        $content .= elgg_view('object/agora/feature', [
            'label' => elgg_echo('agora:howmany'), 
            'text' => $entity->howmany,
        ]);
    }
    if ($entity->digital) {
        $content .= elgg_view('object/agora/feature', [
            'label' => elgg_echo('agora:download:type'), 
            'text' => elgg_echo('agora:download:downloadable_file').'&nbsp;'.$digital_icon,
        ]);
    }

    // be interested form
    if (
        elgg_is_logged_in() 
        && elgg_is_active_plugin("messages") 
        && AgoraOptions::canMembersSendPrivateMessage() 
        && !$soldout
        && !(elgg_get_logged_in_user_guid() == $entity->owner_guid)
    ) {
        $pmbutton = elgg_view('output/url', array(
            'name' => 'reply',
            'class' => 'elgg-button elgg-button-action',
            'rel' => 'toggle',
            'href' => '#interested-in-form',
            'text' => elgg_echo('agora:be_interested'),
        ));
        $content .= elgg_format_element('div', ['class' => 'pm'], $pmbutton);
        
        $form_params = array(
            'id' => 'interested-in-form',
            'class' => 'hidden mtl',
        );
        $body_params = array(
            'classified_guid' => $entity->guid,
            'recipient_guid' => $entity->owner_guid,
            'subject' => elgg_echo("agora:be_interested:ad_message_subject", array($entity->title)),
        );
        $content .= elgg_view_form('agora/be_interested', $form_params, $body_params);
    }

    $images = elgg_extract('images', $vars, false);
    if ($images) {
        $content .= elgg_view('output/amap_images', $vars);
    }

    if ($entity->description) {
        $content .= elgg_format_element('div', ['class' => 'desc'], agora_get_ad_description($entity->description));
    }
        
    if ($entity->description) {
        $body .= elgg_format_element('div', ['class' => 'elgg-image-block clearfix'], elgg_view('output/longtext', [
            'value' => agora_get_ad_description($entity->description),
        ]));
    } 
    
    echo elgg_view('object/elements/full', array(
        'entity' => $entity,
        'icon' => elgg_view_entity_icon($entity, 'large', ['img_class' => 'elgg-photo']),
        'show_responses' => elgg_extract('show_responses', $vars, false),
        'summary' => $summary,
        'body' => $content,
    ));
} 
else if (elgg_in_context('gallery')) {
    $date = elgg_view_friendly_time($entity->time_created);
    $owner_link = elgg_view('output/url', array(
        'href' => "agora/owner/$owner->username",
        'text' => $owner->name,
        'is_trusted' => true,
    ));
     
    $entity_icon = elgg_view_entity_icon($entity, 'large', ['img_class' => 'elgg-photo']);
    
    $g_content = elgg_format_element('h3', [],elgg_view('output/url', array(
        'href' => $entity->getURL(),
        'text' => $entity->title,
    )));
    $g_content .= $entity_icon;
    $g_content .= elgg_format_element('p', ['class' => 'gallery-date'], "{$owner_link} {$date}");
    $g_content .= elgg_format_element('div', ['class' => 'gallery-view'], 
        ($entity->category?elgg_format_element('strong', [], elgg_echo('agora:category')).': '.agora_get_cat_name_settings($entity->category, true).'<br />':'').
        ($entity->getFinalPrice() > 0 ? elgg_echo('agora:object:total_cost', [AgoraOptions::formatCost($entity->getFinalPrice(), $entity->currency)]).'<br />'.$paypal_btn:'')
    );
    
    echo elgg_format_element('div', ['class' => 'agora-gallery-item'], $g_content);   
} 
else {
    $icon_size = 'medium';
    $page_owner = elgg_get_page_owner_entity();
    if (elgg_in_context('widgets') || elgg_instanceof($page_owner, 'group')) {
        // we want small icon on group views and in widget view
        $icon_size = 'small';
    }
    $entity_icon = elgg_view_entity_icon($entity, $icon_size, ['img_class' => 'elgg-photo']);
    
//    $content = elgg_format_element('div', ['class' => 'agoraprint'], $paypal_btn);
    if ($entity->getFinalPrice() > 0) {
        $content .= elgg_view('object/agora/feature', [
            'label' => elgg_echo('agora:object:total_cost:simple'), 
            'text' => AgoraOptions::formatCost($entity->getFinalPrice(), $entity->currency),
        ]);
    }
    
    if ($entity->category) {
        $content .= elgg_view('object/agora/feature', [
            'label' => elgg_echo('agora:category'), 
            'text' => $entity->category,
        ]);
    }
    if ($clocation) {
        $content .= elgg_view('object/agora/feature', [
            'label' => elgg_echo('agora:location'), 
            'text' => $clocation,
        ]);
    }

    if ($entity->digital) {
        $content .= elgg_view('object/agora/feature', [
            'label' => elgg_echo('agora:download:type'), 
            'text' => elgg_echo('agora:download:downloadable_file').'&nbsp;'.$digital_icon,
        ]);
    }

    $params = array(
        'entity' => $entity,
        'content' => $content,
    );
    $params = $params + $vars;
    $body = elgg_view('object/elements/summary', $params);

    echo elgg_view_image_block($entity_icon, $body);
}