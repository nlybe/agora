<?php
/**
 * Elgg Agora Classifieds plugin
 * @package Agora
 */

// Get file GUID
$entity_guid = (int) get_input('entity_guid', 0);

$entity = get_entity($entity_guid);
if (!$entity || $entity->getSubtype() != "agora") {
	exit;
}

// Get owner
$owner = $entity->getOwnerEntity();

// Get the size
$size = strtolower(get_input('size'));
if (!in_array($size,array('large','medium','small','tiny','master'))) {
    $size = "medium";
}

// Try and get the icon
$filehandler = new ElggFile();
$filehandler->owner_guid = $owner->guid;
$filehandler->setFilename("agora/" . $entity->guid . $size . ".jpg");
		
$success = false;
if ($filehandler->open("read")) {
    if ($contents = $filehandler->read($filehandler->getSize())) {
        $success = true;
    } 
}

if (!$success) {	// backward compatibility
	$size = "";
	$filehandler->setFilename("agora/" . $entity->guid . $size . ".jpg");
	if ($filehandler->open("read")) {
		if ($contents = $filehandler->read($filehandler->getSize())) {
			$success = true;
		} 
	}
}

if (!$success) {
	$path = elgg_get_site_url() . "mod/agora/graphics/noimage{$size}.png";
	header("Location: $path");
	exit;
}

header("Content-type: image/jpeg");
header('Expires: ' . date('r',time() + 864000));
header("Pragma: public");
header("Cache-Control: public");
header("Content-Length: " . strlen($contents));

$splitString = str_split($contents, 1024);

foreach($splitString as $chunk) {
	echo $chunk;
}

