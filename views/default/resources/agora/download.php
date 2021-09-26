<?php
/**
 * Elgg Agora Classifieds plugin
 * @package Agora
 */

$guid = elgg_extract('guid', $vars, '');

// Get the file
$entity = get_entity($guid);
if (!$entity instanceof Agora) { 
    return elgg_error_response(elgg_echo('agora:download:filenotexists'));
}

if (!$entity->digital) {
    return elgg_error_response(elgg_echo('agora:download:nodigitalproduct'));
}

if ($entity->price && !$entity->userPurchasedAd(elgg_get_logged_in_user_entity()->guid) && !elgg_is_admin_logged_in()) {
    return elgg_error_response(elgg_echo('agora:download:nopurchased'));
}

$file_ext = 'agora/file-' . $entity->guid . '.zip';

elgg_call(ELGG_IGNORE_ACCESS, function () use ($entity, $file_ext) {
    $options = [
        'type' => 'object',
        //'subtype' => 'file',
        'limit' => 0,
        'metadata_name_value_pairs' => [
            ['name' => 'agora_guid', 'value' => $entity->guid, 'operand' => '='],
            ['name' => 'filename', 'value' => $file_ext, 'operand' => '='],
        ],
        'metadata_name_value_pairs_operator' => 'AND',
    ];

    $files = elgg_get_entities($options);

    if (!$files) {
        return elgg_error_response(elgg_echo('agora:download:filenotexists'));
    }

    if (count($files) > 0) {
        $file = get_entity($files[0]->guid);
    } else {
        return elgg_error_response(elgg_echo('agora:download:filenotexists'));
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
});

exit;


