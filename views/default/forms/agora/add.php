<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

elgg_require_js("agora/js/agora_add");

$user = elgg_get_logged_in_user_entity();
if (!$user) {
    return;
}

// once elgg_view stops throwing all sorts of junk into $vars, we can use 
$title = elgg_extract('title', $vars, '');
$category = elgg_extract('category', $vars, '');
$description = elgg_extract('description', $vars, '');
$price = elgg_extract('price', $vars, 0);
$howmany = elgg_extract('howmany', $vars, 0);
$location = elgg_extract('location', $vars, 0);
$digital = elgg_extract('digital', $vars, 0);
$tax_cost = elgg_extract('tax_cost', $vars, 0);
$shipping_cost = elgg_extract('shipping_cost', $vars, 0);
$shipping_type = elgg_extract('shipping_type', $vars, '');
$currency = elgg_extract('currency', $vars, 0);
$tags = elgg_extract('tags', $vars, '');
$access_id = elgg_extract('access_id', $vars, ACCESS_DEFAULT);
$container_guid = elgg_extract('container_guid', $vars);
if (!$container_guid) {
    $container_guid = elgg_get_logged_in_user_guid();
}
$guid = elgg_extract('guid', $vars, null);
if ($guid) {
    $entity = get_entity($guid);
}

$answers_yesno = array('Yes', 'No');
$answers_shipping_type = array(
    AGORA_SHIPPING_TYPE_TOTAL => AGORA_SHIPPING_TYPE_TOTAL,
    AGORA_SHIPPING_TYPE_PERCENTAGE => AGORA_SHIPPING_TYPE_PERCENTAGE
);

if (empty($currency)) {
    $currency = trim(elgg_get_plugin_setting('default_currency', 'agora'));
}

if (!$location && $user->location) {
    $location = $user->location;
}

// check who can post for retrieving paypal account
$whocanpost = trim(elgg_get_plugin_setting('agora_uploaders', 'agora'));
if ($whocanpost === 'allmembers') {
    $paypal_tip = elgg_format_element('div', ['class' => 'paypal_tip'], elgg_echo('agora:add:price:note:importantall', [elgg_normalize_url('agora/user/'.$user->username)]));
} else if ($whocanpost === 'admins') {
    $paypal_tip = elgg_format_element('div', ['class' => 'paypal_tip'], elgg_echo('agora:add:price:note:importantadmin', [elgg_normalize_url('admin/settings/agora/')]));
}

$allow_digital_products = AgoraOptions::isDigitalProductsEnabled();
if ($allow_digital_products) {
    $digital_checked = false;
    if ($digital) {
        $digital_checked = true;
    }

    if ($allow_digital_products == 'digitalplus') { // Sell both digital and non-digital products
        // enable digital file upload
        $digi_file_disabled = false;
        if (!$digital_checked) {
            $digi_file_disabled = true;
        }

        $digi_option_disabled = false;
//        $digi_option_red = '';
    } else { //Sell ONLY digital products
        $digital_checked = true;
        $digi_file_disabled = false;
        $digi_option_disabled = true;
//        $digi_option_red = '<span style="color:red;">(*)</span>';
    }

    $digital_file_types = trim(elgg_get_plugin_setting('digital_file_types', 'agora'));
}

echo elgg_format_element('p', [], elgg_echo('agora:add:requiredfields'));

$inputs_list['title_input'] = [
    'priority' => 10,
    'render' => elgg_format_element('div', ['id' => 'title_input'], elgg_view_input('text', array(
        'id' => 'title',
        'name' => 'title',
        'value' => $title,
        'label' => elgg_echo('agora:add:title'),
        'help' => elgg_echo('agora:add:title:note'),
        'required' => true,
    ))),
];

if ($entity && $entity->hasIcon('medium')) {
    $icon_existed = elgg_format_element('div', ['style' => 'float:right;'], elgg_view_entity_icon($entity, 'small', []));
    $icon_style= 'width:75%;';
}
$inputs_list['upload_input'] = [
    'priority' => 20,
    'render' => elgg_format_element('div', ['id' => 'upload_input'], 
        $icon_existed.
        elgg_view_input('file', [
            'id' => 'upload',
            'name' => 'upload',
            'label' => elgg_echo('agora:add:image'),
            'help' => elgg_echo('agora:add:image:note'),
            'style' => $icon_style,
        ]
    )),
];

