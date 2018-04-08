<?php
/**
 * Elgg Agora Classifieds plugin
 * @package Agora
 */

elgg_load_library('elgg:agora');

//get entity
$guid = elgg_extract('guid', $vars, '');
elgg_entity_gatekeeper($guid, 'object', Agora::SUBTYPE);

$entity = get_entity($guid);
    
// check if print preview
$print = get_input('view');

$page_owner = elgg_get_page_owner_entity();
$crumbs_title = $page_owner->name;
if (elgg_instanceof($page_owner, 'group')) {
    elgg_push_breadcrumb($crumbs_title, "agora/group/$page_owner->guid/all");
} else {
    elgg_push_breadcrumb($crumbs_title, "agora/owner/$page_owner->username");
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
]);

if (AgoraOptions::allowedComRatOnlyForBuyers()) { 
    // Reviews and ratings enabled only for buyers, ratings plugin is required
    if (
            elgg_instanceof($user_purchases[0], 'object', AgoraSale::SUBTYPE) 
            && !check_if_user_commented_this_ad($entity->getGUID(), $user->guid) 
//            && AgoraOptions::checkComratTime($user_purchases[0]->time_created)
        ) {
        $vars['rate_label'] = elgg_echo('agora:comments:add:rate');
        $vars['comment_label'] = elgg_echo('agora:comments:add:comment');
        $content .= RatingsOptions::ratings_elgg_view_comments($entity, true, $vars);	// allow add review
    } else {
        $content .= RatingsOptions::ratings_elgg_view_comments($entity, false);	// disable review form 
    }
} 
else if ($entity->comments_on != 'Off') { 
    // reviews and ratings enabled for all members
    $content .= elgg_view_comments($entity, true, []);
}

// add download button if current user has purchased this item or if file is available for free
if ($entity->digital && (elgg_instanceof($user_purchases[0], 'object', AgoraSale::SUBTYPE) || !$entity->price || elgg_is_admin_logged_in())) {
    elgg_register_menu_item('title', array(
        'name' => 'download_product',
        'text' => elgg_echo('agora:download:file'),
        'href' => "agora/download/$entity->guid",
        'link_class' => 'elgg-button elgg-button-action',
    ));
}

$body = elgg_view_layout('content', array(
    'content' => $content,
    'title' => $title,
    'filter' => '',
    'sidebar' => '',
));

echo elgg_view_page($title, $body);



