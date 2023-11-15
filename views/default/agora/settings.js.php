<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

use Agora\AgoraOptions;

$settings = [
    'max_images_gallery' => AgoraOptions::getMaxallowedImages(),
];

?>

define(<?php echo json_encode($settings); ?>);
