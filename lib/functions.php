<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */
 
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
