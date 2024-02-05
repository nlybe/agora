<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

use Agora\AgoraOptions;
use Elgg\Database\Clauses\OrderByClause;

// get variables
$s_keyword = stripslashes(get_input('s_keyword', ''));
$s_category = get_input('s_category', '');
$s_price_min = get_input('s_price_min', '');
$s_price_max = get_input('s_price_max', '');
$sort_by = get_input('sort_by', '');
if (!$sort_by) {
    $sort_by = 'newest';
}

$user = elgg_get_logged_in_user_entity();
if ($user) {
    elgg_register_menu_item('title', [
        'name' => 'my_purchases',
        'icon' => 'money-check',
        'text' => elgg_echo('agora:label:my_purchases'),
        'href' => elgg_generate_url('my_purchases:object:agora', [
			'username' => $user->username,
		]),
        'link_class' => 'elgg-button elgg-button-action',
    ]);
}

// check if user can post classifieds
if (AgoraOptions::canUserPostClassifieds()) {
    elgg_register_title_button('add', 'object', 'agora');
}

if (!$s_category) {
    // Get category
    $selected_category = elgg_extract('category', $vars, '');
    if ($selected_category == 'all') {
        $s_category = '';
    } elseif ($selected_category == '') {
        $s_category = '';
        $selected_category = 'all';
    } else {
        $s_category = $selected_category;
    }
} else {
    $selected_category = $s_category;
}

$options = [
    'type' => 'object',
    'subtype' => Agora::SUBTYPE,
    'full_view' => false,
    'view_toggle_type' => false
];

$options["wheres"] = [
    function(\Elgg\Database\QueryBuilder $qb, $alias) use ($s_keyword, $s_category, $s_price_min, $s_price_max) {
        $ands = [];
        
        if ($s_keyword && !empty($s_keyword)) {
            $s_keyword = agoraGetStringSanitised($s_keyword);
            $joined_alias = $qb->joinMetadataTable($alias, 'guid', 'title', 'inner', 'alias_1');
            $ands[] = $qb->compare("$joined_alias.value", 'like', "%$s_keyword%", ELGG_VALUE_STRING);
        }
        if ($s_category && !empty($s_category)) {
            $s_category = strtolower($s_category);
            $joined_alias = $qb->joinMetadataTable($alias, 'guid', 'category', 'inner', 'alias_2');
            $ands[] = $qb->compare("$joined_alias.value", '=', $s_category, ELGG_VALUE_STRING);
        }

        if ($s_price_min) {
            $joined_alias = $qb->joinMetadataTable($alias, 'guid', 'price_final', 'inner', 'alias_3');
            $ands[] = $qb->compare("$joined_alias.value", '>=', floatval($s_price_min), ELGG_VALUE_INTEGER);
        } 
        
        if ($s_price_max) {
            $joined_alias = $qb->joinMetadataTable($alias, 'guid', 'price_final', 'inner', 'alias_4');
            $ands[] = $qb->compare("$joined_alias.value", '<=', floatval($s_price_max), ELGG_VALUE_INTEGER);
        } 

        return $qb->merge($ands, 'AND');
    }
];

// sort results
if ($sort_by == 's_price_min') {
    $options["order_by_metadata"] = ['name' => 'price_final', 'direction' => 'ASC', 'as' => 'integer'];
} else if ($sort_by == 's_price_max') {
    $options["order_by_metadata"] = ['name' => 'price_final', 'direction' => 'DESC', 'as' => 'integer'];
}

// load the search form
$body_vars = [];
$body_vars['s_action'] = 'agora/search';
$body_vars['initial_keyword'] = $s_keyword;
$body_vars['initial_category'] = $s_category;
$body_vars['initial_price_min'] = $s_price_min;
$body_vars['initial_price_max'] = $s_price_max;
$body_vars['sort_by'] = $sort_by;
$form_vars = ['name' => 'agora_search', 'enctype' => 'multipart/form-data', 'action' => elgg_get_site_url() . 'agora/all'];
$content = elgg_view_form('agora/search', $form_vars, $body_vars);

$form_vars = ['name' => 'agora_sort_by', 'enctype' => 'multipart/form-data', 'action' => elgg_get_site_url() . 'agora/all'];
$content .= elgg_view_form('agora/sort_by', $form_vars, $body_vars);

elgg_push_collection_breadcrumbs('object', 'agora');
if (!empty($s_category)) {
    $category = AgoraOptions::getCatName($s_category);
    elgg_push_breadcrumb(elgg_echo('agora'), 'agora/all');
    elgg_push_breadcrumb($category);

    $content_tmp = elgg_list_entities($options);
    $title = elgg_echo('agora') . ': ' . $category;
} 
else {
    $content_tmp = elgg_list_entities($options);
    $title = elgg_echo('agora');
}

if (!$content_tmp) {
    $content_tmp = elgg_echo('agora:none');
}

$content .= elgg_view('agora/list', [
    'content' => $content_tmp,
]);

echo elgg_view_page($title, [
    'content' => $content,
    'sidebar' => elgg_view('agora/sidebar', [
        'selected' => 'all', 
        'category' => $selected_category,
        'page' => 'agora/all/', 
    ]),
]);
