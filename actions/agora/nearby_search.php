<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */
  
if (!elgg_is_xhr()) {
    return elgg_error_response(elgg_echo('Sorry, Ajax only'));
}

if (!elgg_is_active_plugin("amap_maps_api")){
    return elgg_error_response(elgg_echo('agora:settings:amap_maps_api:notenabled'));
}

elgg_load_library('elgg:amap_maps_api');
elgg_load_library('elgg:amap_maps_api_geo'); 

// get variables
$s_location = get_input("s_location");
$s_radius = (int) get_input('s_radius', 0);
$s_keyword = get_input('s_keyword');
$showradius = get_input('showradius');
$s_category = get_input('s_category');
$s_price_min = get_input('s_price_min');
$s_price_max = get_input('s_price_max');

if ($s_radius>0) {
    $search_radius_txt = amap_ma_get_radius_string($s_radius);
}
else {
    $search_radius_txt = amap_ma_get_default_radius_search_string();
}

$s_radius = amap_ma_get_default_radius_search($s_radius);

// retrieve coords of location asked, if any
$coords = amap_ma_geocode_location($s_location);
   
$options = array(
    "type" => "object",
    'subtype' => 'agora',
    "full_view" => false,
    'limit' => get_input('limit', 0),
    'offset' => get_input('proximity_offset', 0),
    'count' => true
);
$options['metadata_name_value_pairs'][] = array('name' => 'location', 'value' => '', 'operand' => '!=');
$options['metadata_name_value_pairs'][] = array('name' => 'country', 'value' => '', 'operand' => '!=');
$options['metadata_name_value_pairs_operator'] = 'OR';    

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

if ($coords) {
    $search_location_txt = $s_location;
    $s_lat = $coords['lat'];
    $s_long = $coords['long'];

    if ($s_lat && $s_long) {
        $options = add_order_by_proximity_clauses($options, $s_lat, $s_long);
        $options = add_distance_constraint_clauses($options, $s_lat, $s_long, $s_radius);
    }
    $title = elgg_echo('agora:groups:nearby:search', array($search_location_txt));
}

$count = elgg_get_entities($options);
if ($count) {
    $options['count'] = false;
    $entities = elgg_get_entities($options);
}  

$map_objects = array();
if ($entities) {	
    foreach ($entities as $entity) {
        $entity = amap_ma_set_entity_additional_info($entity, 'title', 'description');
    }
    
    foreach ($entities as $e) {
        if ($e->getLatitude() && $e->getLongitude())  {
            $object_x = array();
            $object_x['guid'] = $e->getGUID();
            $object_x['title'] = amap_ma_remove_shits($e->getVolatileData('m_title'));;
            $object_x['description'] = amap_ma_get_entity_description($e->getVolatileData('m_description'));
            $object_x['location'] = elgg_echo('amap_maps_api:location', array(amap_ma_remove_shits($e->getVolatileData('m_location'))));	
            $object_x['lat'] = $e->getLatitude();
            $object_x['lng'] = $e->getLongitude();
            $object_x['icon'] = $e->getVolatileData('m_icon');
            $object_x['other_info'] = $e->getVolatileData('m_other_info');
            $object_x['map_icon'] = $e->getVolatileData('m_map_icon');
            $object_x['info_window'] = $object_x['icon'].' '.$object_x['title'];
            $object_x['info_window'] .= ($object_x['location']?'<br/>'.$object_x['location']:'');
            $object_x['info_window'] .= ($object_x['other_info']?'<br/>'.$object_x['other_info']:'');
            $object_x['info_window'] .= ($object_x['description']?'<br/>'.$object_x['description']:'');            
            array_push($map_objects, $object_x);        
        }
    }

    $sidebar = '';
    if (amap_ma_check_if_add_sidebar_list('agora')) {
        $box_color_flag = true;
        foreach ($entities as $entity) {
            $sidebar .= elgg_view('agora/sidebar_map', array('entity' => $entity, 'box_color' => ($box_color_flag?'box_even':'box_odd')));
            $box_color_flag = !$box_color_flag;
        }
    }        
}    
else {
    $content = elgg_echo('agora:search:personalized:empty');
}

$result = array(
    'error' => false,
    'title' => $title,
    'location' => $search_location_txt,
    'radius' => $search_radius_txt,
    's_radius' => amap_ma_get_default_radius_search($s_radius, true),
    's_radius_no' => $s_radius,
    'content' => $content,
    'map_objects' => json_encode($map_objects),
    's_location_lat' => ($s_lat? $s_lat: ''),
    's_location_lng' => ($s_long? $s_long: ''),
    's_location_txt' => $search_location_txt,
    'sidebar' => $sidebar,
);

// release variables
unset($entities);
unset($map_objects);

echo json_encode($result);
exit;
