<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

elgg_register_event_handler('init', 'system', 'agora_init');
elgg_register_event_handler("pagesetup", "system", "agora_pagesetup");

define('AGORA_GENERAL_YES', 'yes'); // general purpose string for yes
define('AGORA_GENERAL_NO', 'no'); // general purpose string for no
define('AGORA_CUSTOM_DEFAULT_COORDS', '49.037868,14.941406'); // set coords of Europe in case default location is not set
define('AGORA_CUSTOM_DEFAULT_LOCATION', 'Europe'); // set default location in case default location is not set
define('AGORA_CUSTOM_DEFAULT_ZOOM', 10); // set default zoom in case is not set
define('AGORA_CUSTOM_CLUSTER_ZOOM', 7); // set cluster zoom that define when markers grouping ends
define('AGORA_INTEREST_INTEREST', 'INTEREST'); // define initial interest
define('AGORA_INTEREST_ACCEPTED', 'ACCEPTED'); // define interest as accepted
define('AGORA_INTEREST_REJECTED', 'REJECTED'); // define interest as rejected
define('AGORA_PURCHASE_METHOD_PAYPAL', 'Paypal'); // paypal method of purchase
define('AGORA_PURCHASE_METHOD_OFFLINE', 'Offline'); // offline method of purchase
define('AGORA_PAYPAL_METHOD_SIMPLE', 'PayPal'); // simple paypal method
define('AGORA_PAYPAL_METHOD_ADAPTIVE', 'Paypal'); // adaptive paypal method
define('AGORA_COMRAT_EXPIRATION_DAYS', 10); // set no of days expiration for custom comments and rating, if enabled
define('AGORA_COMRAT_NOTIFICATION_DAYS', 3); // set no of days nitification for custom comments and rating, if enabled
//define('AGORA_STAR_REVIEW', 'agora_review'); // define annotation string for star reviews of ads (Elgg 1.8)
//define('AGORA_STAR_RATING_ANNOTATION', 'agora_star_rating'); // define annotation string for star rating of ads (Elgg 1.8)
//define('AGORA_STAR_RATING_RATEMAX', 5); // define max star rating rating for ads
define('AGORA_MAX_IMAGES_GALLERY', 10); // define mmaximum number of images in ads gallery

/**
 * Agora plugin initialization functions.
 */
