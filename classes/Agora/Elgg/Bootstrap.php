<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

namespace Agora\Elgg;

use Elgg\DefaultPluginBootstrap;
use Agora;
// use Agora\AgoraOptions;

class Bootstrap extends DefaultPluginBootstrap {
	
	const HANDLERS = [];
	
	/**
	 * {@inheritdoc}
	 */
	public function init() {
		$this->initViews();
	}

	/**
	 * Init views
	 *
	 * @return void
	 */
	protected function initViews() {
		define('AGORA_SHIPPING_TYPE_TOTAL', elgg_echo('agora:add:total')); // define shipping type total
		define('AGORA_SHIPPING_TYPE_PERCENTAGE', elgg_echo('agora:add:percentage')); // define shipping type percentage	
		
		// allow to be liked
		elgg_register_plugin_hook_handler('likes:is_likable', 'object:'.Agora::SUBTYPE, 'Elgg\Values::getTrue');

		// register plugin settings view
		elgg_register_simplecache_view('agora/settings.js');    
		
		// Site navigation
		// $item = new ElggMenuItem('agora', elgg_echo('agora:menu'), 'agora/all');
		// elgg_register_menu_item('site', $item);
		// add a site navigation item
		elgg_register_menu_item('site', [
			'name' => 'agora',
			'icon' => 'store',
			'text' => elgg_echo('agora:menu'),
			'href' => elgg_generate_url('default:object:agora'),
		]); 

		// if ($user = elgg_get_logged_in_user_entity()) {
		//     elgg_register_menu_item("page", [
		//         "name" => "agora",
		//         "text" => elgg_echo("agora:usersettings:settings"),
		//         "href" => "agora/user/" . $user->username,
		//         'section' => 'configure',
		//         "context" => "settings",
		//     ]);
		// }

		// register extra css
		elgg_extend_view('elgg.css', 'agora/css/agora.css');
		elgg_extend_view('css/admin', 'agora/css/agora_admin.css');
		
		// additional options to plugin entities
		elgg_register_plugin_hook_handler('register', 'menu:entity', 'agora_menu_setup');
		elgg_register_plugin_hook_handler('register', 'menu:entity', 'agorasale_menu_setup');
		
		// Register a URL handler for agora
		elgg_register_plugin_hook_handler('entity:url', 'object', 'agora_set_url');

		// Register menu item to an ownerblock
		elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'agora_owner_block_menu');

		// // Edit delete actions
		// elgg_trigger_plugin_hook('action:validate', 'entity/delete', 'agora_delete_action', true);

		// // register plugin hooks	- OBS
		// elgg_register_plugin_hook_handler("public_pages", "walled_garden", "agora_walled_garden_hook");
		
		// appends input fields for posting ads
		elgg_register_plugin_hook_handler('agora:inputs:config', 'agora', 'agora_input_list');
		
		// register paypal listener hook
		elgg_register_plugin_hook_handler('paypal_api', 'ipn_log', 'agora_paypal_successful_payment_hook');

		elgg_register_plugin_hook_handler('register', 'menu:page', 'agora_notifications_page_menu');

		// Add group option
		elgg()->group_tools->register('agora');

		// Setup cron job to send notification to buyers for review of the the ad they bought with link and login
		elgg_register_plugin_hook_handler('cron', 'daily', 'agora_review_reminder_cron_hook');

		// register ajax view for map
		elgg_register_ajax_view('agora/list');

		// set cover sizes
		elgg_set_config('agora_image_sizes', [
			'tiny' => ['w' => 25, 'h' => 25, 'square' => true, 'upscale' => false],
			'small' => ['w' => 40, 'h' => 40, 'square' => true, 'upscale' => false],
			'smamed' => ['w' => 100, 'h' => 100, 'square' => true, 'upscale' => false],
			'medium' => ['w' => 150, 'h' => 150, 'square' => true, 'upscale' => false],
			'large' => ['w' => 250, 'h' => 250, 'square' => false, 'upscale' => false],
			'super' => ['w' => 800, 'h' => 800, 'square' => false, 'upscale' => false],
			'master' => ['w' => 1200, 'h' => 1200, 'square' => false, 'upscale' => false],
		]);
		
	}
}
