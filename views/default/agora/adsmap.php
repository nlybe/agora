<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */
 
    // access check for closed groups
    group_gatekeeper();

    $ads = $vars['ads'];
    $mapwidth = $vars['mapwidth'];
    $mapheight = $vars['mapheight'];
    $defaultlocation = $vars['defaultlocation'];
    $defaultzoom = $vars['defaultzoom'];
    $defaultcoords = $vars['defaultcoords'];
    $clustering = $vars['clustering'];
    $clustering_zoom = $vars['clustering_zoom'];
    $cl_guid = $vars['cl_guid'];

    // load google maps js
    elgg_load_js('kmpbasicjs');
    // elgg_load_js('kmpplaceholderjs'); IE placeholder hack, disabled as conflict autolocation complete
    elgg_load_js('kmpgmap1');
    elgg_load_js('kmpgmap2');
    elgg_load_js('kmpgmap3');
    elgg_load_js('kmpgmap4');
    elgg_load_js('kmpomsjs');
    
?>

<script type="text/javascript"><!--
    var gm = google.maps;    
    $(document).ready(function(){

            infowindow = new google.maps.InfoWindow();
            var myLatlng = new google.maps.LatLng(<?php echo $defaultcoords;?>);
            var mapOptions = {
                zoom: <?php echo $defaultzoom;?>,
                center: myLatlng,
                mapTypeId: gm.MapTypeId.ROADMAP
            }
            //var map = new google.maps.Map(document.getElementById("map"), mapOptions);
            var map = new gm.Map(document.getElementById("map"), mapOptions);
            var markerBounds = new google.maps.LatLngBounds();
            var geocoder = new google.maps.Geocoder();
        
            //////////////////// Spiderfier feature start ////////////////////
            var iw = new gm.InfoWindow();
            var oms = new OverlappingMarkerSpiderfier(map,{markersWontMove: true, markersWontHide: true, keepSpiderfied: true});

			/* obs
            oms.addListener('click', function(marker) {
              iw.setContent(marker.desc);
              iw.open(map, marker);
            });
            */
            //////////////////// Spiderfier feature end ////////////////////

            showusers(geocoder, -1, 0, markerBounds, map, 0, 0,oms); 
    });
    
    // search area
    function codeAddress(givenaddr) {
        codeAddressExtend(givenaddr,<?php echo $defaultzoom;?>,'<?php echo elgg_get_site_url();?>','<?php echo $defaultlocation;?>','<?php echo elgg_echo("membersmap:map:2");?>', 0, <?php echo get_unit_of_measurement();?>, '<?php echo get_unit_of_measurement_string_simple();?>');
    } 


    function showusers(geocoder, radius, showradius, markerBounds, map, www1, www2, oms) {
        var ddd;
        var markers = [];
        //alert(www1+','+www2);
<?php
    foreach ($ads as $u)  {
        if (!isset($u->location) || !$u->location) {
            //do nothing
        }
        else    {
            if ($u->getLatitude() && $u->getLongitude())  {
                // remove single and double quotes from title
				$namecleared = remove_shits($u->title);
				
				$entity_description = getEntityDescription($u->description);
				$entity_location = remove_shits($u->location);
				$icon = getEntityIcon($u);
				$entity_title = getEntityTitle($u, $namecleared); 
				$entity_img = getEntityImg($u, $namecleared);
           
?> 
                if (radius>=0) ddd = calcDistance(www1, www2, <?php echo $u->getLatitude();?>, <?php echo $u->getLongitude();?>);
                if (ddd <= radius || radius<0)   {
                    
                    var myLatlng = new google.maps.LatLng(<?php echo $u->getLatitude();?>,<?php echo $u->getLongitude();?>);
                    var marker = new google.maps.Marker({
                        map: map,
                        position: myLatlng,
                        title: '<?php echo $namecleared;?>',
                        icon: '<?php echo $icon;?>'
                    });                
                    google.maps.event.addListener(marker, 'click', function() {
                      infowindow.setContent('<?php echo '<div class="infowindow">'.$entity_img.' '.$entity_title.'<br/>'.$entity_location.'<br/>'.$entity_description.'</div>';?>');
                      infowindow.open(map, this);
                    });  
                    oms.addMarker(marker);  // Spiderfier feature
                    markers.push(marker);
<?php 
	if ($cl_guid == $u->guid)	{ // when is loaded a spcific as, center the map on this and open info window
?>
					map.setCenter(marker.getPosition());
					infowindow_current = new google.maps.InfoWindow();
					infowindow_current	.setContent('<?php echo '<div class="infowindow">'.$entity_img.' '.$entity_title.'<br/>'.$entity_location.'<br/>'.$entity_description.'</div>';?>');
					infowindow_current.open(map, marker);
					
<?php
	}
	
	if (!$cl_guid)	{
?>
                    if (!showradius.checked)    {
                        markerBounds.extend(myLatlng);
                        map.fitBounds(markerBounds);                    
                    } 
<?php
	}
?>                    
                } 
<?php
            }
        }
    }
    
    if ($clustering)    {
?> 
        var markerCluster = new MarkerClusterer(map, markers, {
          maxZoom: <?php echo $clustering_zoom;?>
        });  
        
       
<?php
    }

	// release array to help memory
    unset($ads);
    
?>        
    } // end of showusers
  
  //--></script>

<div id="map" style="width:<?php echo $mapwidth; ?>; height:<?php echo $mapheight; ?>;"></div>

<?php /*print_r($ads); */?>



