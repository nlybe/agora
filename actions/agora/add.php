<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

elgg_load_library('elgg:agora');
if (elgg_is_active_plugin("amap_maps_api")){
    elgg_load_library('elgg:amap_maps_api'); 
} 

// check if user can post classifieds
if (!AgoraOptions::canUserPostClassifieds()) { 
    register_error(elgg_echo('agora:add:noaccessforpost'));  
    forward(REFERER);     
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
    register_error(elgg_echo('agora:save:missing_title'));
    forward(REFERER);
}
/*    
if (!$category) {
    register_error(elgg_echo('agora:save:missing_category'));
    forward(REFERER);
}    
*/
if ($price && !is_numeric($price)) {
    register_error(elgg_echo('agora:save:price_not_numeric'));
    forward(REFERER);
}  

if ($howmany && !is_numeric($howmany)) {
    register_error(elgg_echo('agora:save:howmany_not_numeric'));
    forward(REFERER);
}  

if ($tax_cost && !is_numeric($tax_cost)) {
    register_error(elgg_echo('agora:save:tax_cost_not_numeric'));
    forward(REFERER);
}  

if ($shipping_cost && !is_numeric($shipping_cost)) {
    register_error(elgg_echo('agora:save:shipping_cost_not_numeric'));
    forward(REFERER);
}  

// upload uploaded
$uploaded_files = elgg_get_uploaded_files('upload');
if ($uploaded_files) {
    $uploaded_file = array_shift($uploaded_files);
    if (!$uploaded_file->isValid()) {
        $error = elgg_get_friendly_upload_error($uploaded_file->getError());
        register_error($error);
        forward(REFERER);
    }

    $allowed_mime_types = AgoraOptions::getAllowedImageFiles();
    $mime_type = ElggFile::detectMimeType($uploaded_file->getPathname(), $uploaded_file->getClientMimeType());
    if (!in_array($mime_type, $allowed_mime_types)) {
        register_error(elgg_echo('agora:add:error:mime_type', [$mime_type]));
        forward(REFERER);
    }
}

/////////////////////////////////////////////
// get image sizes
$icon_sizes = elgg_get_config('agora_image_sizes');

// validate images OBS
//$existing_images = elgg_get_entities(array(
//    'type' => 'object',
//    'subtype' => AgoraImage::SUBTYPE,
//    'owner_guid' => $guid,
//    'limit' => 0,
//    'order_by' => 'e.time_created ASC'
//));

$file_keys = array();
if ($_FILES['product_icon']['tmp_name']) {

    $file_keys = array_keys($_FILES['product_icon']['tmp_name']);
    foreach ($_FILES['product_icon']['tmp_name'] as $key => $tmp_name) {
        $size = getimagesize($_FILES['product_icon']['tmp_name'][$key]);

        // check for image errors
        if (!substr_count($_FILES['product_icon']['type'][$key], 'image/') || $_FILES['product_icon']['error'][$key]) {
            if (($k = array_search($key, $file_keys)) !== false) {
                    unset($file_keys[$key]);
            }

        } 
        elseif (filesize($_FILES['product_icon']['tmp_name'][$key]) > 5120000) { // file size exceed 5MB
            unset($file_keys[$key]);
            system_message(elgg_echo('product_icon:error:image:sizeMB'));

        } 
        elseif (!$size || $size[0] > 3264 || $size[1] > 3264) {   // obs } elseif (!$size || $size[0] > 2048 || $size[1] > 1536) {
            if (($k = array_search($key, $file_keys)) !== false) {
                unset($file_keys[$key]);
                system_message(elgg_echo('product_icon:error:image:size'));
            }
        }
    }
}
/////////////////////////////////////////////

$new_flag = false;
if ($guid == 0) {
    $new_flag = true;
    $entity = new Agora;
    $entity->subtype = Agora::SUBTYPE;
    $entity->container_guid = $container_guid;
    $entity->owner_guid = elgg_get_logged_in_user_guid(); 

    // if no title on new upload, grab filename
    if (empty($title)) {
        $title = elgg_echo('agora:add:missing_title');
    }      
} else {
    $entity = get_entity($guid);
    if (!$entity->canEdit()) {
        system_message(elgg_echo('agora:save:failed'));
        forward(REFERRER);
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
            register_error(elgg_echo('agora:add:digital:fileismissing'));  
            forward(REFERER); 
        } 
        else if ($_FILES["digital_file_box"]["error"] != 4) {
            $digital_file_types = trim(elgg_get_plugin_setting('digital_file_types', 'agora'));
            $allowedExts = explode(",", $digital_file_types);
            array_walk($allowedExts, 'agora_trim_value');

            $temp = explode(".", $_FILES["digital_file_box"]["name"]);
            $extension = end($temp);
            if (in_array($extension, $allowedExts))	 {
            }
            else
            {
                register_error(elgg_echo('agora:add:digital:invalidfiletype', array($digital_file_types)));  
                forward(REFERER); 
            } 
        }
    }
}

