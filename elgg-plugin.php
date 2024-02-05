<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

use Agora\Elgg\Bootstrap;

require_once(dirname(__FILE__) . '/lib/events.php');
require_once(dirname(__FILE__) . '/lib/functions.php'); 

return [
    'plugin' => [
        'name' => 'Agora Classifieds',
		'version' => '5.15',
		'dependencies' => [
			'geomaps_api' => [
				'must_be_active' => false,
            ],
			'ratings' => [
				'must_be_active' => false,
            ],
			'paypal_api' => [
				'must_be_active' => false,
            ],
        ],
	],	
    'bootstrap' => Bootstrap::class,
    'entities' => [
        [
            'type' => 'object',
            'subtype' => 'agora',
            'class' => 'Agora',
            'capabilities' => [
				'commentable' => true,
				'searchable' => true,
				'likable' => true,
			],
        ],
        [
            'type' => 'object',
            'subtype' => 'agora_img',
            'class' => 'AgoraImage',
            'capabilities' => [
				'commentable' => false,
				'searchable' => false,
				'likable' => false,
			],
        ],
        [
            'type' => 'object',
            'subtype' => 'agora_file',
            'class' => 'AgoraFile',
            'capabilities' => [
				'commentable' => false,
				'searchable' => false,
				'likable' => false,
			],
        ],
        [
            'type' => 'object',
            'subtype' => 'agora_sale',
            'class' => 'AgoraSale',
            'capabilities' => [
				'commentable' => true,
				'searchable' => true,
				'likable' => true,
			],
        ],
        [
            'type' => 'object',
            'subtype' => 'agora_interest',
            'class' => 'AgoraInterest',
            'capabilities' => [
				'commentable' => false,
				'searchable' => false,
				'likable' => false,
			],
        ],
    ],
    'actions' => [
        'agora/add' => [],
        'agora/be_interested' => [],
        'agora/set_accepted' => [],
        'agora/set_rejected' => [],
        'agora/usersettings' => [],
        'agora/icon/delete' => [],
        'agora/admin/paypal_options' => ['access' => 'admin'],
        'agora/admin/map_options' => ['access' => 'admin'],
        'agora/admin/ratings_options' => ['access' => 'admin'],
        'agora/admin/digital_options' => ['access' => 'admin'],
        'agorasale/delete' => [],
    ],
    'events' => [
        'register' => [        
            'menu:title' => [
                'Elgg\Agora\Menus\Title::register' => [],
            ],      
            'menu:entity' => [
                'agora_menu_setup' => [],
                'agorasale_menu_setup' => [],
            ],     
            'menu:owner_block' => [
                'agora_owner_block_menu' => [],
            ],     
            'menu:page' => [
                'agora_notifications_page_menu' => [],
            ],      
        ],
        'entity:url' => [      
            'object' => [
                'agora_set_url' => [],
            ], 
        ],
        'agora:inputs:config' => [      
            'agora' => [
                'agora_input_list' => [],
            ], 
        ],
        'paypal_api' => [      
            'ipn_log' => [
                'agora_paypal_successful_payment_hook' => [],
            ], 
        ],
        'cron' => [      
            'daily' => [
                'agora_review_reminder_cron_hook' => [],
            ], 
        ],
        'action:validate' => [      
            'entity/delete' => [
                'agora_delete_action' => [],
            ], 
        ],
    ],
    'routes' => [
        'default:object:agora' => [
            'path' => '/agora',
            'resource' => 'agora/all',
        ],
        'collection:object:agora:all' => [
            'path' => '/agora/all/{category?}',
            'resource' => 'agora/all',
        ],
        'collection:object:agora:owner' => [
            'path' => '/agora/owner/{username?}/{category?}',
            'resource' => 'agora/owner',
        ],
        'collection:object:agora:friends' => [
            'path' => '/agora/friends/{username?}/{category?}',
            'resource' => 'agora/friends',
        ],
        'collection:object:agora:group' => [
			'path' => '/agora/group/{guid?}/{category?}',
			'resource' => 'agora/group',
		],
		'add:object:agora' => [
            'path' => '/agora/add/{guid?}',
            'resource' => 'agora/add',
            'middleware' => [
                \Elgg\Router\Middleware\Gatekeeper::class,
            ],
        ],
        'edit:object:agora' => [
            'path' => '/agora/edit/{guid}',
            'resource' => 'agora/edit',
            'middleware' => [
                \Elgg\Router\Middleware\Gatekeeper::class,
            ],
        ],
        'be_interested:object:agora' => [
            'path' => '/agora/be_interested/{guid}',
            'resource' => 'agora/be_interested',
            'middleware' => [
                \Elgg\Router\Middleware\Gatekeeper::class,
            ],
        ],
        'view:object:agora' => [
            'path' => '/agora/view/{guid}/{title?}',
            'resource' => 'agora/view',
        ],
        'icon:object:agora' => [
            'path' => '/agora/icon/{guid}/{size?}/{xxx?}',
            'resource' => 'agora/icon',
        ],
        'download:object:agora' => [
            'path' => '/agora/download/{guid}',
            'resource' => 'agora/download',
        ],
        'sales:object:agora' => [
            'path' => '/agora/sales/{guid}',
            'resource' => 'agora/sales',
        ],
        'requests:object:agora' => [
            'path' => '/agora/requests/{guid}',
            'resource' => 'agora/requests',
        ],
        'my_purchases:object:agora' => [
            'path' => '/agora/my_purchases/{username?}',
            'resource' => 'agora/my_purchases',
        ],   
        'settings:agora:user' => [
            'path' => '/agora/user/{username}',
            'resource' => 'agora/usersettings',
        ],    
        'transactions:object:agora' => [
            'path' => '/agora/transactions/view/{guid}/{title?}',
            'resource' => 'agora/transactions/view',
        ],
        'agora:terms' => [
            'path' => '/agora/terms',
            'resource' => 'agora/terms',
        ],    
    ],
    'widgets' => [
        'agora' => [
            'context' => ['profile', 'groups', 'dashboard'],
        ],
        'agorabs' => [
            'context' => ['profile', 'dashboard'],
        ],
    ],
    'views' => [
        'default' => [
            'agora/graphics/' => __DIR__ . '/graphics',
        ],
    ],
	'view_extensions' => [
		'elgg.css' => [
			'agora/css/agora.css' => [],
		],
		'css/admin' => [
			'agora/css/agora_admin.css' => [],
		],
	],
];
