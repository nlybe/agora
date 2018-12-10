<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

//add classifieds form parameters
function agora_prepare_form_vars($entity = null) {
    // input names => defaults
    $values = array(
        'title' => '',
        'description' => '',
        'access_id' => ACCESS_DEFAULT,
        'tags' => '',
        'container_guid' => elgg_get_page_owner_guid(),
        'entity' => $entity,
        'price' => 0,
        'price_final' => 0,
        'currency' => '',
        'category' => '',
        'howmany' => '',
        'location' => '',
        'digital' => '',
        'tax_cost' => '',
        'shipping_cost' => '',
        'shipping_type' => '',
        'climage' => '',
        'guid' => null,
        'comments_on' => NULL,
    );

    if ($entity) {
        foreach (array_keys($values) as $field) {
            if (isset($entity->$field)) {
                $values[$field] = $entity->$field;
            }
        }
    }

    if (elgg_is_sticky_form('agora')) {
        $sticky_values = elgg_get_sticky_values('agora');
        foreach ($sticky_values as $key => $value) {
            $values[$key] = $value;
        }
    }

    elgg_clear_sticky_form('agora');

    return $values;
}

/**
 * General purpose trim function
 * 
 * @param type $value
 */
function agora_trim_value(&$value) {
    $value = trim($value);
}

// Get settings parameters
function agora_settings($name = 'categories', $null = true) {
    $type = elgg_get_plugin_setting($name, 'agora');
    $fields = explode(",", $type);
    if ($null) {
        $field_values[NULL] = elgg_echo('agora:add:category:select');
    }
    foreach ($fields as $val) {
        $key = elgg_get_friendly_title($val);
        if ($key) {
            $field_values[$key] = $val;
        }
    }
    return $field_values;
}

// Get category title
function agora_get_cat_name_settings($catname = null, $linked = false) {
    $type = elgg_get_plugin_setting('categories', 'agora');
    $fields = explode(",", $type);
    foreach ($fields as $val) {
        $key = elgg_get_friendly_title($val);
        if ($key == $catname) {
            if ($linked) {
                $page = 'agora/all/';
                return '<a class="elgg-menu-item" href="' . elgg_get_site_url() . $page . $key . '" title="">' . $val . '</a>';
            } else {
                return $val;
            }
        }
    }
    return null;
}

// check if user has commented a specific ad
function check_if_user_commented_this_ad($classfd_guid, $user_guid) {
    $noComments = 0;
    $options = array(
        'type' => 'object',
        'subtype' => 'comment',
        'container_guid' => $classfd_guid,
        'owner_guid' => $user_guid,
        'count' => true,
    );

    $noComments = elgg_get_entities($options);

    return $noComments;
}

// check if html tags on desctription are allowed
function agora_html_allowed() {
    $html_allowed = trim(elgg_get_plugin_setting('html_allowed', 'agora'));

    if ($html_allowed === 'yes') {
        return true;
    }

    return false;
}

// check if html tags on desctription are allowed
function agora_get_ad_description($description) {
    if (!$description)
        return false;

    if (agora_html_allowed())
        return $description;
    else
        return strip_tags($description);
}

// check if user has purchased a specific ad
function get_digital_filename($classfd_guid) {
    $file_ext = 'agora/file-' . $classfd_guid . '.zip';
    $options = array(
        'type' => 'object',
        'limit' => 0,
        'metadata_name_value_pairs' => array(
            array('name' => 'agora_guid', 'value' => $classfd_guid, 'operand' => '='),
            array('name' => 'filename', 'value' => $file_ext, 'operand' => '='),
        ),
        'metadata_name_value_pairs_operator' => 'AND',
    );

    $files = elgg_get_entities_from_metadata($options);

    if (!$files) {
        return false;
    }

    if (count($files) > 0) {
        $file = get_entity($files[0]->guid);
        return $file->originalfilename;
    }

    return false;
}

// get MD5 hash
function get_MD5_hash($ApiKey, $merchantId, $referenceCode, $amount, $currency) {
    $txtstring = $ApiKey . '~' . $merchantId . '~' . $referenceCode . '~' . $amount . '~' . $currency;

    return md5($txtstring);
}



