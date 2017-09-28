elgg.provide('elgg.amap_map_view');

elgg.amap_map_view.init = function() {
	
    $('#my_location').click(function(){
        //alert($('#my_location').is(":checked"));
        if ($('#my_location').is(':checked')) {
            $('#autocomplete').val($('#user_location').val());
        }
        else {
            $('#autocomplete').val('');
        }
    });
    
    $('#nearby_btn').click(function(){
        var btn_text = $(this).val();
        $(this).prop('value', 'Searching ...');
        $(this).css("opacity", 0.7);
         
        var s_location = $('#autocomplete').val();
        var s_radius = $('#s_radius').val();
        var s_keyword = $('#s_keyword').val();
        var s_sport = $('#s_sport').val();	// ys_groupsmap_plus
        var s_action = $('#s_action').val();
        
        var showradius ;
        if ($('#showradius').is(':checked')) 
            showradius = 1;
        else
            showradius = 0;

        if (isNaN(s_radius)) {
            elgg.register_error(elgg.echo('amap_maps_api:search:error:radius_invalid'));
            initSearchBtn(btn_text);       
            return false;
        }
        else if (s_action == 'undefined' || s_action.length === 0) {
            elgg.register_error(elgg.echo('amap_maps_api:search:error:action_undefined'));
            initSearchBtn(btn_text);
            return false;
        }        
        else {	
            elgg.action(s_action, {
                data: {
                    s_location: s_location,
                    s_radius: s_radius,
                    s_keyword: s_keyword,
                    s_sport: s_sport,		// ys_groupsmap_plus
                    showradius: showradius
                },
                success: function (result) {
                    if (result.error) {
                        elgg.register_error(result.msg);
                    } else {
                        $('.elgg-heading-main').html(result.title);
                        $('#map_location').html(result.location);
                        $('#map_radius').html(result.radius);
                        $('#map').html(result.content);
                        $('#map_side_entities').html(result.sidebar);
                        if (s_location) {
							$('#s_radius').val(result.s_radius);
						}
                      
                        //$('#map_parent').html(result.entities_list);
                    }
                },
                complete: function(){
                    initSearchBtn(btn_text);
                }                
                
                        
            });
            return false;	
        }
        
        return false;		
    });


	
};

function initSearchBtn(btn_text) {
    $("#nearby_btn").prop('value', btn_text);
    $("#nearby_btn").css("opacity", 1);
}

elgg.register_hook_handler('init', 'system', elgg.amap_map_view.init);
