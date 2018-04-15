<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

elgg_load_library('elgg:agora');

// get variables
$s_keyword = $_POST["s_keyword"];
$s_category = $_POST["s_category"];
$s_price_min = $_POST["s_price_min"];
$s_price_max = $_POST["s_price_max"];
$sort_by = $_POST["sort_by"];

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
    'limit' => 10,
    'full_view' => false,
    'view_toggle_type' => false
);

if ($s_keyword || $s_category || $s_price_min || $s_price_max) {
    $db_prefix = elgg_get_config("dbprefix");
    $options["joins"] = array();
    $options["wheres"] = array();

    if ($s_keyword) {
        $query = sanitise_string($s_keyword);

        array_push($options["joins"], "JOIN {$db_prefix}objects_entity ge ON e.guid = ge.guid");
        array_push($options["wheres"], "(ge.title LIKE '%$query%' OR ge.description LIKE '%$query%')");
    }

    if ($s_category) {
        $s_category = strtolower($s_category);
        array_push($options["joins"], " JOIN elgg_metadata n_sp on e.guid = n_sp.entity_guid JOIN elgg_metastrings mssp on n_sp.name_id = mssp.id JOIN elgg_metastrings msspjv on n_sp.value_id = msspjv.id ");
        array_push($options["wheres"], "(mssp.string = 'category' AND BINARY msspjv.string = '{$s_category}' AND ( (1 = 1) and n_sp.enabled='yes')) ");
    }

    if ($s_price_min) {
        array_push($options["joins"], " JOIN elgg_metadata n_sp_pmin on e.guid = n_sp_pmin.entity_guid JOIN elgg_metastrings mssp_pmin on n_sp_pmin.name_id = mssp_pmin.id JOIN elgg_metastrings msspjv_pmin on n_sp_pmin.value_id = msspjv_pmin.id ");
        array_push($options["wheres"], "(mssp_pmin.string = 'price_final' AND BINARY msspjv_pmin.string >= {$s_price_min} AND ( (1 = 1) and n_sp_pmin.enabled='yes')) ");
    }

    if ($s_price_max) {
        array_push($options["joins"], " JOIN elgg_metadata n_sp_pmax on e.guid = n_sp_pmax.entity_guid JOIN elgg_metastrings mssp_pmax on n_sp_pmax.name_id = mssp_pmax.id JOIN elgg_metastrings msspjv_pmax on n_sp_pmax.value_id = msspjv_pmax.id ");
        array_push($options["wheres"], "(mssp_pmax.string = 'price_final' AND BINARY msspjv_pmax.string <= {$s_price_max} AND ( (1 = 1) and n_sp_pmax.enabled='yes')) ");
    }
}

// sort results
if ($sort_by == 's_price_min') {
    $options["order_by_metadata"] = array('name' => 'price_final', 'direction' => 'ASC', 'as' => 'integer');
} else if ($sort_by == 's_price_max') {
    $options["order_by_metadata"] = array('name' => 'price_final', 'direction' => 'DESC', 'as' => 'integer');
}

elgg_pop_breadcrumb();

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


if (!empty($s_category)) {
    elgg_push_breadcrumb(elgg_echo('agora'), "agora/all");
    elgg_push_breadcrumb(agora_get_cat_name_settings($s_category));
    //$options['metadata_name'] = "category";
    //$options['metadata_value'] = $selected_category;
    $content_tmp = elgg_list_entities_from_metadata($options);
    $title = elgg_echo('agora') . ': ' . agora_get_cat_name_settings($s_category);
} else {
    elgg_push_breadcrumb(elgg_echo('agora'));
    $content_tmp = elgg_list_entities_from_metadata($options);
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
    'sidebar' => elgg_view('agora/sidebar', array('selected' => $vars['page'], 'category' => $selected_category)),
    'filter_override' => elgg_view('agora/nav', array('selected' => $vars['page'])),
));

echo elgg_view_page($title, $body);










