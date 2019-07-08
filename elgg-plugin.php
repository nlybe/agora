<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

return [
    'entities' => [
        [
            'type' => 'object',
            'subtype' => 'agora',
            'class' => 'Agora',
            'searchable' => true,
        ],
        [
            'type' => 'object',
            'subtype' => 'agora_img',
            'class' => 'AgoraImage',
            'searchable' => false,
        ],
        [
            'type' => 'object',
            'subtype' => 'agora_sale',
            'class' => 'AgoraSale',
            'searchable' => false,
        ],
        [
            'type' => 'object',
            'subtype' => 'agora_interest',
            'class' => 'AgoraInterest',
            'searchable' => false,
        ],
    ],
    'actions' => [
        'agora/add' => [],
        'agora/delete' => [],
        'agora/be_interested' => [],
        'agora/set_accepted' => [],
        'agora/set_rejected' => [],
        'agora/usersettings' => [],
        'agora/icon/delete' => [],
        'agora/nearby_search' => [],
        'agora/admin/paypal_options' => ['access' => 'admin'],
        'agora/admin/map_options' => ['access' => 'admin'],
        'agora/admin/ratings_options' => ['access' => 'admin'],
        'agora/admin/digital_options' => ['access' => 'admin'],
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
        // 'collection:object:agora:map' => [
        //     'path' => '/agora/map/{category?}',
        //     'resource' => 'agora/nearby',
        // ],        
        'collection:object:agora:owner' => [
            'path' => '/agora/owner/{username?}/{category?}',
            'resource' => 'agora/owner',
        ],
        'collection:object:agora:friends' => [
            'path' => '/agora/friends/{username?}/{category?}',
            'resource' => 'agora/friends',
        ],
        'collection:object:agora:group' => [
			'path' => '/agora/group/{guid?}',
			'resource' => 'agora/owner',
		],
		'add:object:agora' => [
            'path' => '/agora/add/{guid?}',
            'resource' => 'agora/add',
        ],
        'edit:object:agora' => [
            'path' => '/agora/edit/{guid}',
            'resource' => 'agora/edit',
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
    ],
    'widgets' => [
        'agora' => [
            'description' => elgg_echo('agora:widget:description'),
            'context' => ['profile', 'groups', 'dashboard'],
        ],
        'agorabs' => [
            'description' => elgg_echo('agora:widget:bought:description'),
            'context' => ['profile', 'dashboard'],
        ],
    ],
    'views' => [
        'default' => [
            'agora/graphics/' => __DIR__ . '/graphics',
        ],
    ],
    'upgrades' => [],
];
