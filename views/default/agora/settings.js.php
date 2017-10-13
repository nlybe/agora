<?php
/**
 * Elgg AgoraMap Maps Api plugin
 * @package amap_maps_api 
 */

$settings = [
    'max_images_gallery' => AGORA_MAX_IMAGES_GALLERY,
];

?>

define(<?php echo json_encode($settings); ?>);
