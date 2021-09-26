<?php
/**
 * Elgg Agora Classifieds plugin
 * @package Agora
 */

$guid = elgg_extract('guid', $vars, 0);
$size = elgg_extract('size', $vars, '');

$img = get_entity($guid);

if (!$img instanceof AgoraImage) {
    forward('','404');
}

$img->setFilename($img->file_prefix.($size == 'original'?'':$size).'.jpg');
$filename = $img->getFilenameOnFilestore();            
$filesize = @filesize($filename);
if ($filesize) {
    header("Content-type: image/jpeg");
    header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', strtotime("+6 months")), true);
    header("Pragma: public");
    header("Cache-Control: public");
    header("Content-Length: $filesize");
    readfile($filename);
    exit;
}