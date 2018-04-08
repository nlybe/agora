<?php
/**
 * Elgg Agora Classifieds plugin
 * @package Agora
 */

elgg_load_library('elgg:agora');

$guid = elgg_extract('guid', $vars, '');

// Get the file
$classfd = get_entity($guid);
if (!elgg_instanceof($classfd, 'object', Agora::SUBTYPE)) {
    register_error(elgg_echo("agora:download:filenotexists"));
    forward();
}

if (!$classfd->digital) {
    register_error(elgg_echo("agora:download:nodigitalproduct"));
    forward(REFERRER);
}

if ($classfd->price && !$classfd->userPurchasedAd(elgg_get_logged_in_user_entity()->guid) && !elgg_is_admin_logged_in()) {
    register_error(elgg_echo("agora:download:nopurchased"));
    forward(REFERRER);
}

$file_ext = 'agora/file-' . $classfd->guid . '.zip';

// set ignore access for loading non public objexts
$ia = elgg_get_ignore_access();
elgg_set_ignore_access(true);

$options = array(
    'type' => 'object',
    //'subtype' => 'file',
    'limit' => 0,
    'metadata_name_value_pairs' => array(
        array('name' => 'agora_guid', 'value' => $classfd->guid, 'operand' => '='),
        array('name' => 'filename', 'value' => $file_ext, 'operand' => '='),
    ),
    'metadata_name_value_pairs_operator' => 'AND',
);

$files = elgg_get_entities_from_metadata($options);

if (!$files) {
    register_error(elgg_echo('agora:download:filenotexists'));
    forward(REFERER);
}

if (count($files) > 0) {
    $file = get_entity($files[0]->guid);
} else {
    register_error(elgg_echo('agora:download:filenotexists'));
    forward(REFERER);
}

$mime = $file->getMimeType();
if (!$mime) {
    $mime = "application/octet-stream";
}

$filename = $file->originalfilename;

// fix for IE https issue
header("Pragma: public");

header("Content-type: $mime");
if (strpos($mime, "image/") !== false || $mime == "application/pdf") {
    header("Content-Disposition: inline; filename=\"$filename\"");
} else {
    header("Content-Disposition: attachment; filename=\"$filename\"");
}

ob_clean();
flush();
readfile($file->getFilenameOnFilestore());

// restore ignore access
elgg_set_ignore_access($ia);

exit;


