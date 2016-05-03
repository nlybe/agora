<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */
 
$selected_item = elgg_extract("value", $vars, "");

?>

<div class="lm_sports">
	<select <?php echo elgg_format_attributes($vars); ?> onchange="this.form.submit()">
	<?php	
	
		echo "<option value='0' >".elgg_echo('agora:add:category:sortby')."</option>";	
		echo "<option value='newest' ".($selected_item=='newest'?'selected=\"selected\"':'')." >".elgg_echo('agora:add:category:newest')."</option>";
		echo "<option value='s_price_min' ".($selected_item=='s_price_min'?'selected=\"selected\"':'')." >".elgg_echo('agora:add:category:s_price_min')."</option>";
		echo "<option value='s_price_max' ".($selected_item=='s_price_max'?'selected=\"selected\"':'')." >".elgg_echo('agora:add:category:s_price_max')."</option>";

	?>
	</select>
</div>


