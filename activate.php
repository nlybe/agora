<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

$subtypes = array(
    'agora' => 'Agora',
    'agora_img' => 'AgoraImage',
    'agora_sale' => 'AgoraSale',
    'agora_interest' => 'AgoraInterest',
);

foreach ($subtypes as $subtype => $class) {
    if (!update_subtype('object', $subtype, $class)) {
        add_subtype('object', $subtype, $class);
    }
}
