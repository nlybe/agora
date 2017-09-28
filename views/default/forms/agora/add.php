<?php
/**
 * Elgg Agora Classifieds plugin
 * @package Agora
 */

elgg_load_js('agorajs');
elgg_load_css('agora_tooltip_css');

// once elgg_view stops throwing all sorts of junk into $vars, we can use 
$title = elgg_extract('title', $vars, '');
$category = elgg_extract('category', $vars, '');
$desc = elgg_extract('description', $vars, '');
$price = elgg_extract('price', $vars, 0);
$howmany = elgg_extract('howmany', $vars, 0);
$location = elgg_extract('location', $vars, 0);
$digital = elgg_extract('digital', $vars, 0);
$tax_cost = elgg_extract('tax_cost', $vars, 0);
$shipping_cost = elgg_extract('shipping_cost', $vars, 0);
$shipping_type = elgg_extract('shipping_type', $vars, '');
$currency = elgg_extract('currency', $vars, 0);
$tags = elgg_extract('tags', $vars, '');
$access_id = elgg_extract('access_id', $vars, ACCESS_DEFAULT);
$container_guid = elgg_extract('container_guid', $vars);
if (!$container_guid) {
    $container_guid = elgg_get_logged_in_user_guid();
}
$guid = elgg_extract('guid', $vars, null);

$answers_yesno = array('Yes', 'No');
$answers_shipping_type = array(
    AGORA_SHIPPING_TYPE_TOTAL => AGORA_SHIPPING_TYPE_TOTAL,
    AGORA_SHIPPING_TYPE_PERCENTAGE => AGORA_SHIPPING_TYPE_PERCENTAGE
);

if (empty($currency)) {
    $currency = trim(elgg_get_plugin_setting('default_currency', 'agora'));
}

if (!$location) {
    $user = elgg_get_logged_in_user_entity();
    if ($user->location)
        $location = $user->location;
}

// get currency list
$CurrOptions = get_common_gateway_currencies();

// maximum number of images
$max_images = trim(elgg_get_plugin_setting('max_images', 'agora'));

// show option for comments only if reviews/ratings for buyers is disabled
if (!comrat_only_buyers_enabled()) {
    $comments_input = '<label for="agora_comments_on">' . elgg_echo('comments') . ': </label>';
    $comments_input .= elgg_view('input/dropdown', array(
        'name' => 'comments_on',
        'id' => 'agora_comments_on',
        'value' => elgg_extract('comments_on', $vars, ''),
        'options_values' => array('On' => elgg_echo('on'), 'Off' => elgg_echo('off'))
    ));
} else { // if reviews/ratings for buyers is enabled, make comments on in silence
    $comments_input = elgg_view('input/hidden', array(
        'name' => 'comments_on',
        'id' => 'agora_comments_on',
        'value' => 'On'
    ));
}

// check who can post for retrieving paypal account
$whocanpost = trim(elgg_get_plugin_setting('agora_uploaders', 'agora'));
if ($whocanpost === 'allmembers') {
    $paypal_tip = '<span style="margin-right:20px;color:red;">' . elgg_echo('agora:add:price:note:importantall', array(elgg_get_site_url() . 'agora/user/' . elgg_get_logged_in_user_entity()->username)) . '</span>';
} else if ($whocanpost === 'admins') {
    $paypal_tip = '<span style="margin-right:20px;">' . elgg_echo('agora:add:price:note:importantadmin', array(elgg_get_site_url() . 'admin/settings/agora/')) . '</span>';
}

$allow_digital_products = digital_products_allowed();
if ($allow_digital_products) {
    if ($digital)
        $digital_checked = true;
    else
        $digital_checked = false;

    if ($allow_digital_products == 'digitalplus') { //Sell both digital and non-digital products
        // enable digital file upload
        $digi_file_disabled = false;
        if (!$digital_checked) {
            $digi_file_disabled = true;
        }

        $digi_option_disabled = false;
        $digi_option_red = '';
    } else { //Sell ONLY digital products
        $digital_checked = true;
        $digi_file_disabled = false;
        $digi_option_disabled = true;
        $digi_option_red = '<span style="color:red;">(*)</span>';
    }

    $digital_file_types = trim(elgg_get_plugin_setting('digital_file_types', 'agora'));
}
?>
<script type="text/javascript">
    function acceptTerms() {
        error = 0;
        if (!(document.agoraForm.accept_terms.checked) && (error == 0)) {
            alert('<?php echo elgg_echo('agora:terms:accept:error'); ?>');
            document.agoraForm.accept_terms.focus();
            error = 1;
        }
        if (error == 0) {
            document.agoraForm.submit();
        }
    }

    $(function () {
        $(document).tooltip();
    });
