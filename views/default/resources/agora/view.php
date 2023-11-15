<?php
/**
 * Elgg Agora Classifieds plugin
 * @package Agora
 */

use Agora\AgoraOptions;

//get entity
$guid = elgg_extract('guid', $vars, '');
elgg_entity_gatekeeper($guid, 'object', Agora::SUBTYPE);

$entity = get_entity($guid);
    
// check if print preview
$print = get_input('view');

elgg_push_breadcrumb(elgg_echo('agora'), 'agora/all');
$page_owner = elgg_get_page_owner_entity();
if ($page_owner instanceof \ElggGroup) {
    elgg_push_breadcrumb($page_owner->name, "agora/group/$page_owner->guid");
} else {
    elgg_push_breadcrumb($page_owner->name, "agora/owner/$page_owner->username");
}

$title = $entity->title;
elgg_push_breadcrumb($title);

// get current user guid, if loggedin
$user = elgg_get_logged_in_user_entity();

// get purchases of current user, if any
$user_purchases = $entity->userPurchasedAd($user->guid);
if (!is_array($user_purchases)) {
    $user_purchases = [];
}

$content = elgg_view_entity($entity, [
    'full_view' => true, 
    'images' => $entity->getMoreImages(),
    'show_responses' => false, // set it to false and check it manually depending if ratings are enabled
]);

if (AgoraOptions::allowedComRatOnlyForBuyers()) { 
    // Reviews and ratings enabled only for buyers, ratings plugin is required
    if (
            $user_purchases[0] instanceof \AgoraSale
            && !check_if_user_commented_this_ad($entity->getGUID(), $user->guid) 
        ) {
        $vars['rate_label'] = elgg_echo('agora:comments:add:rate');
        $vars['comment_label'] = elgg_echo('agora:comments:add:comment');
        $content .= ratings_elgg_view_comments($entity, true, $vars);	// allow add review
    } else {
        $content .= ratings_elgg_view_comments($entity, false);	// disable review form 
    }
} 
else if ($entity->canComment()) {
    $content .= elgg_view_comments($entity);
} 

// add download button if current user has purchased this item or if file is available for free
if ($entity->digital && ($user_purchases[0] instanceof \AgoraSale || !$entity->price || elgg_is_admin_logged_in())) {
    elgg_register_menu_item('title', [
        'name' => 'download_product',
        'text' => elgg_echo('agora:download:file'),
        'href' => "agora/download/$entity->guid",
        'link_class' => 'elgg-button elgg-button-action',
    ]);
}

echo elgg_view_page($title, [
	'content' => $content,
	'filter_id' => '',
	'entity' => $entity,
	'sidebar' => elgg_view('agora/sidebar/request', ['entity' => $entity]),
], 'default', [
	'entity' => $entity,
]);

