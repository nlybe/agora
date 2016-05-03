<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */
 
$type = elgg_get_plugin_setting('categories','agora');
$fields = explode(",", $type);
$fields = array_map('trim', $fields);

$selected_items = elgg_extract("value", $vars, "");
if(!is_array($selected_items)){
	$selected_items = string_to_tag_array($selected_items);
}
$selected_items = array_map("strtolower", $selected_items);

?>

<div class="agora_categories">
	<select <?php echo elgg_format_attributes($vars); ?>>
	<?php	
	
		echo "<option value=\"0\" >".elgg_echo('agora:add:category:select')."</option>";				

		if(!empty($fields)){
			foreach($fields as $val) {
				//$key = elgg_get_friendly_title($val);	
				if (in_array(strtolower($val), $selected_items)) {
					echo "<option value=\"$val\" selected=\"selected\">$val</option>";
				} 
				else {
					echo "<option value=\"$val\" >$val</option>";
				}					
			}
		}

	?>
	</select>
</div>


