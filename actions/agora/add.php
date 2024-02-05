<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

use Agora\AgoraOptions;

// check if user can post classifieds
if (!AgoraOptions::canUserPostClassifieds()) { 
    return elgg_error_response(elgg_echo('agora:add:noaccessforpost'));
}
    
// Get variables
$title = get_input("title");
$desc = get_input("description");
$price = get_input("price");
$howmany = get_input("howmany");
$location = get_input("location");
$digital = get_input("digital");
$tax_cost = get_input("tax_cost");
$shipping_cost = get_input("shipping_cost");
$shipping_type = get_input("shipping_type");
$currency = get_input("currency");
$category = get_input("category");
$tags = get_input("tags");
$access_id = (int) get_input("access_id");
$guid = (int) get_input('agora_guid');
$container_guid = get_input('container_guid', elgg_get_logged_in_user_guid());
$comments_on = get_input("comments_on");

elgg_make_sticky_form('agora');

if (!$title) {
    return elgg_error_response(elgg_echo('agora:save:missing_title'));
}

if ($price && !is_numeric($price)) {
    return elgg_error_response(elgg_echo('agora:save:price_not_numeric'));
}  

if ($howmany && !is_numeric($howmany)) {
    return elgg_error_response(elgg_echo('agora:save:howmany_not_numeric'));
}  

if ($tax_cost && !is_numeric($tax_cost)) {
    return elgg_error_response(elgg_echo('agora:save:tax_cost_not_numeric'));
}  

if ($shipping_cost && !is_numeric($shipping_cost)) {
    return elgg_error_response(elgg_echo('agora:save:shipping_cost_not_numeric'));
}  

// upload uploaded
$uploaded_files = elgg_get_uploaded_files('upload');
if ($uploaded_files && is_array($uploaded_files)) {
    $uploaded_file = array_shift($uploaded_files);
    if ($uploaded_file && !$uploaded_file->isValid()) {
        $error = elgg_get_friendly_upload_error($uploaded_file->getError());
        return elgg_error_response(elgg_echo($error));
    }

    if ($uploaded_file) {
        $allowed_mime_types = AgoraOptions::getAllowedImageFiles();
        $mime_type = elgg()->mimetype->getMimeType($uploaded_file->getPathname());
        if (!in_array($mime_type, $allowed_mime_types)) {
            return elgg_error_response(elgg_echo('agora:add:error:mime_type', [$mime_type]));
        }
    }
}

$uploaded_files_more = elgg_get_uploaded_files('product_icon');
if (is_array($uploaded_files_more) && count($uploaded_files_more) > 0) {
    foreach ($uploaded_files_more as $f) {
        if ($f && !$f->isValid()) {
            $error = elgg_get_friendly_upload_error($f->getError());
            return elgg_error_response(elgg_echo($error));
        }

        if ($f) {
            $allowed_mime_types = AgoraOptions::getAllowedImageFiles();
            $mime_type = elgg()->mimetype->getMimeType($f->getPathname());
            if (!in_array($mime_type, $allowed_mime_types)) {
                return elgg_error_response(elgg_echo('agora:add:error:mime_type', [$mime_type]));
            }
        }
    }
}

$new_flag = false;
if ($guid == 0) {
    $new_flag = true;
    $entity = new Agora;
    $entity->setSubtype(Agora::SUBTYPE);
    $entity->container_guid = $container_guid;
    $entity->owner_guid = elgg_get_logged_in_user_guid(); 

    // if no title on new upload, grab filename
    if (empty($title)) {
        $title = elgg_echo('agora:add:missing_title');
    }      
} else {
    $entity = get_entity($guid);
    if (!$entity->canEdit()) {
        return elgg_error_response(elgg_echo('agora:save:failed'));
    }
    if (!$title) {
        // user blanked title, but we need one
        $title = $entity->title;
    }    
}

$allow_digital_products = AgoraOptions::isDigitalProductsEnabled();
if ($allow_digital_products) { // check for file uploaded only if digital products are allowed
    if ($allow_digital_products == 'digitalonly') 	{	// if sell ONLY digital products $digital must be always on
        $digital = 1;
    }

    if ($digital) {	// if sell ONLY digital products, file is required
        if (!get_digital_filename($guid) && $_FILES["digital_file_box"]["error"] == 4) {
            return elgg_error_response(elgg_echo('agora:add:digital:fileismissing'));
        } 
        else if ($_FILES["digital_file_box"]["error"] != 4) {
            $digital_file_types = trim(elgg_get_plugin_setting('digital_file_types', 'agora'));
            $allowedExts = explode(",", $digital_file_types);
            array_walk($allowedExts, 'agora_trim_value');

            $temp = explode(".", $_FILES["digital_file_box"]["name"]);
            $extension = end($temp);
            if (!in_array($extension, $allowedExts))	 {
                return elgg_error_response(elgg_echo('agora:add:digital:invalidfiletype', [$digital_file_types]));
            } 
        }
    }
}

