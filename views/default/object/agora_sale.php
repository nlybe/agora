<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

$full = elgg_extract('full_view', $vars, false);
$is_buyer = elgg_extract('is_buyer', $vars, false);

$entity = elgg_extract('entity', $vars, false);
$owner = $entity->getOwnerEntity();

$post = get_entity($entity->container_guid);
if (!$post instanceof Agora) { 
    return;
}

$buyer = get_user($entity->owner_guid);
$seller = get_user($post->owner_guid);
$icon = elgg_view_entity_icon($post, 'small', ['img_class' => 'elgg-photo']);
$transaction_date = date("Y-m-d H:i:s", $entity->time_created);

if ($full) {
    $content .= elgg_view('object/agora/feature', [
        'label' => elgg_echo('agora:transaction:id'), 
        'text' => $entity->transaction_id,
    ]);
    
    $content .= elgg_view('object/agora/feature', [
        'label' => elgg_echo('agora:settings:transactions:date'), 
        'text' => $transaction_date,
    ]);    
    
    $content .= elgg_view('object/agora/feature', [
        'label' => elgg_echo('agora:settings:transactions:post'), 
        'text' => elgg_view('output/url', [
            'href' => elgg_normalize_url("agora/view/{$post->guid}/".elgg_get_friendly_title($post->title)),
            'text' => $post->title,
        ]),
    ]);
    
    $content .= elgg_view('object/agora/feature', [
        'label' => elgg_echo('agora:settings:transactions:seller'), 
        'text' => elgg_view('output/url', ['href' => $seller->getURL(), 'text' => $seller->username]),
    ]);
    $content .= elgg_view('object/agora/feature', [
        'label' => elgg_echo('agora:settings:transactions:buyer'), 
        'text' => elgg_view('output/url', ['href' => $buyer->getURL(), 'text' => $buyer->username]),
    ]);    
    if ($entity->txn_method) {
        $content .= elgg_view('object/agora/feature', [
            'label' => elgg_echo('agora:settings:transactions:method'), 
            'text' => $entity->txn_method,
        ]);
    }

    $params = [
        'entity' => $entity,
        'title' => $post->title,
        //'metadata' => $metadata,
        'content' => elgg_format_element('div', ['style' => 'margin: 10px 0;'], $content),
    ];

    $params = $params + $vars;
    $body = elgg_view('object/elements/summary', $params);

    echo elgg_view_image_block($icon, $body);
}
else {
    if (!$is_buyer) {  
        $icon = '';
    }
    
    $content .= elgg_view('object/agora/feature', [
        'label' => elgg_echo('agora:transaction:id'), 
        'text' => elgg_view('output/url', [
            'href' => $entity->getURL(), 
            'class' => 'elgg-lightbox', 
            'text' => $entity->transaction_id,
            'data-colorbox-opts' => json_encode([
                'width' => 800,
                'height' => 400,
            ]),
        ]),
    ]);
    
    $content .= elgg_view('object/agora/feature', [
        'label' => elgg_echo('agora:transaction:date'), 
        'text' => $transaction_date,
    ]);    
    
    if ($is_buyer) { 
        $content .= elgg_view('object/agora/feature', [
            'label' => elgg_echo('agora:settings:transactions:post'), 
            'text' => elgg_view('output/url', [
                'href' => elgg_normalize_url("agora/view/{$post->guid}/".elgg_get_friendly_title($post->title)),
                'text' => $post->title,
            ]),
        ]);
    }
    
    if (!$is_buyer) {  
        $content .= elgg_view('object/agora/feature', [
            'label' => elgg_echo('agora:buyer'), 
            'text' => elgg_view('output/url', ['href' => $buyer->getURL(), 'text' => $buyer->username]), 
        ]);        
    }
    
    $params = [
        'entity' => $entity,
        'title' => $is_buyer?$post->title:false,
        'content' => elgg_format_element('div', [], $content),
    ];

    $params = $params + $vars;
    $body = elgg_view('object/elements/summary', $params);

    echo elgg_view_image_block($icon, $body);    
}




