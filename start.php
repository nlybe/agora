<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

require_once(dirname(__FILE__) . '/lib/hooks.php');
require_once(dirname(__FILE__) . '/lib/widgets.php');

elgg_register_event_handler('init', 'system', 'agora_init');
elgg_register_event_handler("pagesetup", "system", "agora_pagesetup");

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
    
    // Register subtype OBS
//    run_function_once('agora_manager_run_once_subtypes');

    // register a library of helper functions
    elgg_register_library('elgg:agora', elgg_get_plugins_path() . 'agora/lib/agora.php');
    elgg_register_library('elgg:agora:ipnlistener', elgg_get_plugins_path() . 'agora/lib/ipnlistener.php');

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

    // Add admin menu item
    elgg_register_admin_menu_item('configure', 'agora', 'settings');

    // register extra css
    elgg_extend_view('elgg.css', 'agora/css/agora.css');
    elgg_extend_view('css/admin', 'agora/css/agora_admin.css');
    
    // additional options to plugin entities
    elgg_register_plugin_hook_handler('register', 'menu:entity', 'agora_menu_setup'); 
    
    // Register a page handler, so we can have nice URLs
    elgg_register_page_handler('agora', 'agora_page_handler');

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

    // loads the widgets
    agora_widgets_init();
    
    // register actions
    $action_path = elgg_get_plugins_path() . 'agora/actions/agora';
    elgg_register_action('agora/add', "$action_path/add.php");
    elgg_register_action('agora/delete', "$action_path/del.php");
    elgg_register_action('agora/be_interested', "$action_path/be_interested.php");
    elgg_register_action('agora/set_accepted', "$action_path/set_accepted.php");
    elgg_register_action('agora/set_rejected', "$action_path/set_rejected.php");
    elgg_register_action("agora/usersettings", "$action_path/usersettings.php"); // save user settings
    elgg_register_action('agora/icon/delete', "$action_path/icon_del.php");
    elgg_register_action('agora/nearby_search', "$action_path/nearby_search.php", 'public');
    
    // register admin actions
    elgg_register_action('agora/admin/general_options', "$action_path/admin/settings.php", 'admin');
    elgg_register_action('agora/admin/paypal_options', "$action_path/admin/settings.php", 'admin');
    elgg_register_action('agora/admin/map_options', "$action_path/admin/settings.php", 'admin');
    elgg_register_action('agora/admin/ratings_options', "$action_path/admin/settings.php", 'admin');
    elgg_register_action('agora/admin/digital_options', "$action_path/admin/settings.php", 'admin');

    // extend group main page 
    elgg_extend_view('groups/tool_latest', 'agora/group_module');

    // add the group agora tool option
    add_group_tool_option('agora', elgg_echo('agora:group:enableagora'), true);

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
 *  Dispatches agora pages.
 *
 * @param array $page
 * @return bool
 */
function agora_page_handler($page) {
    elgg_push_breadcrumb(elgg_echo('agora'), 'agora/all');

    if (!isset($page[0])) {
        $page[0] = 'all';
    }
    
    $resource_vars = [];
    $resource_vars['page'] = $page[0];
    switch ($page[0]) {
        case "all":
            agora_register_toggle();
            $resource_vars['category'] = elgg_extract(1, $page);
            echo elgg_view_resource('agora/all', $resource_vars);
            break;

        case "map":
            $resource_vars['category'] = elgg_extract(1, $page);
            echo elgg_view_resource('agora/nearby', $resource_vars);
            break;

        case "owner":
            agora_register_toggle();
            $resource_vars['category'] = elgg_extract(2, $page);
            echo elgg_view_resource('agora/owner', $resource_vars);
            break;

        case "friends":
            $resource_vars['category'] = elgg_extract(1, $page);
            agora_register_toggle();
            echo elgg_view_resource('agora/friends', $resource_vars);
            break;

        case "my_purchases":
            agora_register_toggle();
            echo elgg_view_resource('agora/my_purchases', $resource_vars);
            break;

        case "view":
            $resource_vars['guid'] = elgg_extract(1, $page);
            echo elgg_view_resource('agora/view', $resource_vars);
            break;
        
        case "sales":
            $resource_vars['guid'] = elgg_extract(1, $page);
            echo elgg_view_resource('agora/sales', $resource_vars);
            break;
        
        case "requests":
            $resource_vars['guid'] = elgg_extract(1, $page);
            echo elgg_view_resource('agora/requests', $resource_vars);
            break;

        case "add":
            gatekeeper();
            echo elgg_view_resource('agora/add');
            break;

        case "edit":
            gatekeeper();
            $resource_vars['guid'] = elgg_extract(1, $page);
            echo elgg_view_resource('agora/edit', $resource_vars);
            break;

        case "download":
            $resource_vars['guid'] = elgg_extract(1, $page);
            echo elgg_view_resource('agora/download', $resource_vars);
            break;

        case "group":
            group_gatekeeper();
            agora_register_toggle();
            $resource_vars['category'] = elgg_extract(3, $page);
            echo elgg_view_resource('agora/owner', $resource_vars);
            break;

        case "user":
            $resource_vars['username'] = elgg_extract(1, $page);
            echo elgg_view_resource('agora/usersettings', $resource_vars);
            break;
        
        case "transactions":
            switch ($page[1]) {
                case 'view':
                    $resource_vars['guid'] = elgg_extract(2, $page);
                    echo elgg_view_resource('agora/transactions/view', $resource_vars);
                    break;
            }
            break;

        case 'icon':
            $img = new AgoraImage($page[1]);
            $size = $page[2];
            if (!elgg_instanceof($img, 'object', AgoraImage::SUBTYPE)) {
                forward('','404');
            }

            $img->setFilename($img->file_prefix.($size == 'original'?'':$size).'.jpg');
            $filename = $img->getFilenameOnFilestore();            
            $filesize = @filesize($filename);
            if ($filesize) {
                header("Content-type: image/jpeg");
                header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', strtotime("+6 months")), true);
                header("Pragma: public");
                header("Cache-Control: public");
                header("Content-Length: $filesize");
                readfile($filename);
                exit;
            }
            break;  

        case "terms":
            echo elgg_view_resource('agora/terms');
            break;            

        default:
            echo elgg_view_resource('agora/all');
            return false;
    }

    elgg_pop_context();
    return true;
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

/**
 * Register menu item for agora in user settings menu
 */
function agora_pagesetup() {
    if ($user = elgg_get_logged_in_user_entity()) {
        elgg_register_menu_item("page", array(
            "name" => "agora",
            "text" => elgg_echo("agora:usersettings:settings"),
            "href" => "agora/user/" . $user->username,
            "context" => "settings",
        ));
    }
}
