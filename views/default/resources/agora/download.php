<?php
/**
 * Elgg Agora Classifieds plugin
 * @package Agora
 */

 use Elgg\Exceptions\Http\EntityPermissionsException;
 use Elgg\Exceptions\Http\EntityNotFoundException;
 use Elgg\Exceptions\Http\PageNotFoundException;

$guid = elgg_extract('guid', $vars, '');

// Get the file
$entity = get_entity($guid);
if (!$entity instanceof \Agora) {
    throw new EntityNotFoundException();
}

if (!$entity->digital) {
    throw new PageNotFoundException();
}

if ($entity->price && !$entity->userPurchasedAd(elgg_get_logged_in_user_entity()->guid) && !elgg_is_admin_logged_in()) {
    throw new EntityPermissionsException();
}

$file_ext = 'agora/file-' . $entity->guid . '.zip';

elgg_call(ELGG_IGNORE_ACCESS, function () use ($entity, $file_ext) {
    $options = [
        'type' => 'object',
        'subtype' => AgoraFile::SUBTYPE,
        'limit' => 0,
        'container_guid' => $entity->guid,
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


