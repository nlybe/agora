<?php
/**
 * Elgg Agora Classifieds plugin
 * @package Agora
 */

elgg_load_library('elgg:agora');

// if paypal adaptive payments is enabled
if (agora_check_if_paypal_adaptive_payments_is_enabled()) {
    elgg_load_library('elgg:amap_paypal_api');
    elgg_require_js("js/amap_paypal_api");
}

$full = elgg_extract('full_view', $vars, FALSE);
$entity = elgg_extract('entity', $vars, FALSE);

if (!$entity) {
    return;
}

$owner = $entity->getOwnerEntity();
$owner_icon = elgg_view_entity_icon($owner, 'small');

// set the default timezone to use
date_default_timezone_set(agora_get_default_timezone());
$tu = $entity->time_updated;

// check if this user has bought this ad
$getbuyers = check_if_user_purchased_this_ad($entity->guid, elgg_get_logged_in_user_entity()->guid);

$message_to_buyer = '';
if (!$getbuyers) {
    $isbuyer = false;
} else {
    $isbuyer = true;
    $message_to_buyer = elgg_echo('agora:messagetobuyer');
}

// set sold out icon
$status = '';

if (is_numeric($entity->howmany) && $entity->howmany == 0) {
    $status = '<img src="' . elgg_get_site_url() . 'mod/agora/graphics/soldout.png" width="100" height="76" alt="" class="soldout" />';
}

// paypal button
if (elgg_is_logged_in()) {
    if ($isbuyer) {
        $buybuttton = $status;
        $buybuttton .= '<div class="bought">' . $message_to_buyer . '</div>';
        $gallerybutton = '<div class="bought">' . $message_to_buyer . '</div>';
    }

    if (($entity->get_ad_price_with_shipping_cost() > 0) && empty($status) && (multiple_ad_purchase_enabled() || (!$isbuyer && !multiple_ad_purchase_enabled()))) {
        $buybuttton .= '';
        if (agora_check_if_paypal_is_enabled()) {
            $paypal_acount = agora_get_paypal_account($entity->owner_guid);
            if (!empty($paypal_acount)) {
                if (agora_check_if_paypal_adaptive_payments_is_enabled()) { // adaptive payments
                    // get site owner commission
                    $site_owner_commission = agora_get_adaptive_payment_owner_commission($entity->get_ad_price_with_shipping_cost());
                    // check if use sandbox or not
                    $use_sandbox = (agora_use_sandbox_paypal(AGORA_PAYPAL_METHOD_ADAPTIVE) ? TRUE : FALSE);

                    $buybuttton .= amap_generate_adaptive_paypal_button($paypal_acount, $entity, elgg_get_logged_in_user_entity(), 'agora', $site_owner_commission, $use_sandbox, $entity->get_ad_price_with_shipping_cost());
                } else { // standard payments
                    $buybuttton .= agora_generate_paypal_button($paypal_acount, $entity, elgg_get_logged_in_user_entity());
                }
            }
        }

        $gallerybutton .= $buybuttton;
    } else {
        if (!$isbuyer) // we did the some assignment before
            $buybuttton .= $status;
        $gallerybutton .= '&nbsp;';
    }
}
else {
    if ($full && !elgg_in_context('gallery')) {  // login to buy button, only to full view
        $buybuttton = '<div id="login-dropdown">
			<a class="elgg-button elgg-button-dropdown" rel="popup" href="http://' . elgg_get_site_url() . 'login#login-dropdown-box">' . elgg_echo("agora:object:login_to_buy") . '</a>
			</div>';
    } else
        $buybuttton = $status;
    $gallerybutton = '&nbsp;';
}

$digital_icon = '';
if ($entity->digital) {
    $digital_icon .= '<img src="' . elgg_get_site_url() . 'mod/agora/graphics/downloadable_file_tiny.png" alt="' . elgg_echo("agora:download:downloadable_file") . '" class="downloadable_file" />';
}

$owner_link = elgg_view('output/url', array(
    'href' => "agora/owner/$owner->username",
    'text' => $owner->name,
    'is_trusted' => true,
        ));
$author_text = elgg_echo('byline', array($owner_link));

$date = elgg_view_friendly_time($entity->time_created);

//only display if there are commments
if ($entity->comments_on != 'Off') {
    $comments_count = $entity->countComments();
    //only display if there are commments
    if ($comments_count != 0) {
        $text = (comrat_only_buyers_enabled() ? elgg_echo("agora:comments") : elgg_echo("comments")) . " ($comments_count)";
        $comments_link = elgg_view('output/url', array(
            'href' => $entity->getURL() . '#agora-comments',
            'text' => $text,
            'is_trusted' => true,
        ));
    } else {
        $comments_link = '';
    }
} else {
    $comments_link = '';
}

$metadata = elgg_view_menu('entity', array(
    'entity' => $entity,
    'handler' => 'agora',
    'sort_by' => 'priority',
    'class' => 'elgg-menu-hz',
        ));