$tagarray = string_to_tag_array($tags);
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
$entity->tags = $tagarray;
$entity->comments_on = $comments_on;

if ($entity->save()) {
    $entity->price_final = $entity->getFinalPrice();
    $entity->save();    // may be is not required this line
            
    if ($uploaded_file) {
        $entity->deleteIcon();
        $entity->saveIconFromUploadedFile('upload');
    } 

    // save ad coords location and if amap_maps_api is enabled
    if (elgg_is_active_plugin("amap_maps_api") && $location){
        amap_ma_save_object_coords($location, $entity, 'amap_maps_api');
    }

            // if we have new digital file, upload the file
    if ($digital && $_FILES["digital_file_box"]["error"] != 4) {	
        $prefixdocs = "agora/file-".$entity->guid;
        $filedocs = new ElggFile();
        $mime_type_docs = $filedocs->detectMimeType($_FILES['digital_file_box']['tmp_name'], $_FILES['digital_file_box']['type']);

        $filedocs->owner_guid = $entity->owner_guid;
        $filedocs->container_guid = $entity->container_guid;
        $filedocs->agora_guid = $entity->guid;
        $filedocs->originalfilename = $_FILES['digital_file_box']['name'];
        $filedocs->setMimeType($mime_type_docs);
        $filedocs->setFilename($prefixdocs . ".zip");
        $filedocs->access_id = 0;  // private, so they are not visibled in file plugin if enabled
        //$filedocs->simpletype = file_get_simple_type($mime_type_docs); // mallon obsolete, deixnei na douleuei ka xoris to file plugin

        $filedocs->open("write");
        //$filedocs->write(get_uploaded_file('digital_file_box'));
        $filedocs->close();
        move_uploaded_file($_FILES['digital_file_box']['tmp_name'], $filedocs->getFilenameOnFilestore());
        $filedocs->save();
    } 

    /////////////////////////////////////////////// more images
    if ($file_keys) {
        $time = time();
        $invalid = 0;
        foreach ($file_keys as $key) {
            $prefix = "agora/" . $time . $key;

            // rotate photos if needed (iOS issue)
            $exif = exif_read_data($_FILES['product_icon']['tmp_name'][$key]);
            if (!empty($exif['Orientation'])) {
                $image = imagecreatefromstring(file_get_contents($_FILES['product_icon']['tmp_name'][$key]));
                switch ($exif['Orientation']) {
                    case 3:
                        $image = imagerotate($image, 180, 0);
                        break;

                    case 6:
                        $image = imagerotate($image, -90, 0);
                        break;

                    case 8:
                        $image = imagerotate($image, 90, 0);
                        break;
                }
                imagejpeg($image, $_FILES['product_icon']['tmp_name'][$key]);
            }     

            $img_orig = get_resized_image_from_existing_file($_FILES['product_icon']['tmp_name'][$key], 2048, 1536, false);
            $filehandler = new AgoraImage();
            $filehandler->access_id = ACCESS_PUBLIC;
            $filehandler->owner_guid = elgg_get_logged_in_user_guid();
            $filehandler->container_guid = $entity->guid;
            $filehandler->prefix_time = $time;
            $filehandler->prefix_key = $key;
            $filehandler->setFilename($prefix . ".jpg");
            $filehandler->open("write");
            $filehandler->write($img_orig);
            $filehandler->close();
            $filehandler->save();

            foreach ($icon_sizes as $name => $size_info) {
                $resized = get_resized_image_from_existing_file($filehandler->getFilenameOnFilestore(),$size_info['w'], $size_info['h'], $size_info['square']);
                if ($resized) {
                    $file = new ElggFile();
                    $file->owner_guid = elgg_get_logged_in_user_guid();
                    $file->setMimeType('image/jpeg');
                    $file->setFilename($prefix.$name.".jpg");
                    $file->open("write");
                    $file->write($resized);
                    $file->close();					
                } 
            }

            $filehandler->file_prefix = $prefix;

            if ($invalid) {
                system_message(elgg_echo('products:invalid:icon:size', array($invalid)));
            }
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

    system_message(elgg_echo('agora:save:success'));
    forward($entity->getURL());
} else {
    register_error(elgg_echo('agora:save:failed'));
    forward("agora");
}
