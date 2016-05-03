<?php
/**
 * Elgg comments add form
 *
 * @package Elgg
 *
 * @uses ElggEntity $vars['entity'] The entity to comment on
 * @uses bool       $vars['inline'] Show a single line version of the form?
 * 
 * Based on core Elgg comments view, modified for agora
 */

if (isset($vars['entity']) && elgg_is_logged_in()) {
	
	$inline = elgg_extract('inline', $vars, false);
	
	if ($inline) {
		echo elgg_view('input/text', array('name' => 'generic_comment'));
		echo elgg_view('input/submit', array('value' => elgg_echo('comment')));
	} else {
?>

	<div class="exemple">
		<label><?php echo elgg_echo("agora:comments:add:rating"); ?></label>
		<div class="exemple3" data-average="0" data-id="3"></div>
	</div>

	<div>
		<label><?php echo elgg_echo("agora:comments:add:review"); ?></label>
		<?php echo elgg_view('input/longtext', array('name' => 'generic_comment')); ?>
	</div>
	<div class="elgg-foot">
<?php
		echo elgg_view('input/submit', array('value' => elgg_echo("agora:comments:post")));
?>
	</div>
	
	<script type="text/javascript">
		$(document).ready(function(){
			$('.exemple3').jRating({
				step: true,
				length: <?php echo AGORA_STAR_RATING_RATEMAX; ?>,
				rateMax: <?php echo AGORA_STAR_RATING_RATEMAX; ?>,
				bigStarsPath: '<?php echo elgg_get_site_url().'mod/agora/graphics/stars.png'; ?>',
				smallStarsPath: '<?php echo elgg_get_site_url().'mod/agora/graphics/small.png'; ?>'
			});
		});
	</script>	
<?php
	}
	
	echo elgg_view('input/hidden', array(
		'name' => 'star_rating',
		'value' => '',
		'id' => 'star_rating'
	));
	
	echo elgg_view('input/hidden', array(
		'name' => 'entity_guid',
		'value' => $vars['entity']->getGUID()
	));	
}
