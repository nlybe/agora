<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

$settings = [
    'max_images_gallery' => AgoraOptions::MAX_IMAGES_GALLERY,
];

?>

define(<?php echo json_encode($settings); ?>);
