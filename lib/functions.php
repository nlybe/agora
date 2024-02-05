<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

use Agora\AgoraOptions;
 
//add classifieds form parameters
function agora_prepare_form_vars($entity = null) {
    // input names => defaults
    $values = [
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
    ];

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

// check if user has commented a specific ad
function check_if_user_commented_this_ad($entity_guid, $user_guid) {
    $noComments = 0;
    $options = [
        'type' => 'object',
        'subtype' => 'comment',
        'container_guid' => $entity_guid,
        'owner_guid' => $user_guid,
        'count' => true,
    ];

    $noComments = elgg_get_entities($options);

    return $noComments;
}

// get ad description
function agora_get_ad_description($description) {
    if (!$description) {
        return false;
    }

    if (AgoraOptions::isHtmlAllowed()) {
        return $description;
    }

    return strip_tags($description);
}

// check if user has purchased a specific ad
function get_digital_filename($entity_guid) {
    $file_ext = 'agora/file-' . $entity_guid . '.zip';
    $options = [
        'type' => 'object',
        'limit' => 0,
        'metadata_name_value_pairs' => [
            ['name' => 'agora_guid', 'value' => $entity_guid, 'operand' => '='],
            ['name' => 'filename', 'value' => $file_ext, 'operand' => '='],
        ],
        'metadata_name_value_pairs_operator' => 'AND',
    ];
    $files = elgg_get_entities($options);

    if (!$files) {
        return false;
    }

    if (count($files) > 0) {
        $file = get_entity($files[0]->guid);
        return $file->originalfilename;
    }

    return false;
}

// get additional tooltip info for price when edit an ad
function getPaypalToolTip() {
    if (!elgg_is_active_plugin('paypal_api')) {
        return '';
    }

    // check who can post for retrieving paypal account
    $whocanpost = trim(elgg_get_plugin_setting('agora_uploaders', 'agora'));
    if ($whocanpost === 'allmembers') {
        return elgg_format_element('div', ['class' => 'paypal_tip'], elgg_echo('agora:add:price:note:importantall', [elgg_normalize_url('agora/user/'.$user->username)]));
    } else if ($whocanpost === 'admins') {
        return elgg_format_element('div', ['class' => 'paypal_tip'], elgg_echo('agora:add:price:note:importantadmin', [elgg_normalize_url('admin/agora/paypal_options/')]));
    }

    return '';
}

/**
 * Sanitise string
 * 
 * @param string $query
 * 
 * @return string
 */
function agoraGetStringSanitised($query) {
    $query = strip_tags($query);
    $query = htmlspecialchars($query, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401, 'UTF-8');
    $query = trim($query);
    
    return $query;
}

/**
 * Format gategory name by removing spaces, capital letters etc
 * 
 * @param string $val
 * 
 * @return string
 */
function agoraGetCatFormatted($val) {
    return elgg_get_friendly_title($val);
}
