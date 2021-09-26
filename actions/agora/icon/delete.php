<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

$guid = get_input('guid');
$img = get_entity($guid);

if (!$img instanceof AgoraImage || !$img->canEdit()) {
    return elgg_error_response(elgg_echo('agora:icon:delete:error'));
}

// Delete all icons from diskspace
$icon_sizes = elgg_get_config('agora_image_sizes');
$prefix = "agora/" . $img->prefix_time . $img->prefix_key;

foreach ($icon_sizes as $name => $size_info) {
    $file = new AgoraImage();
    $file->owner_guid = $img->owner_guid;
    $file->setFilename("{$prefix}{$name}.jpg");
    $filepath = $file->getFilenameOnFilestore();
    if (!$file->delete()) {
        elgg_log("Image file remove failed. Remove $filepath manually, please.", 'WARNING');
    }
}

$img->delete();
forward(REFERER);
