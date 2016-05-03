<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */
  
if (!elgg_is_xhr()) {
    register_error('Sorry, Ajax only!');
    forward(REFERRER);
}

//elgg_load_library('elgg:agora');  

// get variables
$s_keyword = get_input('s_keyword');
$showradius = get_input('showradius');
$s_category = get_input('s_category');
$s_price_min = get_input('s_price_min');
$s_price_max = get_input('s_price_max');

$title = elgg_echo('agora:label:map');

$options = array(
	"type" => "object",
	'subtype' => 'agora',
	"full_view" => FALSE,
	'limit' => get_input('limit', 0),
	'offset' => get_input('proximity_offset', 0),
	'count' => true
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
}

$count = elgg_get_entities_from_metadata($options);
if ($count) {
	$options['count'] = false;
	$entities = elgg_list_entities_from_metadata($options);
}  

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

if ($entities) {	
	$content = elgg_view('amap_maps_api/map', array(
		'content' => $entities,
	)); 
   
	$sidebar = '';
}    
else {
	$content = elgg_echo('amap_maps_api:search:personalized:empty');
}

$result = array(
	'error' => false,
	'title' => $title,
	'content' => $content,
	'sidebar' => $sidebar,
);

// release variables
unset($entities);    
unset($entities_min);    
unset($entities_max);   
				
echo json_encode($result);
exit;