$entity->title = $title;
$entity->description = $desc;
$entity->access_id = $access_id;
$entity->price = $price;
$entity->howmany = $howmany;
$entity->location = $location;
$entity->digital = $digital;
$entity->tax_cost = $tax_cost;
$entity->shipping_cost = $shipping_cost;
$entity->shipping_type = $shipping_type;
$entity->currency = $currency;
$entity->category = $category;
$entity->tags = elgg_string_to_array($tags);
$entity->comments_on = $comments_on;

if ($entity->save()) {
    $entity->price_final = $entity->getFinalPrice();
    $entity->save();    // may be is not required this line
            
    if ($uploaded_file) {
        $entity->deleteIcon();
        $entity->saveIconFromUploadedFile('upload');
    } 

    // if we have new digital file, upload the file
    if ($digital && $_FILES["digital_file_box"]["error"] != 4) {
        $entity->deleteDigitalFiles();
        $prefixdocs = "agora/file-".$entity->guid;
        $filedocs = new AgoraFile();
        $mime_type_docs = elgg()->mimetype->getMimeType($_FILES['digital_file_box']['tmp_name']);

        $filedocs->owner_guid = $entity->owner_guid;
        $filedocs->container_guid = $entity->guid;
        $filedocs->originalfilename = $_FILES['digital_file_box']['name'];
        $filedocs->setMimeType($mime_type_docs);
        $filedocs->setFilename($prefixdocs . ".zip");
        $filedocs->access_id = ACCESS_PRIVATE;

        $filedocs->open("write");
        $filedocs->close();
        move_uploaded_file($_FILES['digital_file_box']['tmp_name'], $filedocs->getFilenameOnFilestore());
        $filedocs->save();
    } 

    // get image sizes
    $icon_sizes = elgg_get_config('agora_image_sizes');

    /////////////////////////////////////////////// more images
    if (is_array($uploaded_files_more) && count($uploaded_files_more) > 0) {
        $time = time();
        foreach ($uploaded_files_more as $key => $f) {
            $prefix = "agora/".$time.$key;

            //         // rotate photos if needed (iOS issue)
            //         $exif = exif_read_data($_FILES['product_icon']['tmp_name'][$key]);
            //         if (!empty($exif['Orientation'])) {
            //             $image = imagecreatefromstring(file_get_contents($_FILES['product_icon']['tmp_name'][$key]));
            //             switch ($exif['Orientation']) {
            //                 case 3:
            //                     $image = imagerotate($image, 180, 0);
            //                     break;

            //                 case 6:
            //                     $image = imagerotate($image, -90, 0);
            //                     break;

            //                 case 8:
            //                     $image = imagerotate($image, 90, 0);
            //                     break;
            //             }
            //             imagejpeg($image, $_FILES['product_icon']['tmp_name'][$key]);
            //         }     

            $fh = new AgoraImage();
            $fh->access_id = ACCESS_PUBLIC;
            $fh->owner_guid = elgg_get_site_entity()->guid;
            $fh->container_guid = $entity->guid;
            $fh->prefix_time = $time;
            $fh->prefix_key = $key;
            $fh->originalfilename = "ad_{$entity->guid}.jpg";
            $fh->setFilename($prefix.".jpg");

            $uploaded = false;
            $filestorename = $fh->getFilenameOnFilestore();
            try {
                $uploaded = $f->move(pathinfo($filestorename, PATHINFO_DIRNAME), pathinfo($filestorename, PATHINFO_BASENAME));
            } catch (FileException $ex) {
                return elgg_error_response(elgg_echo('agora:products:invalid:icon'));
            }

            $guid = 0;
            if ($uploaded) {
                $mime_type = $fh->getMimeType();
                $fh->setMimeType($mime_type);
                $fh->simpletype = elgg()->mimetype->getSimpleType($mime_type);
                // $fh->simpletype = elgg_get_file_simple_type($mime_type);
                $guid = $fh->save();
            }

            foreach ($icon_sizes as $name => $size_info) {
                try {
                    $fh->setFilename($prefix.$name.".jpg");
                    // touch file location in order to create the file
                    $fh->open('write');
                    $fh->close();
        
                    $resized = elgg_save_resized_image($filestorename, $fh->getFilenameOnFilestore(), [
                        'w' => $size_info['w'], 
                        'h' => $size_info['h'], 
                        'square' => $size_info['square'], 
                    ]);
                } catch (Exception $e) {
                    error_log($e->getMessage());
                    
                    if ($fh->exists()) {
                        $fh->delete();
                    }
                } 
            }

            $fh->file_prefix = $prefix;
        }

    }
    /////////////////////////////////////////////// more images

    elgg_clear_sticky_form('agora');

    //add to river only if new
    if ($new_flag) {
        elgg_create_river_item([
            'view' => 'river/object/agora/create',
            'action_type' => 'create',
            'subject_guid' => elgg_get_logged_in_user_guid(),
            'object_guid' => $entity->getGUID(),
        ]);
    }

    return elgg_ok_response('', elgg_echo('agora:save:success'), $entity->getURL());
} else {
    return elgg_error_response(elgg_echo('agora:save:failed'));
}