</script>

<p><?php echo elgg_echo('agora:add:requiredfields'); ?></p>

<?php

echo elgg_format_element('div', [], elgg_view_input('text', array(
    'name' => 'title',
    'value' => $title,
    'label' => elgg_echo('agora:add:title'),
    'help' => elgg_echo('agora:add:title:note'),
    'required' => true,
)));

?>

<div>
    <label><?php echo elgg_echo('agora:add:category'); ?></label>:
    <span class='agora_custom_fields_more_info' id='more_info_category' title='<?php echo elgg_echo('agora:add:category:note'); ?>'></span>
<?php echo elgg_view('input/dropdown', array('name' => 'category', 'id' => 'category', 'options_values' => agora_settings('categories'), 'value' => $category, 'class' => 'doseaera')); ?>

    <label><?php echo elgg_echo('agora:add:howmany'); ?></label>:
    <span class='agora_custom_fields_more_info' id='more_info_howmany' title='<?php echo elgg_echo('agora:add:howmany:note'); ?>'></span>
    <?php echo elgg_view('input/text', array('name' => 'howmany', 'value' => $howmany, 'class' => 'short')); ?>    
</div>

<div>
    <label><?php echo elgg_echo('agora:add:price'); ?></label>:
    <span class='agora_custom_fields_more_info' id='more_info_price' title='<?php echo elgg_echo('agora:add:price:note'); ?>'></span>
<?php echo elgg_view('input/text', array('name' => 'price', 'value' => $price, 'class' => 'short doseaera')); ?>
    <br/><?php echo $paypal_tip; ?>
</div>

<div>
    <label><?php echo elgg_echo('agora:add:currency'); ?></label>:
<?php echo elgg_view('input/dropdown', array('name' => 'currency', 'value' => $currency, 'class' => 'doseaera', 'options_values' => $CurrOptions)); ?> 

    <label><?php echo elgg_echo('agora:add:tax_cost'); ?></label>
    <span class='agora_custom_fields_more_info' id='more_info_tax_cost' title='<?php echo elgg_echo('agora:add:tax_cost:note'); ?>'></span>
    <?php echo elgg_view('input/text', array('name' => 'tax_cost', 'value' => $tax_cost, 'class' => 'short')); ?> %
</div>

<div>
    <label><?php echo elgg_echo('agora:add:shipping_cost'); ?></label>
    <span class='agora_custom_fields_more_info' id='more_info_shipping_cost' title='<?php echo elgg_echo('agora:add:shipping_cost:note'); ?>'></span>
<?php echo elgg_view('input/text', array('name' => 'shipping_cost', 'value' => $shipping_cost, 'class' => 'short doseaera')); ?>

    <label><?php echo elgg_echo('agora:add:shipping_type'); ?></label>:
    <span class='agora_custom_fields_more_info' id='more_info_shipping_type' title='<?php echo elgg_echo('agora:add:shipping_type:note'); ?>'></span>
    <span class='agora_custom_fields_more_info_text' id='text_more_info_shipping_type'>

    </span>
<?php
echo elgg_view('input/dropdown', array(
    'name' => 'shipping_type',
    'id' => 'shipping_type',
    'value' => elgg_extract('shipping_type', $vars, ''),
    'options_values' => $answers_shipping_type)
);
?>
</div>