$max_images = AgoraOptions::getParams('max_images');
if (is_numeric($max_images) && $max_images>0) {
    $inputs_list['images_input'] = [
        'priority' => 25,
        'render' => elgg_format_element('div', ['id' => 'images_input', 'style' => 'display:block; clear:both;'], 
            elgg_view_input('amap_images', [
                'id' => 'amap_images',
                'name' => 'amap_images',
                'guid' => $guid,
                'max_images' => $max_images,
                'label' => elgg_echo('agora:add:images'),
                'help' => elgg_echo('agora:add:images:note', [$max_images]),
            ]
        )),
    ];
}

$inputs_list['category_input'] = [
    'priority' => 30,
    'render' => elgg_format_element('div', ['id' => 'category_input'], elgg_view_input('dropdown', array(
        'id' => 'category',
        'name' => 'category',
        'value' => $category,
        'label' => elgg_echo('agora:add:category'),
        'help' => elgg_echo('agora:add:category:note'),
        'options_values' => agora_settings('categories'),
        'required' => true,
    ))),
];

$inputs_list['howmany_input'] = [
    'priority' => 40,
    'render' => elgg_format_element('div', ['id' => 'howmany_input'], elgg_view_input('text', array(
        'id' => 'howmany',
        'name' => 'howmany',
        'value' => $howmany,
        'label' => elgg_echo('agora:add:howmany'),
        'help' => elgg_echo('agora:add:howmany:note'),
        'class' => 'short',
    ))),
];

$inputs_list['price_input'] = [
    'priority' => 50,
    'render' => elgg_format_element('div', ['id' => 'price_input'], elgg_view_input('text', array(
        'id' => 'price',
        'name' => 'price',
        'value' => $price,
        'label' => elgg_echo('agora:add:price'),
        'help' => elgg_echo('agora:add:price:note').$paypal_tip,
        'class' => 'short',
    ))),
];

$inputs_list['currency_input'] = [
    'priority' => 60,
    'render' => elgg_format_element('div', ['id' => 'currency_input'], elgg_view_input('dropdown', array(
        'id' => 'currency',
        'name' => 'currency',
        'value' => $currency,
        'label' => elgg_echo('agora:add:currency'),
        'help' => elgg_echo('agora:add:currency:note'),
        'options_values' => AgoraOptions::getCommonGatewayCurrencies(),
    ))),
];

$inputs_list['tax_cost_input'] = [
    'priority' => 70,
    'render' => elgg_format_element('div', ['id' => 'tax_cost_input'], elgg_view_input('text', array(
        'id' => 'tax_cost',
        'name' => 'tax_cost',
        'value' => $tax_cost,
        'label' => elgg_echo('agora:add:tax_cost'),
        'help' => elgg_echo('agora:add:tax_cost:note'),
        'class' => 'short',
        'placeholder' => '%',
    ))),
];

$inputs_list['shipping_cost_input'] = [
    'priority' => 80,
    'render' => elgg_format_element('div', ['id' => 'shipping_cost_input'], elgg_view_input('text', array(
        'id' => 'shipping_cost',
        'name' => 'shipping_cost',
        'value' => $shipping_cost,
        'label' => elgg_echo('agora:add:shipping_cost'),
        'help' => elgg_echo('agora:add:shipping_cost:note'),
        'class' => 'short',
    ))),
];

$inputs_list['shipping_type_input'] = [
    'priority' => 90,
    'render' => elgg_format_element('div', ['id' => 'shipping_type_input'], elgg_view_input('dropdown', array(
        'id' => 'shipping_type',
        'name' => 'shipping_type',
        'value' => $shipping_type,
        'label' => elgg_echo('agora:add:shipping_type'),
        'help' => elgg_echo('agora:add:shipping_type:note'),
        'options_values' => $answers_shipping_type,
        'class' => 'short',
    ))),
];

if ($allow_digital_products) {
    $digital_filename = get_digital_filename($guid);
    if ($digital_filename) {
        echo elgg_echo('agora:add:digital:alreadyuploaded', [$digital_filename]);
    }
    
    $inputs_list['digital_input'] = [
        'priority' => 100,
        'render' => elgg_format_element('div', ['id' => 'digital_input'], 
            elgg_view_input('checkbox', [
                'id' => 'digital',
                'name' => 'digital', 
                'checked' => $digital_checked, 
                'onclick' => 'digital_file_show(this.checked)', 
                'disabled' => $digi_option_disabled,
                'label' => elgg_echo('agora:add:digital'),
            ]).
            elgg_view_input('file', [
                'id' => 'digital_file_box',
                'name' => 'digital_file_box',
                'disabled' => $digi_file_disabled,
                'help' => elgg_echo('agora:add:digital:note', [$digital_file_types]),
            ])
        ),
    ];    
}   


