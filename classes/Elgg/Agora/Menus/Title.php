<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

namespace Elgg\Agora\Menus;

use Agora\AgoraOptions;

/**
 * Event callbacks for menus
 */
class Title {
	
	/**
	 * Registers title menu items for agora
	 *
	 * @param \Elgg\Event $event 'register' 'menu:title'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function register(\Elgg\Event $event) {
		$entity = $event->getEntityParam();
		if (!$entity instanceof \Agora) {
			return;
		}
		
		$user = elgg_get_logged_in_user_entity();
		if (empty($user)) {
			return;
		}

		if (!elgg_is_active_plugin("messages") || !AgoraOptions::canMembersSendPrivateMessage())  {
			return;
		};

		if ($user->guid == $entity->owner_guid) {
			return;
		}

		if ($entity->isSoldOut()) {
			return;
		}

		$result = $event->getValue();
		$result[] = \ElggMenuItem::factory([
			'name' => 'interested',
			'class' => 'elgg-button elgg-button-action elgg-lightbox',
			'href' => elgg_generate_url('be_interested:object:agora', [
                'guid' => $entity->guid,
            ]),
			'text' => elgg_echo('agora:be_interested'),
			'icon' => 'hand-point-up',
		]);
		
		return $result;
	}
}
