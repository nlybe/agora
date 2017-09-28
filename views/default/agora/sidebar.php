<?php
/**
 * Elgg Agora Classifieds plugin
 * @package Agora
 */

// Categories
$type = elgg_get_plugin_setting('categories','agora');
$fields = explode(",", $type);
$list_type = $_GET["list_type"];
if ($list_type) {
	$list_type = '?list_type='.$list_type;
}
else  {
	$list_type = '';
}

$page = 'agora/'.$vars['selected'].'/';

echo '<div class="elgg-module elgg-module-aside">';
echo '<div class="elgg-head"><h3>'.elgg_echo("agora:settings:categories").'</h3></div>';
echo '<div class="elgg-body" id="elgg-module elgg-module-aside">';
echo '<ul class="elgg-menu elgg-menu-page elgg-menu-page-default">';	
echo '<li class="'.($vars['category'] == 'all'?'elgg-state-selected':'').'"><a class="elgg-menu-item" href="'.elgg_get_site_url().$page.$key.$list_type.'" title="">'.elgg_echo("agora:categories:all").'</a></li>';
foreach ($fields as $val){
	$key = elgg_get_friendly_title($val);
	if($key){
		echo '<li class="'.($vars['category'] == $key?'elgg-state-selected':'').'"><a class="elgg-menu-item" href="'.elgg_get_site_url().$page.$key.$list_type.'" title="">'.$val.'</a></li>';
	}
}
echo '</ul>';
echo '</div>';
echo '</div>';
// Categories End

if(elgg_is_active_plugin("amap_maps_api_geocoder") && is_geolocation_enabled() && $vars['selected']=='map'){
	// load kanelgga maps api libraries
//	elgg_load_library('elgg:amap_maps_api_geocoder');  

	$searchonmap = '';

	$searchonmap .= '<div class="elgg-module  elgg-module-aside">';
	$searchonmap .= '<div class="elgg-head"><h3>'.elgg_echo("agora:search").'</h3></div>';
	$searchonmap .= '<div class="elgg-body">';
	$searchonmap .= '<input class="elgg-input-text" id="address" type="text" value="" placeholder="'.elgg_echo("agora:search:location").'">';
	$searchonmap .= '<input class="elgg-input-text" id="radius" type="text" value="" placeholder="'.get_unit_of_measurement_string().'">';
	$searchonmap .= '<label class="mtm float-alt"><input id="showradius" type="checkbox" value="show" >'.elgg_echo("agora:search:showradius").'</label><br />';
	$searchonmap .= '<input type="submit" class="elgg-button elgg-button-submit" value="'.elgg_echo("agora:search:submit").'" onclick="codeAddress()">';
	$searchonmap .= '</div>';
	$searchonmap .= '</div>';

	if ($user = elgg_get_logged_in_user_entity())   {
		if (!empty($user->location))    {
			$searchonmap .= '<div class="elgg-module  elgg-module-aside">';
			$searchonmap .= '<div class="elgg-head"><h3>'.elgg_echo("agora:searchnearby").'</h3></div>';
			$searchonmap .= '<div class="elgg-body">';
			$searchonmap .= '<small>'.elgg_echo("agora:mylocationsis").'<i>'.$user->location.'</i></small>';
			$searchonmap .= '<input class="elgg-input-text" id="radiusmyloc" type="text" value="" placeholder="'.get_unit_of_measurement_string().'">';
			$searchonmap .= '<label class="mtm float-alt"><input id="showradiusloc" type="checkbox" value="show" >'.elgg_echo("agora:search:showradius").'</label>';
			$searchonmap .= '<input type="submit" class="elgg-button elgg-button-submit" value="'.elgg_echo("agora:search:submit").'" onclick="codeAddress(\''.$user->location.'\')">';
			$searchonmap .= '</div>';
			$searchonmap .= '</div>';
		}
	}

	$searchonmap .= '
		<script>
		/* IE placeholder hack, disabled as conflict geolocation
			$.fn.hide = function() { return this; };
			$(function() {
			 $(\'input, textarea\').placeholder();
			});
		*/
			$(function() {
				$( "#address" ).autocomplete({
					source: function( request, response ) {
					$.ajax({
					url: "http://gd.geobytes.com/AutoCompleteCity",
					dataType: "jsonp",
					data: {
						q: request.term
					},
						success: function( data ) {
							response( data );
						}
					});
				},
				minLength: 3,
				});
			});
		</script>	
	';	

	echo $searchonmap;
}

echo elgg_view('page/elements/comments_block', array(
	'subtypes' => 'agora',
	'owner_guid' => elgg_get_page_owner_guid(),
));

echo elgg_view('page/elements/tagcloud_block', array(
	'subtypes' => 'agora',
	'owner_guid' => elgg_get_page_owner_guid(),
));



