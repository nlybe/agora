<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

return [
    
    // menu items and titles
    'agora' => "Classifieds",
    'agora:menu' => "Classifieds",
    'item:object:agora' => "Classifieds",
    'collection:object:agora' => "Classifieds",
    'item:object:agora_sale' => 'Classifieds Sales',
    'item:object:agora_interest' => 'Classifieds Interest',
    'item:object:agora_img' => 'Classifieds Images',
    'agora:buyer' => "Buyer",
    'agora:seller' => "Seller",
    'agora:transaction:id' => "ID",
    'agora:transaction:date' => "Date",
    
    // errors / warnings
    'agora:error:access:invalid' => "Invalid access to this page",
    'agora:error:action:invalid' => "Invalid access for this action",
    'agora:error:offline:failed' => "Purchace not possbile to be saved. Please contact with site administrator",
    'agora:plugins:paypal_api:missing' => 'Paypal API plugin (paypal_api) is missing', 
    'agora:plugins:ratings:missing' => 'Ratings (ratings) plugin is missing', 
    'agora:error:invalid:entity' => 'Invalid entity',
    
    // basic options
    'agora:add' => "Post Ad",
    'agora:edit' => "Edit Ad",
    'agora:unknown_agora' => "Unknown ad",
    'agora:by' => "Ad by",
    'agora:ad' => "Ad",
    'agora:category' => "Category",
    'agora:price' => "Price",
    'agora:location' => "Location",
    'agora:howmany' => "No of available units",
    'agora:categories:all' => "All Categories",
    'agora:terms:title' => "Terms of use",
    'agora:terms:accept' => "I have read and accepted the %s",
    'agora:terms:accept:error' => "You must accept the terms of use!",
    'agora:send_message' => "Send private message",
    'agora:be_interested' => "I am interested",
    'agora:label:all' => "All",
    'agora:label:owner' => "Mine",
    'agora:label:friends' => "Friends",
    'agora:label:map' => "Classifieds Map",
    'agora:download:file' => "Download",
    'agora:download:filenotexists' => "File doesn't not exist",
    'agora:download:nopurchased' => "You haven't purchased this product. No valid access to this file.",
    'agora:download:nodigitalproduct' => "This product is not digital. No available file to download.",
    'agora:download:downloadable_file' => "Downloadable file",
    'agora:download:type' => "Type",
    'agora:object:tax_included' => " (Taxes included)",
    'agora:object:total_cost' => "Total Cost: %s",
    'agora:object:total_cost:simple' => "Total Cost",
    'agora:object:login_to_buy' => "Login to Buy",
    
    // Status messages
    'agora:none' => "No classifieds yet",
    'agora:owner' => "%s's classifieds",
    'agora:friends' => "Friends' classifieds",	
    'agora:save:success' => "Ad was successfully saved.",
    'agora:save:missing_title' => "Ad title is missing. Your ad cannot be saved.",
    'agora:save:missing_category' => "Ad category is missing. Your ad cannot be saved.",
    'agora:save:failed' => "Your ad cannot be saved.",
    'agora:save:price_not_numeric' => "Price is not valid, must be numeric. Your ad cannot be saved.",
    'agora:delete:success' => "Your ad was deleted.",    
    'agora:delete:failed' => "Your ad cannot be deleted.",   
    'agora:save:howmany_not_numeric' => "No of available units is not valid, must be numeric. Your ad cannot be saved.",
    'agora:save:tax_cost_not_numeric' => "Tax is not valid, must be numeric. Your ad cannot be saved.",
    'agora:save:shipping_cost_not_numeric' => "Cost of Shipping is not valid, must be numeric. Your ad cannot be saved.",
    'agora:be_interested:failed' => "Send interest failed",   
    'agora:be_interested:adtitle' => "Ad: <a href='%s'>%s</a>",
    'agora:be_interested:ad_message_subject' => "New interest for %s",
    'agora:be_interested:success' => "You successfully expressed interest for this ad. The owner of the ad will be notified",
    'agora:be_interested:error' => "Error on setting interest for this ad.",
    'agora:set_rejected:interest_guid_missing' => "Interest ID is missing. Reject process canceled.",
    'agora:set_rejected:interest_entity_missing' => "Interest entity is missing. Reject process canceled.",
    'agora:set_rejected:agora_entity_missing' => "Classified entity is missing. Reject process canceled.",
    'agora:set_rejected:success' => "You successfully rejected this interest.",
    'agora:set_rejected:failed' => "Reject process failed.",
    'agora:set_accepted:interest_guid_missing' => "Interest ID is missing. Accept process canceled.",
    'agora:set_accepted:interest_entity_missing' => "Interest entity is missing. Accept process canceled.",
    'agora:set_accepted:agora_entity_missing' => "Classified entity is missing. Accept process canceled.",
    'agora:set_accepted:user_entity_missing' => "User entity is missing. Accept process canceled.",
    'agora:set_accepted:success' => "You successfully accepted this interest.",
    'agora:set_accepted:failed' => "Accept process failed.",  
    'agora:icon:delete:success' => 'Image deleted sucessfully',
    'agora:icon:delete:failed' => 'Failure on deleting the image',
    'agora:product_icon:error:image:sizeMB' => "The file size of an image upload was too large - please limit up to 5MB",
    'agora:product_icon:error:image:size' => "An image upload was too large - please limit to 3264px width/height",
    'agora:products:invalid:icon:size' => "Invalid image size",
    
    // reviews and ratings
    'agora:comments' => "Reviews",  
    'agora:comments:post' => "Post review & rating",  
    'agora:comments:add:rate' => "Rate this product ",
    'agora:comments:add:comment' => "Write a review",
    'agora:comments:add:rating' => "Rate the seller: ",  
    'agora:comments:add:review' => "Write a review",  
    'agora:comments:rating:failure' => "An unexpected error occurred when adding your rating.",
    'agora:comments:review:failure' => "An unexpected error occurred when adding your review.",
    'agora:comments:notify_buyer:subject' => "Successful comment addition",  
    'agora:comments:rating:blank' => "Sorry, you need to rate this item before we can save it.",
    'agora:comments:review:blank' => "Sorry, you need to put something in your review before we can save it.",
	'agora:comments:notify_buyer:body' => "You have successfully added a new comment on item \"%s\" It reads:

	%s

	To reply or view the original item, click here:

	%s

	You cannot reply to this email.",    
	
	'agora:comments:notify:subject' => "Add review and rating for your purchase",  
	'agora:comments:notify:body' => "Dear buyer, you recently purchased the item %s. 
	
	Please take some time to rate the seller and add a review for this purchase by clicking on %s
	
	You cannot reply to this email.",    
	'agora:comments:stars_caption' => "%s/%s stars (%s votes)",  
	'generic_comments:latest' => "Latest reviews",
	
    
    // interest messages
    'agora:interests' => "Users interested",
    'agora:request:read_message' => "Read Message",
    'agora:request:user' => 'User',
    'agora:request:date' => 'Date',
    'agora:request:status' => 'Status',
    'agora:request:action' => 'Action',
    'agora:interest:accept' => "Accept",
    'agora:interest:reject' => "Reject",
    'agora:interest:accepted' => "accepted",
    'agora:interest:rejected' => "rejected",   
    'agora:interest:myinterest' => "You have sent interest for this ad at:", 
    'agora:interest:send' => "Send",
    
    // add classfieds function
    'agora:add:title' => "Title",
    'agora:add:title:note' => "Enter ad title",
    'agora:add:tags' => "Tags",
    'agora:add:tags:note' => "Enter some keywords which describe this ad",
    'agora:add:category' => "Category",
    'agora:add:category:note' => "Enter ad category",
    'agora:add:category:select' => "Select category", 
    'agora:add:category:sortby' => "Sort by", 
    'agora:add:category:newest' => "Newest first", 
    'agora:add:category:s_price_min' => "Price: lower first", 
    'agora:add:category:s_price_max' => "Price: higher first", 
    'agora:add:description' => "Description",
    'agora:add:description:note' => "Enter ad detailed description",
    'agora:add:tags' => "Tags",
    'agora:add:submit' => "Save",
    'agora:add:missingtitle' => "Title is missing",
    'agora:add:cannotload' => "Cannot load ad",
    'agora:add:noaccess' => "No valid access",
    'agora:add:noaccessforpost' => "No valid access for post classfieds",
    'agora:add:requiredfields' => "Fields with an asterisk (*) are required",
    'agora:add:currency' => "Currency",
    'agora:add:currency:note' => "Select currency",
    'agora:add:price' => "Price of Ad",
    'agora:add:pricesimple' => "Price",
    'agora:add:price:note' => "Price of Ad. Enter zero for free.",
    'agora:add:price:note:importantall' => "For receiving online payments, you have to enter at least one valid account for payment gateways in your <a href='%s'>settings</a>.",
    'agora:add:price:note:importantadmin' => "For receiving online payments, you have to enter at least one valid account for payment gateways in <a href='%s'>Administration</a> area.",
    'agora:add:image' => "Main Photo",
    'agora:add:image:note' => "File type must be JPG, GIF or PNG. Leave blank for no change.",
    'agora:add:image:fileerror' => "Invalid file",
    'agora:add:image:fileerror1' => "The file you are trying to upload is too big.",
    'agora:add:image:fileerror2' => "The file you are trying to upload is too big.",
    'agora:add:image:fileerror3' => "The file you are trying upload was only partially uploaded",
    'agora:add:image:invalidfiletype' => "Invalid file type. File type must be JPG, GIF or PNG.",
    'agora:add:howmany' => "No of available units",
    'agora:add:howmany:note' => "Set the number for available units. Leave blank for unlimited.", 
    'agora:add:location' => "Location",
    'agora:add:location:note' => "Set the location of this ad for map searches.", 
    'agora:add:digital' => "Digital Product",
    'agora:add:digital:note' => "Check this if your product is digital file for downloading. The allowed file types are: %s",
    'agora:add:digital:message' => "Downloadable file",
    'agora:add:digital:fileismissing' => "Digital product file is missing",
    'agora:add:digital:invalidfiletype' => "Invalid file type. The allowed file types are: %s",
    'agora:add:digital:alreadyuploaded' => "The file <strong>%s</strong> has already been uploaded. To replace it, select it below:",
    'agora:add:tax_cost' => "Tax",
    'agora:add:tax_cost:note' => "Enter a numeric value as percentage of tax which apply. Leave blank or zero for no taxes.",
    'agora:add:shipping_cost' => "Cost of Shipping",
    'agora:add:shipping_cost:note' => "Enter shipping cost. Leave blank or zero if not applied.",
    'agora:add:shipping_type' => "Type of Shipping",
    'agora:add:shipping_type:note' => "Select if the Shipping Cost should be indicated in percentage or a monetary amount (tax excluded).",
    'agora:add:total' => "Fixed",
    'agora:add:percentage' => "Percentage",
    'agora:add:images' => "Upload more images",
    'agora:add:images:note' => "Add more images or/and remove existing (maximum %s images)",
    'agora:add:images:another' => "Add another",
    'agora:add:images:last:delete' => "You cannot delete the last image",
    'agora:add:images:limit' => "You have reached the maximum number (%s) of images allowed",
    
    // river
    'river:object:agora:create' => '%s posted new ad %s',
    'river:object:agora:comment' => '%s commented on ad %s',
    'agora:river:annotate' => 'a comment on this ad',
    'agora:river:item' => 'an item',  
    
    // groups
    'agora:group' => 'Group classifieds',
    'groups:tool:agora' => 'Enable classifieds',
    
    // my purchases
    'agora:my_purchases' => 'My Purchases',
    'agora:label:my_purchases' => 'My Purchases',
    'agora:purchases:none' => 'No purchases yet',
    'agora:my_purchases:none' => 'No Purchases',
    'agora:requests:none' => 'No requests yet',
    'agora:sales:none' => 'No sales yet',
    
    // settings
    'admin:agora' => 'Plugin Settings: Classifieds',
    // 'admin:settings:agora' => 'Classifieds',
    'admin:agora:basic_options' => 'Basic Options',
    'admin:agora:paypal_options' => 'PayPal Options',
    'admin:agora:map_options' => 'Map Options',
    'admin:agora:ratings_options' => 'Ratings & Reviews',
    'admin:agora:digital_options' => 'Digital Products',
    'admin:agora:transactions_log' => 'Transactions Log',

    'agora:settings:defaultdateformat' => 'Default date format',
    'agora:settings:defaultdateformat:note' => 'Enter date format for displaying dates', 
    'agora:settings:default_currency' => 'Default currency',
    'agora:settings:default_currency:note' => 'Select default currency',
    'agora:settings:default_timezone' => 'Default timezone',
    'agora:settings:default_timezone:note' => 'Select default timezone',
    'agora:settings:agora_uploaders' => 'Who can post classifieds ?',
    'agora:settings:agora_uploaders:note' => 'Set permissions for posting classifieds',
    'agora:settings:agora_uploaders:allmembers' => 'All Members',
    'agora:settings:agora_uploaders:admins' => 'Administrators',
    'agora:settings:no' => "No",
    'agora:settings:yes' => "Yes",
    'agora:settings:sandbox:note' => "Select <strong>Yes</strong> ONLY for testing purpose using a valid sandbox account. ",
    'agora:settings:sandbox' => "Use Sandbox (test mode)",
    'agora:settings:categories' => 'Categories',
    'agora:settings:categories:note' => 'Set some predefined categories for posting to the Agora Classifieds. Seperate each category with commas like "Cars, Motocycles, Trucks etc".',
    'agora:settings:terms_of_use' => 'Terms of Use',
    'agora:settings:terms_of_use:note' => 'Determine the terms of use for members when creating/editing ad posts. If you do not determine terms of use, members will not be required to accept.',
    'agora:settings:send_message' => 'Enable offline purchases',
    'agora:settings:send_message:note' => 'Set if members can request interest for products and make transactions offline. Seller can accept or reject interest request. If accept, transaction is logged and product units are reduced. Message plugin is required.', 
    'agora:settings:multiple_ad_purchase' => 'Multiple purchase of same product',
    'agora:settings:multiple_ad_purchase:note' => 'Select Yes if members can purchase the same ad more than once times.', 
    'agora:settings:html_allowed' => 'Rich text editor & HTML tags',
    'agora:settings:html_allowed:note' => 'Select Yes if for use Rich text editor & HTML tags on ad description. If select No, simple text editor will be used and HTML tags will ne be allowed', 
    'agora:settings:buyers_comrat' => 'Enable reviews and ratings only for buyers',
    'agora:settings:buyers_comrat:note' => 'Select Yes to enable reviews and ratings only for buyers. If select no, the uploader can select if accept comments from all community members.',
    'agora:settings:buyers_comrat_expire' => 'Expiration days: ',
    'agora:settings:buyers_comrat_expire:note' => '  Enter expiration period for reviews and ratings in days. Value must be numeric. It affects ONLY IF reviews and ratings are enabled only for buyers', 
    'agora:settings:buyers_comrat_notify' => 'Notification days: ',
    'agora:settings:buyers_comrat_notify:note' => '  Enter notification time after purchase in days for review and rating. Value must be numeric. It affects ONLY IF reviews and ratings are enabled only for buyers', 
    'agora:settings:buyers_comrat_notify_by' => 'Send notification by: ',
    'agora:settings:buyers_comrat_notify_by:note' => '  Enter a username who is supposed to send the notifications. Normally it will be a site administrator.', 
    'agora:settings:ads_geolocation' => 'Enable Ad Geolocation and Classifieds Map',
    'agora:settings:ads_geolocation:note' => 'Select Yes for enable ad geolocation and map view.', 
    'agora:settings:ads_digital' => 'Enable option for selling digital products',
    'agora:settings:ads_digital:note' => '',
    'agora:settings:ads_digital:plus' => 'Sell both digital and non-digital products',
    'agora:settings:ads_digital:only' => 'Sell ONLY digital products',
    'agora:settings:digital:file_types' => 'Select allowed file type for digital products',
    'agora:settings:digital:file_types:note' => 'Set the allowed file type for digital products. Seperate each type with commas like <strong>pdf, PDF, zip, ZIP</strong> etc.',   
    'agora:settings:ads_geolocation:notenabled' => "Classifieds Geolocation is not enabled",
    'agora:settings:users_to_notify' => 'Users to Notify',
    'agora:settings:users_to_notify:note' => 'Set a list of users who will be notified for every transsaction. Use usernames and seperate them with comma.',
    'agora:settings:tabs:basic_options' => 'Basic Options',
    'agora:settings:save:ok' => 'Settings were successfully saved',
    'agora:settings:transactions:none' => 'No transactions found',
    'agora:settings:transactions:buyer' => 'Buyer',
    'agora:settings:transactions:seller' => 'Seller',
    'agora:settings:transactions:method' => 'Purchase method',
    'agora:settings:transactions:post' => 'Ad',
    'agora:settings:transactions:date' => 'Date of transaction',
    'agora:settings:markericon:agora_blue' => 'Blue',
    'agora:settings:markericon:ad_image' => 'Ad Image',
    'agora:settings:markericon' => 'Marker Icon',
    'agora:settings:markericon:note' => 'Select the color of marker for classifieds on map',
    'agora:settings:markericon:agora_royal_blue' => 'Blue Royal',
    'agora:settings:markericon:agora_forest_green' => 'Green',
    'agora:settings:markericon:agora_grey' => 'Grey',
    'agora:settings:markericon:agora_orange' => 'Orange',
    'agora:settings:markericon:agora_pink' => 'Pink',
    'agora:settings:markericon:agora_purple' => 'Purple',
    'agora:settings:markericon:agora_red' => 'Red',
    'agora:settings:markericon:agora_violet_red' => 'Red Violet',
    'agora:settings:markericon:agora_yellow' => 'Yellow',
    'agora:settings:agora_paypal_enabled:note' => "Select <strong>Yes</strong> if you want to enable Paypal as payment gateway. The Paypal API plugin is required.",
    'agora:settings:agora_paypal_enabled' => "Enable Paypal Gateway",    
    'agora:settings:max_images' => "Maximum number of images",  
    'agora:settings:max_images:note' => "Set maximum number of images for ad ",    
    'agora:settings:initial_load:title' => 'Initial map',
    'agora:settings:initial_load:note' => 'Select what to show on initial map',
    'agora:settings:initial_load:all' => 'All ads',
    'agora:settings:initial_load:newest' => 'Newest ads',
    'agora:settings:initial_load:mylocation' => 'User\'s location',
    'agora:settings:initial_load:newest_no' => 'No of newest ads',
    'agora:settings:initial_load:newest_no:note' => 'If <strong>Newest ads</strong> selected, enter the number of newest ads to display.',
    'agora:settings:initial_load:mylocation_radius' => 'Radius',
    'agora:settings:initial_load:mylocation_radius:note' => 'If <strong>User\'s location</strong> selected, enter the default radius for searching around user\'s location.',  
    'agora:settings:sidebar_list' => 'Display list of ads on sidebar',   
    'agora:settings:sidebar_list:note' => 'Select if you want to display list of ads in sidebar. The ads will be clickable displaying the info window if ad on map.',    
    
    // widget
    'agora:widget' => "Classifieds",
    'agora:widget:viewall' => "View all",
    'agora:widget:num_display' => "Number of posts to display",
    'agora:widget:num_display_items' => "Number of items to display",
    'agora:widget:description' => "Latest classifieds",
    'agora:widget:bought' => "Purchases",
    'agora:widget:bought:description' => "Latest purchases",
    'agora:widget:items_bought' => "Recent items bought",
//    'agora:widget:items_sold' => "Recent items sold",
    
    // paypal 
    'agora:buy' => "Buy this",
    'agora:sales' => "Sales of %s",
    'agora:requests' => "Requests for %s",
    'agora:sales:short' => "Sales",
    'agora:sales:short:note' => "Select to see sales of %s",
    'agora:requests' => "Requests",
    'agora:requests:note' => "Select to see requests of %s",
    'agora:transactionid' => "Transaction ID",
    'agora:messagetobuyer' => "You have already buy this unit",
    'agora:paypal:buyeremail' => "Email of buyer",
    'agora:paypal:country' => "Country",
    'agora:paypal:firstname' => "First name",
    'agora:paypal:lastname' => "Last name",
    'agora:paypal:mccurrency' => "Currency",
    'agora:paypal:mcgross' => "Amount",
    'agora:paypal:paymentdate' => "Date of payment",
    'agora:paypal:accepteddate' => "Date accepted",
    'agora:paypal:paymentstatus' => "Payment status",
    'agora:paypal:sellersubject' => "Verified Order by %s",
    'agora:paypal:buyersubject' => "Successful purchase of %s",
    'agora:paypal:buyerbody' => "For viewing this, please click on link below",
    'agora:paypal:title' => "Ad",
    'agora:buyerprofil' => "Buyer profil",
    'agora:ipn:title' => "IPN failed fraud checks",
    'agora:ipn:error1' => "Payment status not completed",
    'agora:ipn:error2' => "mc_gross does not match: ",
    'agora:ipn:error3' => "mc_currency does not match: ",
    'agora:ipn:error4' => "This user has already buy this ad: ",
    'agora:ipn:error6' => "This buyer is not registered user",
    'agora:ipn:error7' => "Item_number not set",
    'agora:ipn:error8' => "Item is not valid agora object",
    
    // paypal api
    'agora:sales:title' => "Sale of %s",
    'agora:sales:notification:here' => "here",
    'agora:sales:success' => "Transaction was successfully completed",
    'agora:sales:notification:buyer:subject' => "Successful purchase: %s",
    'agora:sales:notification:buyer:body' => "You completed transaction successfully. You can check the status of transaction at %s.",
    'agora:sales:notification:admin:subject' => "New: %s",
    'agora:sales:notification:admin:body' => "You can check the transaction at %s.",
    

    // map search 
    'agora:search' => "Search ads by location",
    'agora:search:location' => "location",
    'agora:search:radius' => "radius (meters)",
    'agora:search:radius:meters' => "radius (meters)",
    'agora:search:radius:km' => "radius (km)",
    'agora:search:radius:miles' => "radius (miles)",
    'agora:search:meters' => "meters",
    'agora:search:km' => "km",
    'agora:search:miles' => "miles",    
    'agora:search:showradius' => "Show search area",
    'agora:search:submit' => "Search",
    'agora:searchnearby' => "Search nearby ads",
    'agora:mylocationsis' => "My location is: ",
    'agora:searchbyname' => "Search ads by name",
    'agora:search:name' => "name",
    'agora:search:searchname' => "ads search for %s and nearby",
    'agora:search:usernotfound' => "Classifieds not found",
    'agora:search:usersfound' => "Classifieds found",
    'agora:search:around' => "Classifieds nearby on ads found", 
    'agora:groups:newest' => 'Map with %s newest ads',  
    'agora:groups:nearby:search' => 'Ads near "%s"',   
    'agora:search:price_min' => 'min price',  
    'agora:search:price_max' => 'max price',  
    'agora:search:keyword' => 'keyword',
    'agora:search:submit' => 'Search',

    // user settings
    'agora:usersettings:settings' => "Classifieds settings",
    'agora:usersettings:title' => "Personal Classifieds Settings",
    'agora:usersettings:error:user' => "Error, not such user",
    'agora:usersettings:no_settings' => "No classifieds settings available to configure",
    'agora:usersettings:paypal_settings' => "Paypal Settings",
    'agora:usersettings:paypal' => "Paypal account",
    'agora:usersettings:paypal:note' => "Specify the right Merchant ID or email address for your Paypal account. This account will be used in order to receive payments through Paypal gateway for ads you post.",

    'agora:usersettings:logo' => "",
    'agora:usersettings:logo:note' => "",
    'agora:usersettings:update:success' => "Your Classifieds Settings were successfully saved",
    'agora:usersettings:update:error' => "Error on saving Classifieds Settings",
    'agora:usersettings:no_fornormaluseryet' => "No settings to configure yet",    
    
    //////////////////////////////////
    'agora:add:error:mime_type' => '%s is not supported',
    
];
