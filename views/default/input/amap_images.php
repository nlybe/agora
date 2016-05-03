<?php
/**
 * Elgg Agora Classifieds plugin
 * @package Agora
 */

$entity = get_entity(get_input('guid'));

// maximum number of images
$max_images = trim(elgg_get_plugin_setting('max_images', 'agora'));

$gallery = '';
if ($entity) {

	$images = elgg_get_entities(array(
		'type' => 'object',
		'subtype' => 'agoraimg',
		'owner_guid' => $entity->guid,
		'limit' => 10,
		'order_by' => 'e.time_created ASC'
	));

	$imgcount = count($images);

	if ($imgcount) {
		$gallery .= '<ul class="elgg-gallery agora-icons">';
		foreach ($images as $img) {
			$thumb_img = elgg_view('output/img', array(
				'src' => elgg_normalize_url("agora/icon/{$img->guid}/smamed/" . md5($img->time_created) . ".jpg"),
				'class' => "elgg-photo agora-photo",
				'alt' => $img->title,
			));

			$full_img = elgg_view('output/url', array(
				'href' => elgg_normalize_url(elgg_get_site_url() . "agora/icon/{$img->guid}/master/" . md5($img->time_created) . '.jpg'),
				'text' => $thumb_img,
				'class' => "agora-icon elgg-lightbox",
			));		
			$gallery .= '<li>';
			$gallery .= $full_img;
						
			//$thumb_url = elgg_get_site_url() . "agora/icon/{$img->guid}/medium/" . md5($img->time_created) . '.jpg';
			//$full_url = elgg_get_site_url() . "agora/icon/{$img->guid}/master/" . md5($img->time_created) . '.jpg';
			//$gallery .= '<li>';
			//$gallery .= "<a class=\"agora-icon elgg-lightbox\" href=\"$full_url\" rel=\"agora-gallery\"><img src=\"$thumb_url\" alt=\"$img->title\" title=\"$img->title\"/></a>";
			$gallery .= elgg_view('output/url', array(
				'text' => elgg_view_icon('delete'),
				'href' => 'action/agora/icon/delete?guid=' . $img->guid,
				'is_action' => true,
				'is_trusted' => true,
				'data-guid' => $img->guid,
				'class' => 'agora-icon-delete'
			));
			$gallery .= '</li>';
		}
		$gallery .= '</ul>';
	}
}
?>

<div>
	<?php
		$icon_class = 'agora-icon-input';
		
		if ($imgcount >= $max_images) {
			$icon_class .= ' hidden';
		}
		
		echo '<div class="' . $icon_class . '">';
		echo '<table id="agora-icon-wrapper"><tr><td>';
        echo elgg_view('input/file', array('name' => 'product_icon[]'));
		echo '</td><td class="remove"></td></tr></table>';
		echo elgg_view('output/url', array(
			'text' => elgg_echo('agora:add:images:another'),
			'href' => '#',
			'class' => 'agora-icon-add-another elgg-button elgg-button-action'
		));
		echo '</div>';
		
		if ($gallery) {
			echo '<br>' . $gallery;
		}
        ?>
</div>

<script>
	$(document).ready(function() {
	
	// add another screenshot field
	$('.agora-icon-add-another').click(function(e) {
		e.preventDefault();
		
		// make sure we don't give more than 8 uploads
		var existing_screenshots = $('ul.agora-icons li').length;
		var available_fields = $('.agora-icon-input table').length;
		var total_screenshots = existing_screenshots + available_fields;
		
		if (total_screenshots >= <?php echo $max_images; ?>) {
			elgg.register_error(elgg.echo('agora:add:images:limit', [<?php echo $max_images; ?>]));
			return;
		}
		
		
		$('#agora-icon-wrapper').clone(true)
			.removeAttr('id')
			.insertBefore($(this))
			.find('td.remove')
			.html('<a href="#"><span class="elgg-icon elgg-icon-delete"></span></a>');
	});
	
	
	// remove screenshot field
	$('td.remove a .elgg-icon-delete').live('click', function(e) {
		e.preventDefault();
		
		$(this).parents('table').eq(0).remove();
	});
	
	
	// delete a screenshot
	$('.agora-icon-delete').live('click', function(e) {
		e.preventDefault();
		
		var container = $(this).parents('li').eq(0);
		
		/* OBS
		if (container.siblings().length === 0) {
			alert(elgg.echo('agora:add:images:last:delete'));
			return;
		}*/
		
		// hide it initially
		container.hide();
		
		elgg.action('agora/icon/delete', {
			timeout: 30000,
			data: {
				guid: $(this).attr('data-guid')
			},
			success: function(result, success, xhr){
                if (result.status == 0) {
					// successfully removed it, remove the markup
					container.remove();
					
					// if we have less than 9 screenshots remaining we can show the field if hidden
					if ($('ul.agora-icons li').length < 9) {
						$('.agora-icon-input').show();
					}
                }
                else {
					// it didn't delete properly, show it again
					container.show();
					elgg.register_error(elgg.echo('agora:icon:imagedelete:failed'));
                }
			},
			error: function(result, response, xhr) {
				container.show();
				elgg.register_error(elgg.echo('agora:icon:imagedelete:failed'));
			}
        });
	});
});

</script>
