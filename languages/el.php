<?php
/**
 * Elgg Agora Classifieds plugin
 * @package Agora
 */

$lang = array(

    // menu items and titles
    'agora' => "Αγγελίες",
    'agora:menu' => "Αγγελίες",
    
    // basic options
    'agora:add' => "Ανέβασε νέα αγγελία",
    'agora:edit' => "Επεξεργασία αγγελίας",
    'agora:unknown_agora' => "Άγνωστη αγγελία",
    'item:object:agora' => "Αγγελίες",
    'item:object:' => "Αγγελίες",
    'agora:by' => "Αγγελία από",
    'agora:ad' => "Αγγελία",
    'agora:category' => "Κατηγορία",
    'agora:price' => "Τιμή",
    'agora:location' => "Location",
    'agora:howmany' => "Αριθμός διαθέσιμων",
    'agora:list:list' => "Προβολή λίστας",
    'agora:list:gallery' => "Προβολή εικονιδίων",
    'agora:categories:all' => "Όλες οι κατηγορίες",
    'agora:terms:title' => "Όρους χρήσης",
    'agora:terms:accept' => "Έχω διαβάσει και αποδέχομαι τους %s",
    'agora:terms:accept:error' => "Πρέπει να αποδεχτείτε τους όρους χρήσης!",
    'agora:send_message' => "Αποστολή μηνύματος",
    'agora:label:all' => "Όλες",
    'agora:label:owner' => "Οι αγγελίες μου",
    'agora:label:friends' => "Αγγελίες Φίλων",
    'agora:label:map' => "Χάρτης",    
        
    // Status messages
    'agora:none' => "Δεν υπάρχουν αγγελίες.",
    'agora:owner' => "Αγγελίες του %s",
    'agora:friends' => "Αγγελίες φίλων",
    'agora:save:success' => "Η αγγελία αποθηκεύτηκε με επιτυχία.",
    'agora:save:missing_title' => "Ο τίτλος της αγγελίας λείπει. Η αγγελία σας δεν μπορεί να αποθηκευτεί.",
    'agora:save:missing_category' => "Ad category is missing. Η αγγελία σας δεν μπορεί να αποθηκευτεί.",
    'agora:save:failed' => "Η αγγελία σας δεν μπορεί να αποθηκευτεί.",
    'agora:save:price_not_numeric' => "Η Τιμή δεν είναι είναι έγκυρος αριθμός. Η αγγελία σας δεν μπορεί να αποθηκευτεί.",
    'agora:delete:success' => "Η αγγελία σας διαγράφτηκε.",    
    'agora:delete:failed' => "Η αγγελίαςσας δεν μπορεί να διαγραφτεί.",   
    'agora:save:howmany_not_numeric' => "Ο αριθμός των διαθέσιμων δεν είναι έγκυρος αριθμός. Η αγγελία σας δεν μπορεί να αποθηκευτεί.",
    
    // add classfieds function
    'agora:add' => "Δημοσίευση Αγγελίας",
    'agora:add:title' => "Τίτλος",
    'agora:add:title:note' => "Καταχωρείστε τον τίτλο της αγγελίας.",
    'agora:add:category' => "Κατηγορία",
    'agora:add:category:note' => "Επιλέξτε την κατηγορία στην οποία ανήκει η αγγελία σας.",    
    'agora:add:category:select' => "Επιλέξτε κατηγορία", 
    'agora:add:description' => "Περιγραφή",
    'agora:add:description:note' => "Εισάγετε μια αναλυτική περιγραφή για την αγγελία σας.",
    'agora:add:tags' => "Ετικέτες",
    'agora:add:submit' => "Αποθήκευση",
    'agora:add:missingtitle' => "Ο τίτλος λείπει",
    'agora:add:cannotload' => "Αδύνατη η φόρτωση της αγγελίας",
    'agora:add:noaccess' => "Μη έγκυρη πρόσβαση",
    'agora:add:noaccessforpost' => "Μη έγκυρη πρόσβαση για δημοσίευση αγγελιών",
    'agora:add:requiredfields' => "Τα πεδία με αστερίσκο (*) είναι υποχρεωτικά",
    'agora:add:currency' => "Νόμισμα",
    'agora:add:currency:note' => "Επιλέξτε νόμισμα",
    'agora:add:price' => "Τιμή",
    'agora:add:pricesimple' => "Τιμή",
    'agora:add:price:note' => "Τιμή για πώληση μέσω paypal.<br />Εισάγετε 0 εάν είναι δωρεάν.",
    'agora:add:price:note:importantall' => "(για λήψη πληρωμών μέσω Paypal, η διεύθυνση email στις ρυθμίσεις σας πρέπει να είναι έγκυρος Λογαριασμός στο Paypal)",
    'agora:add:price:note:importantadmin' => "(Απαιτείται έγκυρος Λογαριασμός Paypal στις ρυθμίσεις: Administration - Settings - Agora Classifieds)",
    'agora:add:image' => "Ανεβάστε μία εικόνα για την αγγελία σας. Αφήστε κενό για καμία αλλαγή.",
    'agora:add:image:note' => "Ο τύπος αρχείου πρέπει να είναι JPG, GIF ή PNG.",
    'agora:add:image:fileerror' => "Μη έγκυρο αρχείο",
    'agora:add:image:fileerror1' => "Το μέγεθος του αρχείου που προσπαθείτε να ανεβάσετε είναι πολύ μεγάλο.",
    'agora:add:image:fileerror2' => "Το μέγεθος του αρχείου που προσπαθείτε να ανεβάσετε είναι πολύ μεγάλο.",
    'agora:add:image:fileerror3' => "Το αρχείο σας μεταφορτώθηκε μερικώς.",
    'agora:add:image:invalidfiletype' => "Μη έγκυρος τύπος αρχείου. Ο τύπος αρχείου πρέπει να είναι JPG, GIF ή PNG.",
    'agora:add:howmany' => "Αριθμός διαθέσιμων",
    'agora:add:howmany:note' => "Καθορίστε τον αριθμό των διαθέσιμων μονάδων προς πώληση για την αγγελία σας.<br />Αφήστε κενό για απεριόριστο αριθμό.",    
    'agora:add:location' => "Τοποθεσία",
    'agora:add:location:note' => "Καθορίστε την τοποθεσία για προβολή σε χάρτη.",     
    
    // river
    'river:create:object:agora' => '%s ανέβασε νέα αγγελία %s',
    'river:comment:object:agora' => '%s σχολίασε στην αγγελία %s',
    'agora:river:annotate' => 'ένα σχόλιο για την αγγελία',
    'agora:river:item' => 'an item',  
    
    // groups
    'agora:group' => 'Αγγελίες ομάδας',
    'agora:group:enableagora' => 'Ενεργοποίηση αγγελιών',
    
    // settings
    'agora:settings:defaultdateformat' => 'Default date format',
    'agora:settings:defaultdateformat:note' => 'Enter date format for displaying dates', 
    'agora:settings:defaultcurrency' => 'Default currency',
    'agora:settings:defaultcurrency:note' => 'Enter default currency',
    'agora:settings:uploaders' => 'Who can post classifieds ?',
    'agora:settings:uploaders:note' => 'Set permissions for posting classifieds',
    'agora:settings:uploaders:allmembers' => 'All Members',
    'agora:settings:uploaders:admins' => 'Administrators',
    'agora:settings:no' => "No",
    'agora:settings:yes' => "Yes",
    'agora:settings:sandbox:note' => "Select <strong>Yes</strong> ONLY for testing purpose using a valid sandbox account. ",
    'agora:settings:sandbox' => "Use Sandbox (test mode)",
    'agora:settings:categories' => 'Categories',
    'agora:settings:categories:note' => 'Set some predefined categories for posting to the Agora Classifieds. Seperate each category with commas like "Cars, Motocycles, Trucks etc".',
    'agora:settings:terms_of_use' => 'Terms of Use',
    'agora:settings:terms_of_use:note' => 'Determine the terms of use for members when creating/editing ad posts. If you do not determine terms of use, members will not be required to accept.',
    'agora:settings:send_message' => 'Enable private message button',
    'agora:settings:send_message:note' => 'Set if members can send private message to seller.', 
    'agora:settings:ads_geolocation' => 'Enable ad geolocation and map',
    'agora:settings:ads_geolocation:note' => 'Set yes for enable ad geolocation and map view.',  
    'agora:settings:ads_geolocation:notenabled' => 'Classifieds Geolocation is not enabled',
    'agora:settings:amap_maps_api_geocoder:notenabled' => 'Kanellga Maps Api is not enabled. Map of ads cannot be displayed',       
	'agora:settings:users_to_notify' => 'Users to Notify',
    'agora:settings:users_to_notify:note' => 'Set a list of users who will be notified for every transsaction. Use usernames and seperate them with comma.',
    'agora:settings:tabs:general_options' => 'General Options',
    'agora:settings:tabs:paypal_options' => 'Paypal Options',
    'agora:settings:tabs:map_options' => 'Map Options',
    'agora:settings:tabs:transactions_log' => 'Transactions Log',   
    'admin:settings:agora' => 'Agora Classifieds',
    'agora:settings:save:ok' => 'Settings were successfully saved',
    'agora:settings:transactions:none' => 'No transactions found',
    'agora:settings:transactions:buyer' => 'Buyer',
    'agora:settings:transactions:seller' => 'Seller',
    'agora:settings:transactions:date' => 'Paypal Transactions Date',    
    
    // widget
    'agora:widget' => "Οι αγγελίες μου",
    'agora:widget:viewall' => "Προβολή όλων",
    'agora:widget:num_display' => "Αριθμός αγγελιών για εμφάνιση",
    'agora:widget:num_display_items' => "Αριθμός αγγελιών για εμφάνιση",
    'agora:widget:description' => "",
    'agora:widget:items_bought' => "Πρόσφατες αγορές",
    'agora:widget:items_sold' => "Πρόσφατες πωλήσεις",    
    
    // paypal 
    'agora:buy' => "Αγορά",
    'agora:sales' => "Πωλήσεις για αυτή την αγγελία",
    'agora:transactionid' => "Transaction ID",
    'agora:messagetobuyer' => "Έχετε αγοράσει ήδη αυτό το προϊόν",
    'agora:paypal:buyeremail' => "Email του αγοραστή",
    'agora:paypal:country' => "Χώρα",
    'agora:paypal:firstname' => "Όνομα",
    'agora:paypal:lastname' => "Επώνυμο",
    'agora:paypal:mccurrency' => "Νόμισμα",
    'agora:paypal:mcgross' => "Ποσό",
    'agora:paypal:paymentdate' => "Ημερομηνία πληρωμής",
    'agora:paypal:paymentstatus' => "Κατάσταση πληρωμής",
    'agora:paypal:sellersubject' => "Επιβεβαιωμένη παραγγελία από ",
    'agora:paypal:buyersubject' => "Επιτυχής αγορά από %s",
    'agora:paypal:buyerbody' => "Για προβολή πατήστε στον παρακάτω σύνδεσμο",
    'agora:paypal:title' => "Αγγελία",
    'agora:buyerprofil' => "Προφίλ αγοραστή",
    'agora:ipn:title' => "IPN failed fraud checks",
    'agora:ipn:error1' => "Η πληρωμή δεν έχει ολοκληρωθεί",
    'agora:ipn:error2' => "mc_gross does not match: ",
    'agora:ipn:error3' => "mc_currency does not match: ",
    'agora:ipn:error4' => "Ο χρήστης αυτός έχει ήδη αγοράσει το προϊόν: ",
    'agora:ipn:error5' => "Purchace not possbile to be saved. Please contact with site administrator",
    'agora:ipn:error6' => "Αυτός ο αγοραστής δεν είναι εγγεγραμμένος χρήστης",
    'agora:ipn:error7' => "item_number not set",
    
    //search 
    'agora:search' => "Αναζήτηση αγγελιών",
    'agora:search:location' => "τοποθεσία",
    'agora:search:radius' => "απόσταση (μέτρα)",
    'agora:search:radius:meters' => "απόσταση (μέτρα)",
    'agora:search:radius:km' => "απόσταση (χιλιόμετρα)",
    'agora:search:radius:miles' => "απόσταση (μίλια)",    
    'agora:search:meters' => "μέτρα",
    'agora:search:km' => "χμ",
    'agora:search:miles' => "μίλια",    
    'agora:search:showradius' => "Εμφάνιση περιοχής αναζήτησης",
    'agora:search:submit' => "Αναζήτηση",
    'agora:searchnearby' => "Κοντινές αγγελίες",
    'agora:mylocationsis' => "Η τοποθεσία μου: ",
    'agora:searchbyname' => "Αναζήτηση αγγελιών με όνομα",
    'agora:search:name' => "όνομα προϊόντων",
    'agora:search:searchname' => "Αναζήτηση μελών για %s",
    'agora:search:usernotfound' => "Δεν βρέθηκαν προϊόντα κατά την αναζήτηση σας",
    'agora:search:usersfound' => "Προϊόντα που βρέθηκαν",
    'agora:search:around' => "Προϊόντα κοντινά στα προϊόντα που βρέθηκαν",  
    
);

add_translation("el", $lang);
