<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */
  
if (!elgg_is_xhr()) {
    register_error('Sorry, Ajax only!');
    forward(REFERRER);
}

if(!elgg_is_active_plugin("amap_maps_api")){
    register_error(elgg_echo("agora:settings:amap_maps_api:notenabled"));
    forward(REFERER);
}

//elgg_load_library('elgg:agora');  
elgg_load_library('elgg:amap_maps_api');
//elgg_load_library('elgg:amap_maps_api_geocoder');
elgg_load_library('elgg:amap_maps_api_geo'); 

// get variables
$s_location = get_input("s_location");
$s_radius = (int) get_input('s_radius', 0);
$s_keyword = get_input('s_keyword');
$showradius = get_input('showradius');
$s_category = get_input('s_category');
$s_price_min = get_input('s_price_min');
$s_price_max = get_input('s_price_max');

if ($s_radius>0)
    $search_radius_txt = amap_ma_get_radius_string($s_radius);
else
    $search_radius_txt = amap_ma_get_default_radius_search_string();
    
$s_radius = amap_ma_get_default_radius_search($s_radius);

// get loged-in user, if any
// $user = elgg_get_logged_in_user_entity(); // OBS

// retrieve coords of location asked, if any
$coords = amap_ma_geocode_location($s_location);
   

// Retrieve layers to show on map
$layers = amap_ma_get_map_layers();
// Retrieve default layer
$default_layer = amap_ma_get_map_default_layer();
// Retrieve map default location
$defaultlocation = amap_ma_get_map_default_location();
// Retrieve map zoom
$mapzoom = amap_ma_get_map_zoom();
// Retrieve cluster feature
$clustering = amap_ma_get_map_gm_clustering();
$clustering_zoom = AMAP_MA_CUSTOM_CLUSTER_ZOOM;

// get coords of default location
$defaultcoords = amap_ma_get_map_default_location_coords();

$title = elgg_echo('agora:label:map');

$options = array(
	"type" => "object",
	'subtype' => 'agora',
	"full_view" => FALSE,
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

$count = elgg_get_entities_from_metadata($options);
if ($count) {
	$options['count'] = false;
	$entities = elgg_get_entities_from_metadata($options);
}  

/* OBS
if ($entities) {
	if ($s_price_min && is_numeric($s_price_min)) {
		$entities_min = array();
		foreach ($entities as $entity) {
			if ($entity->get_ad_price_with_shipping_cost() >= $s_price_min) {
				array_push($entities_min, $entity);
			}
		}	
		$entities = $entities_min;	
	}
	
	if ($s_price_max && is_numeric($s_price_max)) {
		$entities_max = array();
		foreach ($entities as $entity) {
			if ($entity->get_ad_price_with_shipping_cost() <= $s_price_max) {
				array_push($entities_max, $entity);
			}
		}	
		$entities = $entities_max;	
	}
}
*/

if ($entities) {	
	foreach ($entities as $entity) {
		$entity = amap_ma_set_entity_additional_info($entity, 'title', 'description');
	}
	
	$content = elgg_view('amap_maps_api/map', array(
		'entities' => $entities,
		'defaultlocation' => $defaultlocation,
		'defaultzoom' => $mapzoom,
		'defaultcoords' => $defaultcoords,
		'clustering' => $clustering,
		'clustering_zoom' => $clustering_zoom,
		'layers' => $layers,
		'default_layer' => $default_layer,
		'osm_base_layer' => amap_ma_get_osm_base_layer(),
		's_location' => ($s_lat && $s_long?$s_lat.','. $s_long:''),
		's_radius' => $s_radius,     
		's_location_txt' => $search_location_txt,
		's_radius_txt' => $search_radius_txt,    
		'showradius' => $showradius,   
	)); 
   
	$sidebar = '';
	if (amap_ma_check_if_add_sidebar_list('agora')) {
		$box_color_flag = true;
		foreach ($entities as $entity) {
			$sidebar .= elgg_view('groupsmap/sidebar', array('entity' => $entity, 'box_color' => ($box_color_flag?'box_even':'box_odd')));
			$box_color_flag = !$box_color_flag;
		}
	}        
}    
else {
	$content = elgg_echo('amap_maps_api:search:personalized:empty');
}

$result = array(
	'error' => false,
	'title' => $title,
	'location' => $search_location_txt,
	'radius' => $search_radius_txt,
	's_radius' => amap_ma_get_default_radius_search($s_radius, true),
	'content' => $content,
	'sidebar' => $sidebar,
);

// release variables
unset($entities);    
unset($entities_min);    
unset($entities_max);   
				
echo json_encode($result);
exit;
