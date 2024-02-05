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

		// register plugin settings view
		elgg_register_simplecache_view('agora/settings.js');    
		
		elgg_register_menu_item('site', [
			'name' => 'agora',
			'icon' => 'store',
			'text' => elgg_echo('agora:menu'),
			'href' => elgg_generate_url('default:object:agora'),
		]); 

		if ($user = elgg_get_logged_in_user_entity()) {
		    elgg_register_menu_item("page", [
		        "name" => "agora",
		        "text" => elgg_echo("agora:usersettings:settings"),
		        "href" => "agora/user/" . $user->username,
		        'section' => 'configure',
		        "context" => "settings",
		    ]);
		}

		// Add group option
		elgg()->group_tools->register('agora');
		
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