<?php if (digital_products_allowed()) { ?>
    <div class="digitalfile_frame">
        <div id="digital_file_box">
    <?php
    if (get_digital_filename($guid))
        echo elgg_echo('agora:add:digital:alreadyuploaded', array(get_digital_filename($guid)));
    ?>
            <?php echo elgg_view('input/file', array('name' => 'digital_file_box', 'disabled' => $digi_file_disabled)); ?>
        </div>
        <label for="digital"><?php echo elgg_echo('agora:add:digital'); ?>: </label> <?php echo $digi_option_red; ?>
        <span class='agora_custom_fields_more_info' id='more_info_digital' title='<?php echo elgg_echo('agora:add:digital:note', array($digital_file_types)); ?>'></span>
        <?php echo elgg_view('input/checkbox', array('name' => 'digital', 'id' => 'digital', 'checked' => $digital_checked, 'onclick' => 'digital_file_show(this.checked)', 'disabled' => $digi_option_disabled)); ?> 
    </div>
<?php } ?>

<?php if (is_geolocation_enabled()) { ?>
    <div class="location_frame">
        <label><?php echo elgg_echo('agora:add:location'); ?></label>
        <span class='agora_custom_fields_more_info' id='more_info_location' title='<?php echo elgg_echo('agora:add:location:note'); ?>'></span>
        <br /><?php echo elgg_view('input/text', array('name' => 'location', 'value' => $location)); ?>
    </div>
<?php } ?>

<div class="location_frame">
    <label><?php echo elgg_echo('agora:add:description'); ?></label>
    <span class='agora_custom_fields_more_info' id='more_info_description' title='<?php echo elgg_echo('agora:add:description:note'); ?>'></span>
    <?php echo elgg_view('input/' . (agora_html_allowed() ? 'longtext' : 'plaintext'), array('name' => 'description', 'value' => $desc)); ?>
</div>

<div style="display:block; clear:both;">
    <label><?php echo elgg_echo('agora:add:image'); ?></label>
    <span class='agora_custom_fields_more_info' id='more_info_image' title='<?php echo elgg_echo('agora:add:image:note'); ?>'></span>
    <br /><?php echo elgg_view('input/file', array('name' => 'upload', 'class' => 'medium')); ?>
    <?php
    if ($guid) {
        $entity = get_entity($guid);
        $ad_img = elgg_view('output/img', array(
            'src' => agora_getImageUrl($entity, 'medium'),
            'class' => "elgg-photo",
        ));
        echo '<div style="float:right;margin-top: 8px;">' . $ad_img . '</div>';
    }
    ?>     
</div>
<div style="display:block; clear:both;">
    <label><?php echo elgg_echo('agora:add:images'); ?></label>
    <span class='agora_custom_fields_more_info' id='more_info_images' title='<?php echo elgg_echo('agora:add:images:note', array($max_images)); ?>'></span>
    <?php echo elgg_view('input/amap_images', array('name' => 'amap_images', 'guid' => $guid)); ?>
</div>

<div style="display:block; clear:both;">
    <label><?php echo elgg_echo('agora:add:tags'); ?></label>
    <?php echo elgg_view('input/tags', array('name' => 'tags', 'value' => $tags)); ?>
</div>

<div>
    <?php echo $comments_input; ?>
</div>

<div>
    <label><?php echo elgg_echo('access'); ?></label><br />
    <?php echo elgg_view('input/access', array('name' => 'access_id', 'value' => $access_id)); ?>
</div>


<?php
if (check_if_admin_terms_classifieds()) {
// Terms checkbox and link
    $termslink = elgg_view('output/url', array(
        'href' => "mod/agora/terms.php",
        'text' => elgg_echo('agora:terms:title'),
        'class' => "elgg-lightbox",
    ));
    $termsaccept = sprintf(elgg_echo("agora:terms:accept"), $termslink);
    ?>
    <div>
        <input type='checkbox' name='accept_terms'><label><?php echo $termsaccept; ?></label>
    </div>
    <?php
}
?>

<div class="elgg-foot">
    <?php
    if ($guid) {
        echo elgg_view('input/hidden', array('name' => 'agora_guid', 'value' => $guid));
    }
    echo elgg_view('input/hidden', array('name' => 'container_guid', 'value' => $container_guid));
    echo elgg_view('input/submit', array('value' => elgg_echo('agora:add:submit')));
    ?>
</div>