function agora_init() {
    define('AGORA_SHIPPING_TYPE_TOTAL', elgg_echo('agora:add:total')); // define shipping type total
    define('AGORA_SHIPPING_TYPE_PERCENTAGE', elgg_echo('agora:add:percentage')); // define shipping type percentage	
    // Register subtype
    run_function_once('agora_manager_run_once_subtypes');

    // register a library of helper functions
    elgg_register_library('elgg:agora', elgg_get_plugins_path() . 'agora/lib/agora.php');
    elgg_register_library('elgg:agora:ipnlistener', elgg_get_plugins_path() . 'agora/lib/ipnlistener.php');

    // load maps api libraries if it's enabled. If not, it will not be working
    if (elgg_is_active_plugin("amap_maps_api")) {
        elgg_load_library('elgg:amap_maps_api');
//		elgg_load_library('elgg:amap_maps_api_geocoder'); 
    }

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
    // elgg_register_css('agora_tooltip_css', '//code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css'); // OBS
    
    // Extend js
    elgg_register_js('paypal', elgg_get_site_url() . 'mod/agora/assets/paypal-button.min');  // paypal button  
//    elgg_register_js('agorajs', elgg_get_site_url() . 'mod/agora/assets/agora.js'); // OBS
//    elgg_register_js('agora_tooltip_js', '//code.jquery.com/ui/1.11.1/jquery-ui.js'); // OBS
    
    // Register a page handler, so we can have nice URLs
    elgg_register_page_handler('agora', 'agora_page_handler');

    // Register a URL handler for agora
    elgg_register_plugin_hook_handler('entity:url', 'object', 'agora_set_url');

    // Register menu item to an ownerblock
    elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'agora_owner_block_menu');

    // register plugin hooks
    elgg_register_plugin_hook_handler("public_pages", "walled_garden", "agora_walled_garden_hook");

    // Register actions
    $action_path = elgg_get_plugins_path() . 'agora/actions';
    elgg_register_action('agora/add', "$action_path/add.php");
    elgg_register_action('agora/delete', "$action_path/del.php");
    elgg_register_action('agora/be_interested', "$action_path/be_interested.php");
    elgg_register_action('agora/set_accepted', "$action_path/set_accepted.php");
    elgg_register_action('agora/set_rejected', "$action_path/set_rejected.php");
    elgg_register_action("agora/usersettings", "$action_path/usersettings.php"); // save user settings
    elgg_register_action('agora/comments/add', "$action_path/comments/add.php");
    elgg_register_action('agora/icon/delete', "$action_path/icon/icon_del.php");
    elgg_register_action('agora/nearby_search', "$action_path/agora/nearby_search.php", 'public');
    // OBS elgg_register_action('agora/search', "$action_path/agora/search.php", 'public');
    // Register actions admin
    elgg_register_action('agora/admin/general_options', "$action_path/admin/settings.php", 'admin');
    elgg_register_action('agora/admin/paypal_options', "$action_path/admin/settings.php", 'admin');
    elgg_register_action('agora/admin/map_options', "$action_path/admin/settings.php", 'admin');
    elgg_register_action('agora/admin/digital_options', "$action_path/admin/settings.php", 'admin');

    // extend group main page 
    elgg_extend_view('groups/tool_latest', 'agora/group_module');

    // add the group agora tool option
    add_group_tool_option('agora', elgg_echo('agora:group:enableagora'), true);

    // Add agora widget for displaying available ads
    elgg_register_widget_type('agora', elgg_echo('agora:widget'), elgg_echo('agora:widget:description'), array('profile', 'groups', 'dashboard'));
    //elgg_register_widget_type('agora', elgg_echo('agora:widget'), elgg_echo('agora:widget:description'));
    // Add agora widget for displaying bought and sold items
    elgg_register_widget_type('agorabs', elgg_echo('agora:widget:boughtandsold'), elgg_echo('agora:widget:description'), array('profile', 'dashboard'));
    //elgg_register_widget_type('agorabs', elgg_echo('agora:widget:boughtandsold'), elgg_echo('agora:widget:description'));	
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
    $vars = array();
    $vars['page'] = $page[0];

    $base = elgg_get_plugins_path() . 'agora/pages/agora';
    switch ($page[0]) {
        case "ipn":
            include "$base/ipn.php";
            break;

        case "ipnadaptive":
            include "$base/ipnadaptive.php";
            break;

        case "all":
            agora_register_toggle();
            set_input('category', $page[1]);
            include "$base/all.php";
            break;

        case "map":
            set_input('category', $page[1]);
            include "$base/nearby.php";
            break;

        case "owner":
            agora_register_toggle();
            set_input('category', $page[2]);
            include "$base/owner.php";
            break;

        case "friends":
            agora_register_toggle();
            include "$base/friends.php";
            break;

        case "mypurchases":
            agora_register_toggle();
            include "$base/mypurchases.php";
            break;

        case "view":
            set_input('guid', $page[1]);
            include "$base/view.php";
            break;

        case "add":
            gatekeeper();
            include "$base/add.php";
            break;

        case "edit":
            gatekeeper();
            set_input('guid', $page[1]);
            include "$base/edit.php";
            break;

        case "download":
            set_input('guid', $page[1]);
            include "$base/download.php";
            break;

        case "group":
            group_gatekeeper();
            agora_register_toggle();
            set_input('category', $page[3]);
            include "$base/owner.php";
            break;

        case "user":
            set_input('username', $page[1]);
            include "$base/usersettings.php";
            break;

        case 'icon':
            $img = get_entity($page[1]);

            if (isset($_GET['vtype']) && $_GET['vtype'] == 'openview')
                $ia = elgg_set_ignore_access(true);

            $size = $page[2];
            if (!elgg_instanceof($img, 'object', 'agoraimg')) {
                forward('', '404');
            }

            if ($size == 'original') {
                $size = '';
            }

            // Try and get the icon
            $filehandler = new ElggFile();
            $filehandler->owner_guid = $img->owner_guid;
            $filehandler->setFilename($img->file_prefix . $size . '.jpg');

            $success = false;
            if ($filehandler->open("read")) {
                if ($contents = $filehandler->read($filehandler->getSize())) {
                    $success = true;
                }
            }

            header("Content-type: image/jpeg");
            header('Expires: ' . date('r', time() + 864000));
            header("Pragma: public");
            header("Cache-Control: public");
            header("Content-Length: " . strlen($contents));

            $splitString = str_split($contents, 1024);

            foreach ($splitString as $chunk) {
                echo $chunk;
            }

            if (isset($_GET['vtype']) && $_GET['vtype'] == 'openview')
                elgg_set_ignore_access($ia);

            break;

        case 'image':
            set_input('entity_guid', $page[1]);
            set_input('size', $page[2]);
            set_input('tu', $page[3]);
            include "$base/image.php";
            break;

        default:
            include "$base/all.php";
            return false;
    }

    elgg_pop_context();
    return true;
}

