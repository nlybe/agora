<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

require_once(dirname(__FILE__) . '/lib/hooks.php');

elgg_register_event_handler('init', 'system', 'agora_init');

define('AGORA_CUSTOM_DEFAULT_COORDS', '49.037868,14.941406'); // set coords of Europe in case default location is not set
define('AGORA_CUSTOM_DEFAULT_LOCATION', 'Europe'); // set default location in case default location is not set
define('AGORA_CUSTOM_DEFAULT_ZOOM', 10); // set default zoom in case is not set
define('AGORA_CUSTOM_CLUSTER_ZOOM', 7); // set cluster zoom that define when markers grouping ends
define('AGORA_PAYPAL_METHOD_SIMPLE', 'PayPal'); // simple paypal method
define('AGORA_PAYPAL_METHOD_ADAPTIVE', 'Paypal'); // adaptive paypal method

/**
 * Agora plugin initialization functions
 */
function agora_init() {
    define('AGORA_SHIPPING_TYPE_TOTAL', elgg_echo('agora:add:total')); // define shipping type total
    define('AGORA_SHIPPING_TYPE_PERCENTAGE', elgg_echo('agora:add:percentage')); // define shipping type percentage	
    
    // register a library of helper functions
    // elgg_register_library('elgg:agora:ipnlistener', elgg_get_plugins_path() . 'agora/lib/ipnlistener.php');

    // load maps api libraries if it's enabled. If not, it will not be working
    if (elgg_is_active_plugin("amap_maps_api")) {
        elgg_load_library('elgg:amap_maps_api');
    }
    
    // allow to be liked
    elgg_register_plugin_hook_handler('likes:is_likable', 'object:'.Agora::SUBTYPE, 'Elgg\Values::getTrue');

    // register plugin settings view
    elgg_register_simplecache_view('agora/settings.js');    
    
    // Register entity_type for search
    elgg_register_entity_type('object', Agora::SUBTYPE);

    // Site navigation
    $item = new ElggMenuItem('agora', elgg_echo('agora:menu'), 'agora/all');
    elgg_register_menu_item('site', $item);

    // if ($user = elgg_get_logged_in_user_entity()) {
    //     elgg_register_menu_item("page", array(
    //         "name" => "agora",
    //         "text" => elgg_echo("agora:usersettings:settings"),
    //         "href" => "agora/user/" . $user->username,
    //         'section' => 'configure',
    //         "context" => "settings",
    //     ));
    // }

    // register extra css
    elgg_extend_view('elgg.css', 'agora/css/agora.css');
    elgg_extend_view('css/admin', 'agora/css/agora_admin.css');
    
    // additional options to plugin entities
    elgg_register_plugin_hook_handler('register', 'menu:entity', 'agora_menu_setup'); 
    
    // Register a URL handler for agora
    elgg_register_plugin_hook_handler('entity:url', 'object', 'agora_set_url');

    // Register menu item to an ownerblock
    elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'agora_owner_block_menu');

    // register plugin hooks
    elgg_register_plugin_hook_handler("public_pages", "walled_garden", "agora_walled_garden_hook");
    
    // appends input fields for posting ads
    elgg_register_plugin_hook_handler('agora:inputs:config', 'agora', 'agora_input_list');
    
    // register paypal listener hook
    elgg_register_plugin_hook_handler('paypal_api', 'ipn_log', 'agora_paypal_successful_payment_hook');
    elgg_register_plugin_hook_handler('paypal_api', 'ipn_log_adaptive', 'agora_paypal_adaptive_successful_payment_hook');

    elgg_register_plugin_hook_handler('register', 'menu:page', 'agora_notifications_page_menu');

    // Add group option
    elgg()->group_tools->register('agora');

    // Setup cron job to send notification to buyers for review of the the ad they bought with link and login
    elgg_register_plugin_hook_handler('cron', 'daily', 'agora_review_reminder_cron_hook');

    // register ajax view for map
    elgg_register_ajax_view('agora/list');

    // set cover sizes
    elgg_set_config('agora_image_sizes', array(
        'tiny' => array('w' => 25, 'h' => 25, 'square' => true, 'upscale' => false),
        'small' => array('w' => 40, 'h' => 40, 'square' => true, 'upscale' => false),
        'smamed' => array('w' => 100, 'h' => 100, 'square' => true, 'upscale' => false),
        'medium' => array('w' => 150, 'h' => 150, 'square' => true, 'upscale' => false),
        'large' => array('w' => 250, 'h' => 250, 'square' => false, 'upscale' => false),
        'super' => array('w' => 800, 'h' => 800, 'square' => false, 'upscale' => false),
        'master' => array('w' => 1200, 'h' => 1200, 'square' => false, 'upscale' => false),
    ));
}

/**
 * Adds a toggle to extra menu for switching between list and gallery views
 */
function agora_register_toggle() {
    $url = elgg_http_remove_url_query_element(current_page_url(), 'list_type');

    if (get_input('list_type', 'list') == 'list') {
        $list_type = "gallery";
        $icon = elgg_view_icon('grid');
    } else {
        $list_type = "list";
        $icon = elgg_view_icon('list');
    }

    if (substr_count($url, '?')) {
        $url .= "&list_type=" . $list_type;
    } else {
        $url .= "?list_type=" . $list_type;
    }

    elgg_register_menu_item('extras', array(
        'name' => 'agora_list',
        'text' => $icon,
        'href' => $url,
        'title' => elgg_echo("agora:list:$list_type"),
        'priority' => 1000,
    ));
}

///////////////////////////////////////////
// The following moved from lib/agora.php 

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

// get MD5 hash
function get_MD5_hash($ApiKey, $merchantId, $referenceCode, $amount, $currency) {
    $txtstring = $ApiKey . '~' . $merchantId . '~' . $referenceCode . '~' . $amount . '~' . $currency;

    return md5($txtstring);
}
