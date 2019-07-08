<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

// get variables
$s_keyword = stripslashes(get_input('s_keyword', ''));
$s_category = get_input('s_category', '');
$s_price_min = get_input('s_price_min', '');
$s_price_max = get_input('s_price_max', '');
$sort_by = get_input('sort_by', '');

if (!$sort_by) {
    $sort_by = 'newest';
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

$options = array(
    'type' => 'object',
    'subtype' => Agora::SUBTYPE,
    'full_view' => false,
    'view_toggle_type' => false
);

$options["wheres"] = [
    function(\Elgg\Database\QueryBuilder $qb, $alias) use ($s_keyword, $s_category, $s_price_min, $s_price_max) {
        $ands = [];
        
        if ($s_keyword && !empty($s_keyword)) {
            $s_keyword = sanitise_string($s_keyword);
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
    $options["order_by_metadata"] = array('name' => 'price_final', 'direction' => 'ASC', 'as' => 'integer');
} else if ($sort_by == 's_price_max') {
    $options["order_by_metadata"] = array('name' => 'price_final', 'direction' => 'DESC', 'as' => 'integer');
}

// load the search form
$body_vars = array();
$body_vars['s_action'] = 'agora/search';
$body_vars['initial_keyword'] = $s_keyword;
$body_vars['initial_category'] = $s_category;
$body_vars['initial_price_min'] = $s_price_min;
$body_vars['initial_price_max'] = $s_price_max;
$body_vars['sort_by'] = $sort_by;
$form_vars = array('name' => 'agora_search', 'enctype' => 'multipart/form-data', 'action' => elgg_get_site_url() . 'agora/all');
$content = elgg_view_form('agora/search', $form_vars, $body_vars);

$form_vars = array('name' => 'agora_sort_by', 'enctype' => 'multipart/form-data', 'action' => elgg_get_site_url() . 'agora/all');
$content .= elgg_view_form('agora/sort_by', $form_vars, $body_vars);

elgg_pop_breadcrumb();
if (!empty($s_category)) {
    elgg_push_breadcrumb(elgg_echo('agora'), 'agora/all');
    elgg_push_breadcrumb(agora_get_cat_name_settings($s_category));
    //$options['metadata_name'] = "category";
    //$options['metadata_value'] = $selected_category;
    $content_tmp = elgg_list_entities($options);
    $title = elgg_echo('agora') . ': ' . agora_get_cat_name_settings($s_category);
} 
else {    
    $content_tmp = elgg_list_entities($options);
    $title = elgg_echo('agora');
}

// check if user can post classifieds
if (AgoraOptions::canUserPostClassifieds()) {
    elgg_register_title_button();
}

if (!$content_tmp) {
    $content_tmp = elgg_echo('agora:none');
}

$content .= elgg_view('agora/list', array(
    'content' => $content_tmp,
        ));

$body = elgg_view_layout('content', array(
    'filter_context' => 'all',
    'content' => $content,
    'title' => $title,
    'sidebar' => elgg_view('agora/sidebar', array('selected' => 'all', 'category' => $selected_category)),
    'filter_override' => elgg_view('agora/nav', array('selected' => 'all')),
));

echo elgg_view_page($title, $body);