if (elgg_is_active_plugin('amap_maps_api') && AgoraOptions::isGeolocationEnabled()) {
    $inputs_list['location_input'] = [
        'priority' => 110,
        'render' => elgg_format_element('div', ['id' => 'location_input'], elgg_view('input/location_autocomplete', array(
            'name' => 'location',
            'value' => $location,
            'label' => elgg_echo('agora:add:location'),
            'help' => elgg_echo('agora:add:location:note'),
        ))),
    ];    
}

$inputs_list['description_input'] = [
    'priority' => 120,
    'render' => elgg_format_element('div', ['id' => 'description_input'], elgg_view_input((agora_html_allowed()?'longtext':'plaintext'), array(
        'id' => 'description',
        'name' => 'description',
        'value' => $description,
        'label' => elgg_echo('agora:add:description'),
        'help' => elgg_echo('agora:add:description:note'),
    ))),
];

$inputs_list['tags_input'] = [
    'priority' => 130,
    'render' => elgg_format_element('div', ['id' => 'tags_input'], elgg_view_input('tags', array(
        'id' => 'tags',
        'name' => 'tags',
        'value' => $tags,
        'label' => elgg_echo('agora:add:tags'),
        'help' => elgg_echo('agora:add:tags:note'),
    ))),
];


if (!AgoraOptions::allowedComRatOnlyForBuyers()) {    
    // show option for comments only if reviews/ratings for buyers is disabled
    $comments_input = elgg_format_element('div', ['id' => 'agora_comments_on_input'], elgg_view_input('dropdown', array(
        'id' => 'agora_comments_on',
        'name' => 'comments_on',
        'value' => elgg_extract('comments_on', $vars, ''),
        'label' => elgg_echo('comments'),
        'options_values' => array('On' => elgg_echo('on'), 'Off' => elgg_echo('off')),
    )));
} 
else {  // if reviews/ratings for buyers is enabled, make comments on in silence
    $comments_input = elgg_format_element('div', ['id' => 'agora_comments_on_input'], elgg_view_input('hidden', array(
        'id' => 'agora_comments_on',
        'name' => 'comments_on',
        'value' => 'On'
    )));
}
$inputs_list['agora_comments_on_input'] = [
    'priority' => 140,
    'render' => $comments_input,
];

$inputs_list['access_id_input'] = [
    'priority' => 150,
    'render' => elgg_format_element('div', ['id' => 'access_id_input'], elgg_view_input('access', array(
        'id' => 'access_id',
        'name' => 'access_id',
        'value' => $access_id,
        'label' => elgg_echo('access'),
    ))),
];

if (!$guid && AgoraOptions::checkTermsClassifieds()) {
    $termslink = elgg_view('output/url', array(
        'href' => elgg_normalize_url("agora/terms"),
        'text' => elgg_echo('agora:terms:title'),
        'class' => "elgg-lightbox",
		'data-colorbox-opts' => json_encode(array(
			'width' => 800,
		)),
    ));
    $termsaccept = sprintf(elgg_echo("agora:terms:accept"), $termslink);
    
    $inputs_list['accept_terms_input'] = [
        'priority' => 160,
        'render' => elgg_format_element('div', ['id' => 'accept_terms_input'], elgg_view_input('checkbox', [
            'id' => 'accept_terms',
            'name' => 'accept_terms', 
            'label' => $termsaccept,
            'required' => true,
        ])),
    ];
}

$inputs = elgg_trigger_plugin_hook('agora:inputs:config', 'agora', $vars, $inputs_list);
foreach ($inputs as $inp) {
    echo $inp['render'];
}

 
if ($guid) {
    $footer = elgg_view('input/hidden', array('name' => 'agora_guid', 'value' => $guid));
}
$footer .= elgg_view('input/hidden', array('name' => 'container_guid', 'value' => $container_guid));
$footer .= elgg_view('input/submit', array('value' => elgg_echo('agora:add:submit')));
echo elgg_format_element('div', ['class' => 'elgg-foot'], $footer);