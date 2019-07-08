<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

$tabs = array(
    'all' => array(
        'text' => elgg_echo('agora:label:all'),
        'href' => 'agora/all',
        'selected' => $vars['selected'] == 'all',
    ),
);

if (elgg_is_logged_in()) {
    $user = elgg_get_logged_in_user_entity(); // get current user

    $filter_context = '';
    if ($vars['page_owner_guid'] == elgg_get_logged_in_user_guid()) {
        $selected = 'owner';
    }

    $tabs['owner'] = array(
        'text' => elgg_echo('agora:label:owner'),
        'href' => 'agora/owner/' . $user->username,
        'selected' => $selected,
    );
    $tabs['friends'] = array(
        'text' => elgg_echo('agora:label:friends'),
        'href' => 'agora/friends/' . $user->username,
        'selected' => $vars['selected'] == 'friends',
    );
}

if (elgg_is_active_plugin('amap_maps_api') && AgoraOptions::isGeolocationEnabled()) {
    $user = elgg_get_logged_in_user_entity(); // get current user
    $tabs['map'] = array(
        'text' => elgg_echo('agora:label:map'),
        'href' => 'agora/map',
        'selected' => $vars['selected'] == 'map',
    );
}

if (elgg_is_logged_in()) {
    $tabs['my_purchases'] = array(
        'text' => elgg_echo('agora:label:my_purchases'),
        'href' => 'agora/my_purchases/' . $user->username,
        'selected' => $vars['selected'] == 'my_purchases',
    );
}

echo elgg_view('navigation/tabs', array('tabs' => $tabs));