if (comrat_only_buyers_enabled()) { // get ratings if enabled for buyers
    $entity_ratings = elgg_view('ratings/ratings', ['entity' => $entity]);
}

$subtitle = "$author_text $date $comments_link" . ($entity_ratings?'<br />'.$entity_ratings:'');

if ($full && !elgg_in_context('gallery')) {
    $params = array(
        'entity' => $entity,
        'title' => false,
        'metadata' => $metadata,
        'subtitle' => $subtitle,
    );
    $params = $params + $vars;
    $summary = elgg_view('object/elements/summary', $params);

    $body = '';
    $body .= '<div class="agorabody elgg-image-block clearfix">';
    $body .= '<div class="elgg-image">';

    $thumbnail = elgg_view('output/img', array(
        'src' => agora_getImageUrl($entity, 'large'),
        'class' => "elgg-photo",
    ));

    $body .= elgg_view('output/url', array(
        'href' => agora_getImageUrl($entity, 'master'),
        'text' => $thumbnail,
        'class' => "elgg-lightbox market-thumbnail",
        'rel' => 'market-gallery',
    ));

    $body .= '</div>';
    $body .= '<div class="elgg-body">';
    $body .= '<div class="elgg-content">';

    // button with total price
    if ($entity->get_ad_price_with_shipping_cost() > 0) {
        $body .= '<div class="agoraprint">';
        $body .= '<div class="total_price">' . elgg_echo('agora:object:total_cost') . '<br /><strong>' . get_agora_currency_sign($entity->currency) . ' ' . $entity->price_final . '</strong></div>';
        $body .= $buybuttton;
        $body .= '</div>';
    }

    if ($entity->price) {
        $body .= '<div class="list_features"><strong>' . elgg_echo('agora:price') . '</strong>: ' . get_agora_currency_sign($entity->currency) . ' ' . $entity->price . '</div>';
        if ($entity->tax_cost) {
            $body .= '<div class="list_features"><strong>' . elgg_echo('agora:add:tax_cost') . '</strong>: ' . get_agora_currency_sign($entity->currency) . ' ' . $entity->get_ad_tax_cost() . '</div>';
        }
    }
    if ($entity->shipping_cost) {
        $body .= '<div class="list_features"><strong>' . elgg_echo('agora:add:shipping_cost') . '</strong>: ' . get_agora_currency_sign($entity->currency) . ' ' . $entity->get_ad_shipping_cost() . '</div>';
    }

    if ($entity->category) {
        //$body .= '<div class="list_features"><strong>'.elgg_echo('agora:category') . '</strong>: '.agora_get_cat_name_settings($entity->category, true).'</div>';
        $body .= '<div class="list_features"><strong>' . elgg_echo('agora:category') . '</strong>: ' . $entity->category . '</div>';
    }
    if (is_geolocation_enabled() && $entity->location) {
        $clocation = elgg_view('output/url', array(
            'href' => elgg_get_site_url() . "agora/map?guid={$entity->guid}",
            'text' => $entity->location,
        ));
        $body .= '<div class="list_features"><strong>' . elgg_echo('agora:location') . '</strong>: ' . $clocation . '</div>';
    }
    if (is_numeric($entity->howmany)) {
        $body .= '<div class="list_features"><strong>' . elgg_echo('agora:howmany') . '</strong>: ' . $entity->howmany . '</div>';
    }
    if ($entity->digital) {
        $body .= '<div class="list_features"><strong>' . elgg_echo('agora:download:type') . '</strong>: ' . elgg_echo('agora:download:downloadable_file') . '&nbsp;' . $digital_icon . '</div>';
    }

    $body .= '</div>';
    $body .= '</div>';

    // be interested form
    if (
            elgg_is_logged_in() 
            && elgg_is_active_plugin("messages") 
            && check_if_members_can_send_private_message() 
            && empty($status) 
            && !(elgg_get_logged_in_user_guid() == $entity->owner_guid)
    ) {

        $form_params = array(
            'id' => 'interested-in-form',
            'class' => 'hidden mtl',
        );
        $body_params = array(
            'classified_guid' => $entity->guid,
            'recipient_guid' => $entity->owner_guid,
            'subject' => elgg_echo("agora:be_interested:ad_message_subject", array($entity->title)),
        );
        $interest_form = elgg_view_form('agora/be_interested', $form_params, $body_params);
        // $from_user = get_user($message->fromId);  // mallon obs

        $pmbutton = elgg_view('output/url', array(
            'name' => 'reply',
            'class' => 'elgg-button elgg-button-action',
            'rel' => 'toggle',
            'href' => '#interested-in-form',
            'text' => elgg_echo('agora:be_interested'),
        ));

        $body .= '<div class="pm">' . $pmbutton . '</div>';
        $body .= $interest_form;
    }

    $images = elgg_extract('images', $vars, FALSE);
    if ($images) {
        $body .= '<div class="agora-gallery">';
        $body .= '<ul class="elgg-gallery agora-icons">';
        foreach ($images as $img) {
            $thumb_img = elgg_view('output/img', array(
                'src' => elgg_normalize_url("agora/icon/{$img->guid}/smamed/" . md5($img->time_created) . ".jpg"),
                'class' => "elgg-photo agora-photo",
                'alt' => $img->title,
            ));

            $full_img = elgg_view('output/url', array(
                'href' => elgg_normalize_url(elgg_get_site_url() . "agora/icon/{$img->guid}/master/" . md5($img->time_created) . '.jpg'),
                'text' => $thumb_img,
                'class' => "agora-icon elgg-lightbox",
            ));
            $body .= '<li>' . $full_img . '</li>';
        }
        $body .= '</ul>';
        $body .= '</div>';
    }

    $body .= '<div class="desc">' . ($entity->description ? agora_get_ad_description($entity->description) : '&nbsp;') . '</div>';

    $body .= '</div>';

    echo elgg_view('object/elements/full', array(
        'entity' => $entity,
        'icon' => $owner_icon,
        'summary' => $summary,
        'body' => $body,
    ));
} 
elseif (elgg_in_context('gallery')) {

    $thumbnail = elgg_view('output/img', array(
        'src' => agora_getImageUrl($entity, 'medium'),
        'class' => "elgg-photo",
    ));

    $image = elgg_view('output/url', array(
        'href' => $entity->getURL(),
        'text' => $thumbnail,
        'class' => "elgg-lightbox market-thumbnail",
        'rel' => 'market-gallery',
    ));

    $galleryhref = elgg_get_site_url() . 'agora/view/' . $entity->guid . '/' . elgg_get_friendly_title($entity->title);
    echo '<div class="agora-gallery-item">';
    echo '<h3>' . elgg_view('output/url', array(
        'href' => $entity->getURL(),
        'text' => $entity->title,
    )) . '</h3>';
    echo $image;
    echo '<p class="gallery-date">' . $owner_link . ' ' . $date . '</p>';
    echo '<div class="gallery-view">';
    if ($entity->category) {
        echo '<strong>' . elgg_echo('agora:category') . '</strong>: ' . agora_get_cat_name_settings($entity->category, true) . '<br />';
    }

    if ($entity->get_ad_price_with_shipping_cost() > 0) {
        echo '<strong>' . elgg_echo('agora:object:total_cost') . '</strong>: ' . get_agora_currency_sign($entity->currency) . ' ' . $entity->get_ad_price_with_shipping_cost() . '<br />';
        echo $gallerybutton;
    }

    echo '</div>';
    echo '</div>';
} else {
    // we want small thumb on group views
    $page_owner = elgg_get_page_owner_entity();
    if (elgg_instanceof($page_owner, 'group'))
        $thumbsize = 'small';
    else
        $thumbsize = 'medium';

    $thumbnail = elgg_view('output/img', array(
        'src' => agora_getImageUrl($entity, $thumbsize),
        'class' => "elgg-photo",
    ));

    $classfd_img .= elgg_view('output/url', array(
        'href' => $entity->getURL(),
        'text' => $thumbnail,
    ));

    $display_text = $url;

    $content = '<div class="agoraprint">' . $buybuttton . '</div>';
    if ($entity->get_ad_price_with_shipping_cost() > 0) {
        $content .= '<div class="list_features"><strong>' . elgg_echo('agora:object:total_cost') . '</strong>: ' . get_agora_currency_sign($entity->currency) . ' ' . $entity->get_ad_price_with_shipping_cost() . '</div>';
    }
    /*
      if ($entity->price) {
      $content .= '<div class="list_features"><strong>'.elgg_echo('agora:price') . '</strong>: '.get_agora_currency_sign($entity->currency).' '.$entity->get_ad_price().($entity->tax_cost?elgg_echo('agora:object:tax_included'):'').'</div>';
      }
     */
    if ($entity->category) {
        $content .= '<div class="list_features"><strong>' . elgg_echo('agora:category') . '</strong>: ' . agora_get_cat_name_settings($entity->category, true) . '</div>';
    }
    if (is_geolocation_enabled() && $entity->location) {
        $clocation = elgg_view('output/url', array(
            'href' => elgg_get_site_url() . "agora/map?guid={$entity->guid}",
            'text' => $entity->location,
        ));
        $content .= '<div class="list_features"><strong>' . elgg_echo('agora:location') . '</strong>: ' . $clocation . '</div>';
    }

    if ($entity->digital) {
        $content .= '<div class="list_features"><strong>' . elgg_echo('agora:download:type') . '</strong>: ' . elgg_echo('agora:download:downloadable_file') . '&nbsp;' . $digital_icon . '</div>';
    }

    $params = array(
        'entity' => $entity,
        'metadata' => $metadata,
        'subtitle' => $subtitle,
        'content' => $content,
    );
    $params = $params + $vars;
    $body = elgg_view('object/elements/summary', $params);

    echo elgg_view_image_block($classfd_img, $body);
}