/**
 * Format and return the URL for agora objects, since 1.9.
 *
 * @param string $hook
 * @param string $type
 * @param string $url
 * @param array  $params
 * @return string URL of agora.
 */
function agora_set_url($hook, $type, $url, $params) {
    $entity = $params['entity'];
    if (elgg_instanceof($entity, 'object', 'agora')) {
        $friendly_title = elgg_get_friendly_title($entity->title);
        return "agora/view/{$entity->guid}/$friendly_title";
    }
}

/**
 * Add a menu item to an ownerblock
 */
function agora_owner_block_menu($hook, $type, $return, $params) {
    if (elgg_instanceof($params['entity'], 'user')) {
        $url = "agora/owner/{$params['entity']->username}";
        $item = new ElggMenuItem('agora', elgg_echo('agora'), $url);
        $return[] = $item;
    } else {
        if ($params['entity']->agora_enable != 'no') {
            $url = "agora/group/{$params['entity']->guid}/all";
            $item = new ElggMenuItem('agora', elgg_echo('agora:group'), $url);
            $return[] = $item;
        }
    }

    return $return;
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
 * Call from Paypal must be always accessible, even in walled garden Elgg sites
 *
 * @param string $hook
 * @param string $type
 * @param array $return_value
 * @param array $params
 * @return array
 */
function agora_walled_garden_hook($hook, $type, $return_value, $params) {
    $add = array();
    $add[] = 'ipn';
    $add[] = 'agora/ipn';

    if (is_array($return_value))
        $add = array_merge($add, $return_value);

    return $add;
}

// Agora User settings
function agora_pagesetup() {

    if ($user = elgg_get_logged_in_user_entity()) {

        elgg_register_menu_item("page", array(
            "name" => "agora",
            "text" => elgg_echo("agora:usersettings:settings"),
            "href" => "agora/user/" . $user->username,
            "context" => "settings"
        ));
    }
}

/**
 * Cron function for sending notification to buyers for review of the the ad they bought with link and login
 * 
 * @param type $hook
 * @param type $entity_type
 * @param type $returnvalue
 * @param type $params
 * @return boolean
 */
function agora_review_reminder_cron_hook($hook, $entity_type, $returnvalue, $params) {

    elgg_load_library('elgg:agora');

    if (comrat_only_buyers_enabled()) {

        $buyers_comrat_notify_by = trim(elgg_get_plugin_setting('buyers_comrat_notify_by', 'agora'));
        $notifier = get_user_by_username($buyers_comrat_notify_by);
        if (elgg_instanceof($notifier, 'user'))
            $notifier_guid = $notifier->guid;
        else
            $notifier_guid = elgg_get_site_entity()->guid;

        $ts_upper_days = trim(elgg_get_plugin_setting('buyers_comrat_notify', 'agora'));
        $ts_lower_days = $ts_upper_days + 1;

        $ts_upper = strtotime("-$ts_upper_days days");
        $ts_lower = strtotime("-$ts_lower_days days");

        // set ignore access for loading all entries
        $ia = elgg_get_ignore_access();
        elgg_set_ignore_access(true);

        $options = array(
            'type' => 'object',
            'subtype' => 'agorasales',
            'limit' => 0,
            'created_time_lower' => $ts_lower,
            'created_time_upper' => $ts_upper,
        );

        $getsales = elgg_get_entities($options);

        foreach ($getsales as $gs) {
            $entity = get_entity($gs->txn_vguid);
            if (!elgg_instanceof($entity, 'object', 'agora')) {
                return false;
            }

            if (!check_if_user_commented_this_ad($entity->guid, $gs->txn_buyer_guid)) {
                notify_user($gs->txn_buyer_guid, $notifier_guid, elgg_echo('agora:comments:notify:subject'), elgg_echo('agora:comments:notify:body', array($entity->title, $entity->getURL()))
                );
            }
        }

        // restore ignore access
        elgg_set_ignore_access($ia);
    }

    return false;
}

?>
