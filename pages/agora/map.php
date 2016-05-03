<?php
/**
 * Elgg Agora Classifieds plugin
 * @package Agora
 */

elgg_load_library('elgg:agora');

if(elgg_is_active_plugin("amap_maps_api")){
	if (is_geolocation_enabled()) {
		//elgg_load_library('elgg:amap_maps_api');  
		//elgg_load_library('elgg:amap_maps_api_geocoder'); 
		
		// retrieve specific ad if any
		$cl_guid = get_input('guid');
		
		// Retrieve map width 
		$mapwidth = amap_get_map_width();
		// Retrieve map height
		$mapheight = amap_get_map_height();
		// Retrieve map default location
		$defaultlocation = amap_get_map_default_location();
		// Retrieve map zoom
		$mapzoom = amap_get_map_zoom();
		// Retrieve cluster feature
		$clustering = get_map_clustering();
		$clustering_zoom = CUSTOM_CLUSTER_ZOOM;

		// get coords of default location
		$defaultcoords = amap_get_map_default_location_coords();

		// Get category
		$selected_category = get_input('category');
		if ($selected_category == 'all') {
			$category = '';
		} elseif ($selected_category == '') {
			$category = '';
			$selected_category = 'all';
		} else {
			$category = $selected_category;
		}

		$options = array(
			'type' => 'object',
			'subtype' => 'agora',
			'limit' => 0,
			'full_view' => false,
			'view_toggle_type' => false 
		);

		elgg_pop_breadcrumb();
		if (!empty($category)) {
			elgg_push_breadcrumb(elgg_echo('agora'), "agora/all");
			elgg_push_breadcrumb(elgg_echo('agora:label:map'), "agora/map");
			elgg_push_breadcrumb(agora_get_cat_name_settings($category));
			$options['metadata_name_value_pairs'] = array(
				array('name' => 'category', 'value' => $selected_category, 'operand' => '='), 
				array('name' => 'location', 'value' => '', 'operand' => '!='),
			);
			$options['metadata_name_value_pairs_operator'] = 'AND';
			$ads = elgg_get_entities_from_metadata($options);		
			$title = elgg_echo('agora:label:map').': '.agora_get_cat_name_settings($category);
		}
		else  {
			elgg_push_breadcrumb(elgg_echo('agora'), "agora/all");
			elgg_push_breadcrumb(elgg_echo('agora:label:map'));
			$options['metadata_name_value_pairs'] = array(array('name' => 'location', 'value' => '', 'operand' => '!='));
			$ads = elgg_get_entities_from_metadata($options);
			$title = elgg_echo('agora:label:map');
		}

		// check if user can post classifieds
		if (check_if_user_can_post_classifieds())   {
			elgg_register_title_button();
		}

		$content = elgg_view('agora/adsmap', array(
			'ads'=>$ads,
			'mapwidth'=>$mapwidth,
			'mapheight'=>$mapheight,
			'defaultlocation'=>$defaultlocation,
			'defaultzoom'=>$mapzoom,
			'defaultcoords'=>$defaultcoords,
			'clustering'=>$clustering,
			'clustering_zoom'=>$clustering_zoom,
			'cl_guid'=>$cl_guid,
			)
		);		
	 
		$body = elgg_view_layout('content', array(
			'content' => $content,
			'title' => $title,
			'sidebar' => elgg_view('agora/sidebar', array('selected' => $vars['page'])),
			'filter_override' => elgg_view('agora/nav', array('selected' => $vars['page'])),
		));

		echo elgg_view_page($title, $body);
	}
	else
	{
		register_error(elgg_echo("agora:settings:ads_geolocation:notenabled"));
		forward(REFERER);
	}	
}
else
{
	register_error(elgg_echo("agora:settings:amap_maps_api:notenabled"));
	forward(REFERER);
}

// release variables
unset($ads);










