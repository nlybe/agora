<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

if (!elgg_is_active_plugin("amap_maps_api")) {
    register_error(elgg_echo("agora:settings:amap_maps_api:notenabled"));
    forward(REFERER);
}

elgg_load_library('elgg:amap_maps_api');
elgg_load_library('elgg:amap_maps_api_geo');

$user = elgg_get_logged_in_user_entity();

if (amap_ma_not_permit_public_access()) {
    gatekeeper();
}

// Retrieve map width 
$mapwidth = amap_ma_get_map_width();
// Retrieve map height
$mapheight = amap_ma_get_map_height();

// set breadcrumb
elgg_push_breadcrumb(elgg_echo('agora:label:map'));

// set default parameters
$limit = get_input('limit', 0);
$title = elgg_echo('agora:label:map');
$options = array('type' => 'object', 'subtype' => 'agora', 'full_view' => false);

// get variables
$s_location = get_input("l");
$s_radius = (int) get_input("r");
$s_keyword = get_input("q");
$s_category = get_input("s");
$showradius = get_input("sr");
// get initial load option from settings
$initial_load = elgg_get_plugin_setting('initial_load', 'agora');

if (($s_location && $s_radius) || $s_keyword || $s_category) {
    if ($s_keyword || $s_category) {
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
    }

    $search_radius_txt = '';
    if ($s_radius > 0) {
        $search_radius_txt = $s_radius;
    }

    // retrieve coords of location asked, if any
    $coords = amap_ma_geocode_location($s_location);

    if ($coords) {
        $s_radius = amap_ma_get_default_radius_search($s_radius);
        $search_location_txt = $s_location;
        $s_lat = $coords['lat'];
        $s_long = $coords['long'];

        if ($s_lat && $s_long) {
            $options = add_order_by_proximity_clauses($options, $s_lat, $s_long);
            $options = add_distance_constraint_clauses($options, $s_lat, $s_long, $s_radius);
        }
        $title = elgg_echo('agora:groups:nearby:search', array($search_location_txt));
    }
} else {
    $initial_load = elgg_get_plugin_setting('initial_load', 'agora');
    if ($initial_load == 'newest') {
        $limit = amap_ma_get_initial_limit('agora');
        $title = elgg_echo('agora:groups:newest', array($limit));
    } else if ($initial_load == 'location') {
        // retrieve coords of location asked, if any
        if ($user->location) {
            $s_lat = $user->getLatitude();
            $s_long = $user->getLongitude();

            if ($s_lat && $s_long) {
                $s_radius = amap_ma_get_initial_radius('agora');
                $search_radius_txt = $s_radius;
                $s_radius = amap_ma_get_default_radius_search($s_radius);
                $options = add_order_by_proximity_clauses($options, $s_lat, $s_long);
                $options = add_distance_constraint_clauses($options, $s_lat, $s_long, $s_radius);

                $title = elgg_echo('agora:groups:nearby:search', array($user->location));
            }
        }
    }
}

$options['limit'] = $limit;
$options['metadata_name_value_pairs'][] = array('name' => 'location', 'value' => '', 'operand' => '!=');
$options['metadata_name_value_pairs'][] = array('name' => 'country', 'value' => '', 'operand' => '!=');
$options['metadata_name_value_pairs_operator'] = 'OR';

$entities = elgg_get_entities_from_metadata($options);
if ($entities) {
    foreach ($entities as $entity) {
        $entity = amap_ma_set_entity_additional_info($entity, 'title', 'description');
    }
}

// load the search form
$body_vars = array();
$body_vars['s_action'] = 'agora/nearby_search';
$body_vars['initial_location'] = $search_location_txt;
$body_vars['initial_radius'] = $search_radius_txt;
$body_vars['initial_keyword'] = $s_keyword;
$body_vars['initial_category'] = $s_category;
if ($user->location) {
    $body_vars['my_location'] = $user->location;
    if (isset($initial_load) && $initial_load == 'location') {
        $body_vars['initial_location'] = $user->location;
    }
}
$form_vars = array('enctype' => 'multipart/form-data');

$content =  elgg_view_form('amap_maps_api/nearby', $form_vars, $body_vars); 
$content .= elgg_view('amap_maps_api/map_box', array(
    'mapwidth' => $mapwidth,
    'mapheight' => $mapheight,
));

// OBS
//$content = elgg_view_form('agora/nearby', $form_vars, $body_vars);
//if (!$entities) {
//    $content .= elgg_echo('amap_maps_api:search:personalized:empty');
//}
//$content .= elgg_view('amap_maps_api/map_box', array(
//    'mapwidth' => $mapwidth,
//    'mapheight' => $mapheight,
//        ));
//$content .= elgg_view('amap_maps_api/map', array(
//    'entities' => $entities,
//    // 'defaultlocation' => $defaultlocation, // OBS
//    'defaultzoom' => $mapzoom,
//    'defaultcoords' => $defaultcoords,
//    'clustering' => $clustering,
//    'clustering_zoom' => AMAP_MA_CUSTOM_CLUSTER_ZOOM,
//    'layers' => $layers,
//    'default_layer' => $default_layer,
//    'osm_base_layer' => amap_ma_get_osm_base_layer(),
//    's_radius' => $s_radius,
//    'showradius' => (isset($showradius) ? 1 : 0),
//    's_location' => ($s_lat && $s_long ? $s_lat . ',' . $s_long : ''),
//));

$sidebar = '';
$layout = 'one_column';
if (amap_ma_check_if_add_sidebar_list('agora')) {
    $layout = 'content';
    $sidebar = elgg_view('amap_maps_api/sidebar_elist', array(
        'entities' => $entities,
        'mapheight' => $mapheight,
        'list_view' => 'agora/sidebar_map'
    ));
}

$params = array(
    'content' => $content,
    'sidebar' => $sidebar,
    'title' => $title,
    'filter_override' => '',
);

$body = elgg_view_layout($layout, $params);

echo elgg_view_page($title, $body);

// release variables
unset($entities);
