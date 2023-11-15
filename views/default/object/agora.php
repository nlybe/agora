<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

use Agora\AgoraOptions;

elgg_require_js("agora/gallery");

$full = elgg_extract('full_view', $vars, false);
$entity = elgg_extract('entity', $vars, false);

if (!$entity instanceof \Agora) {
    return;
}

// get the owner
$owner = $entity->getOwnerEntity();

// set the default timezone to use
date_default_timezone_set(AgoraOptions::getDefaultTimezone());

if ($entity->digital) {
    $digital_icon = elgg_view('output/img', [
        'src' => elgg_normalize_url('mod/agora/graphics/downloadable_file_tiny.png'),
        'alt' => elgg_echo("agora:download:downloadable_file"),
        'class' => 'downloadable_file',
    ]);
}

if ($full) {
    $params = [
        'entity' => $entity,
        'title' => false,
    ];
    $params = $params + $vars;
    $summary = elgg_view('object/elements/summary', $params);

    // get ratings if enabled for buyers
    if (AgoraOptions::allowedComRatOnlyForBuyers()) { 
        $entity_ratings = elgg_view('ratings/ratings', ['entity' => $entity]);
        if ($entity_ratings) {
            $summary .= $entity_ratings;
        }
    }

    if (elgg_is_logged_in()) {
        // check if this user has bought this ad
        $isbuyer = $entity->userPurchasedAd(elgg_get_logged_in_user_entity()->guid, true);

        if ($entity->isSoldOut()) {
            $paypal_btn = elgg_view('output/img', [
                'src' => elgg_normalize_url('mod/agora/graphics/soldout.png'),
                'alt' => 'soldout',
                'class' => 'soldout',
            ]);
        }
        else if ($entity->getFinalPrice()>0 && (!$isbuyer || ($isbuyer && AgoraOptions::isMultipleAdPurchaseEnabled()))) {
            $paypal_btn = elgg_view('object/agora/paypal_btn', [
                'entity' => $entity,
            ]);
        }
    
        if ($isbuyer) {
            elgg_ok_response('', elgg_echo('agora:messagetobuyer'));
        }    
    }
         
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
    
    echo elgg_view('object/elements/full', [
        'entity' => $entity,
        'icon' => elgg_view_entity_icon($entity, 'medium', ['img_class' => 'elgg-photo']),
        'show_responses' => elgg_extract('show_responses', $vars, false),
        'summary' => $summary,
        'body' => $content,
    ]);
} 
else {
    $icon_size = 'medium';
    $page_owner = elgg_get_page_owner_entity();
    if (elgg_in_context('widgets') || $page_owner instanceof \ElggGroup) {
        // we want small icon on group views and in widget view
        $icon_size = 'small';
    }
    $entity_icon = elgg_view_entity_icon($entity, $icon_size, ['img_class' => 'elgg-photo']);
    
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

    $params = [
        'entity' => $entity,
        'content' => $content,
    ];
    $params = $params + $vars;
    $body = elgg_view('object/elements/summary', $params);

    echo elgg_view_image_block($entity_icon, $body);
}