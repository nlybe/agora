<?php
/**
 * Elgg Agora Classifieds plugin
 * @package Agora
 * Traduccido por Javier @Inside the hotel
 */

$lang = array(

    // menu items and titles	
    'agora' => "Experiencias",
    'agora:menu' => "Experiencias",
    	
    // basic options	
    'agora:add' => "Publicar nuevo",
    'agora:edit' => "Editar",
    'agora:unknown_agora' => "Desconocido",
    'item:object:agora' => "Experiencias",
    'item:object:' => "Experiencias",
    'agora:by' => "Experiencia de",
    'agora:ad' => "Experiencia",
    'agora:category' => "Categoría",
    'agora:price' => "Precio",
    'agora:location' => "Localización",
    'agora:howmany' => "No de unidades disponibles",
    'agora:notfoundtext' => "Registro no encontrado o ha sido eliminado",
    'agora:list:list' => "Ver lista",
    'agora:list:gallery' => "Vista de la galería",
    'agora:categories:all' => "Todas las categorías",
    'agora:terms:title' => "Términos de uso",
    'agora:terms:accept' => "He leído y aceptado el %s",
    'agora:terms:accept:error' => "Usted debe aceptar los términos de uso",
    'agora:send_message' => "Enviar mensaje privado",
    'agora:be_interested' => "Me interesa",
    'agora:label:all' => "Todos",
    'agora:label:owner' => "Mio",
    'agora:label:friends' => "Amigos",
    'agora:label:map' => "Mapa Experiencias",
    'agora:download:file' => "Download",
    'agora:download:filenotexists' => "File doesn't not exist",
    'agora:download:nopurchased' => "You haven't purchased this product. No valid access to this file.",
    'agora:download:nodigitalproduct' => "This product is not digital. No available file to download.",
    'agora:download:downloadable_file' => "Downloadable file",
    'agora:download:type' => "Type",
    'agora:object:tax_included' => " (Taxes included)",
    'agora:object:total_cost' => "Total Cost",
    'agora:object:login_to_buy' => "Login to Buy",
    	
    // Status messages	
    'agora:none' => "No hay experiencias.",
    'agora:owner' => "Experiencias de %s ",
    'agora:friends' => "Experiencias de los amigos",
    'agora:save:success' => "Guardado correctamente .",
    'agora:save:missing_title' => "El título del anuncio no ha sido especificado. Su anuncio no puede ser salvado.",
    'agora:save:missing_category' => "La categoría del anuncio no ha sido especificada. Su anuncio no puede ser salvado.",
    'agora:save:failed' => "Su anuncio no puede ser salvado.",
    'agora:save:price_not_numeric' => "El precio no es válido, debe ser numérico . Su anuncio no puede ser salvado.",
    'agora:delete:success' => "Se suprimió su anuncio.",
    'agora:delete:failed' => "Su anuncio no se puede eliminar.",
    'agora:save:howmany_not_numeric' => "Número de unidades disponibles no es válido, debe ser numérico . Su anuncio no puede ser salvado.",
    'agora:save:tax_cost_not_numeric' => "Tax is not valid, must be numeric. Your ad cannot be saved.",
    'agora:save:shipping_cost_not_numeric' => "Cost of Shipping is not valid, must be numeric. Your ad cannot be saved.",
    'agora:be_interested:failed' => "No se pudo enviar interés",
    'agora:be_interested:adtitle' => "Ad : <a href = '%s'",
    'agora:be_interested:ad_message_subject' => "New interest for %s",
    'agora:be_interested:success' => "Usted expresó con éxito interés por este anuncio",
    'agora:be_interested:success_message' => "El dueño del anuncio será notificado",
    'agora:be_interested:error' => "Error en la configuración de interés por este anuncio.",
    'agora:set_rejected:interest_guid_missing' => "Identificación de intereses no se encuentra. Proceso cancelado.",
    'agora:set_rejected:interest_entity_missing' => "Entidad de interés no se encuentra. Proceso cancelado.",
    'agora:set_rejected:agora_entity_missing' => "Entidad anuncio no se encuentra. Proceso cancelado.",
    'agora:set_rejected:success' => "Habéis rechazado con éxito este interés.",
    'agora:set_rejected:failed' => "El proceso de rechazo ha fallado.",
    'agora:set_rejected:novalidaccess' => "No es válido el acceso.",
    'agora:set_accepted:interest_guid_missing' => "Identificación de intereses no se encuentra. Proceso cancelado.",
    'agora:set_accepted:interest_entity_missing' => "Entidad de interés no se encuentra. Proceso cancelado.",
    'agora:set_accepted:agora_entity_missing' => "Entidad anuncio no se encuentra. Proceso cancelado.",
    'agora:set_accepted:user_entity_missing' => "Entidad de usuario no se encuentra. Proceso cancelado.",
    'agora:set_accepted:success' => "Usted aceptó con éxito este interés.",
    'agora:set_accepted:failed' => "Proceso de aceptación fallido.",
    'agora:set_accepted:novalidaccess' => "No es válido el acceso .",
    'agora:save:payulatam:minimum_ammount' => "The minimum ammount allowed is COP $ 5,659.00. Please change the value entered.",  
    	
    // interest messages	
    'agora:interests' => "Usuarios interesados​​",
    'agora:interest:read_message' => "Leer mensajes",
    'agora:interest:accept' => "Aceptar",
    'agora:interest:reject' => "Rechazar",
    'agora:interest:accepted' => "aceptado",
    'agora:interest:rejected' => "rechazado",
    'agora:interest:myinterest' => "Has enviado interés por este anuncio en:",
    	
    // add classfieds function	
    'agora:add' => "Añadir experiencia",
    'agora:add:title' => "Título",
    'agora:add:title:note' => "Introducir título.",
    'agora:add:category' => "Categoría",
    'agora:add:category:note' => "Categoría.",
    'agora:add:category:select' => "Seleccionar la categoría",
    'agora:add:description' => "Descripción",
    'agora:add:description:note' => "Descripción detallada.",
    'agora:add:tags' => "Etiquetas",
    'agora:add:submit' => "Guardar",
    'agora:add:missingtitle' => "Especifique el título",
    'agora:add:cannotload' => "No se puede cargar experiencias",
    'agora:add:noaccess' => "Sin acceso válido",
    'agora:add:noaccessforpost' => "No hay acceso válido para publicar experiencias",
    'agora:add:requiredfields' => "Los campos con un asterisco (* ) son necesarios",
    'agora:add:currency' => "Moneda",
    'agora:add:price' => "Precio de la experiencia",
    'agora:add:pricesimple' => "Precio",
    'agora:add:price:note' => "Precio del anuncio para la compra a través de PayPal.<Br />Introduzca 0 si es sin coste",
    'agora:add:price:note:importantall' => "(para recibir pagos a través de PayPal, su dirección de correo electrónico en la configuración debe ser una cuenta de PayPal válida )",
    'agora:add:price:note:importantadmin' => "(Se requiere cuenta PayPal en Administración - Configuración - Experiencias Agora )",
    'agora:add:image' => "Cargar imagen. Dejar en blanco si no quiere cambiarla.",
    'agora:add:image:note' => "Tipo de archivo debe ser JPG , GIF o PNG.",
    'agora:add:image:fileerror' => "Archivo no válido",
    'agora:add:image:fileerror1' => "El archivo que está intentando subir es demasiado grande.",
    'agora:add:image:fileerror2' => "El archivo que está intentando subir es demasiado grande.",
    'agora:add:image:fileerror3' => "El archivo que está intentando subir sólo fue subido parcialmente",
    'agora:add:image:invalidfiletype' => "Tipo de archivo no válido . Tipo de archivo debe ser JPG , GIF o PNG.",
    'agora:add:howmany' => "Número de unidades disponibles",
    'agora:add:howmany:note' => "Establecer el número de unidades disponibles. < Br />Dejar en blanco para unidades ilimitadas.",
    'agora:add:location' => "Localización",
    'agora:add:location:note' => "Establecer la ubicación de este anuncio mapa búsquedas.",
    'agora:add:digital' => "Digital Product",
    'agora:add:digital:note' => "Check this if your product is digital file for downloading. The allowed file types are: %s",
    'agora:add:digital:message' => "Downloadable file",
    'agora:add:digital:fileismissing' => "Digital product file is missing",
    'agora:add:digital:invalidfiletype' => "Invalid file type. The allowed file types are: %s",
    'agora:add:digital:alreadyuploaded' => "The file <strong>%s</strong> has already been uploaded. To replace it, select it below:",
    'agora:add:tax_cost' => "Tax",
    'agora:add:tax_cost:note' => "Enter percentage of tax which apply. Leave blank or zero for no taxes.",
    'agora:add:shipping_cost' => "Cost of Shipping",
    'agora:add:shipping_cost:note' => "Enter shipping cost. Leave blank or zero if not applied.",
    'agora:add:shipping_type' => "Type of Shipping",
    'agora:add:shipping_type:note' => "Select if the Shipping Cost should be indicated in percentage or a monetary amount (tax excluded).",
    'agora:add:total' => "Fixed",
    'agora:add:percentage' => "Percentage",
    	
    // river	
    'river:create:object:agora' => '%s ha publicado anuncio %s',
    'river:comment:object:agora' => '%s comentado ad %s',
    'agora:river:annotate' => 'un comentario para este anuncio',
    'agora:river:item' => 'un elemento',
    	
    // groups	
    'agora:group' => 'Experiencias ',
    'agora:group:enableagora' => 'Habilitar experiencias',
    	
    // settings	
    'agora:settings:defaultdateformat' => 'Formato de fecha por defecto',
    'agora:settings:defaultdateformat:note' => 'Introduzca el formato de fecha para la visualización de fechas',
    'agora:settings:defaultcurrency' => 'Moneda por defecto',
    'agora:settings:defaultcurrency:note' => 'Enter moneda por defecto',
    'agora:settings:uploaders' => '¿Quién puede publicar experiencias?',
    'agora:settings:uploaders:note' => 'Establecer permisos para publicar experiencias',
    'agora:settings:uploaders:allmembers' => 'Todos los Miembros',
    'agora:settings:uploaders:admins' => 'Administradores',
    'agora:settings:no' => "No",
    'agora:settings:yes' => "Sí",
    'agora:settings:sandbox:note' => "Select <strong>Sí<strong/> ONLY for testing purpose using a valid sandbox account.",
    'agora:settings:sandbox' => "Usar Sandbox (modo de prueba)",
    'agora:settings:paypal_account' => 'Paypal: identificación del proveedor o dirección de correo electrónico',
    'agora:settings:paypal_account:note' =>	'Introduzca la identificación del comerciante derecha o la dirección de correo electrónico de su cuenta Paypal. Esta cuenta se utiliza para recibir los pagos de Paypal <strong> en caso de que solo Administradores puedan publicar experiencias</strong>. Otherwise if all members can post experiences, then their personal email account (in settings) will be used as paypal account.',
    'agora:settings:categories' => 'Categorías',
    'agora:settings:categories:note' => 'Establecer algunas categorías predefinidas para publicar en las experiencias de Agora . Separe cada categoría con comas , como "Autos, Motos, Camiones, etc."',
    'agora:settings:terms_of_use' => 'Condiciones de uso',
    'agora:settings:terms_of_use:note' => "Determinar las condiciones de uso de los miembros al crear / editar los mensajes de publicidad. Si no determina los términos de uso, no se requiere que los miembros de aceptar.',
    'agora:settings:send_message' => 'Habilitar compras offline',
    'agora:settings:send_message:note' => 'Establecer si los usuarios pueden solicitar el interés por los productos y realizar transacciones en línea. El vendedor puede aceptar o rechazar la solicitud de interés. Si acepta, la transacción se registra y unidades de producto se reducen.',
	'agora:settings:multiple_ad_purchase' => 'Multiple purchase of same product',
    'agora:settings:multiple_ad_purchase:note' => 'Select Yes if members can purchase the same ad more than once times.', 
    'agora:settings:ads_geolocation' => 'Habilitar Geolocalización y mapa de experiencias',
	'agora:settings:ads_geolocation:note' => 'Set yes for enable ad geolocation and map view.', 
	'agora:settings:ads_digital' => 'Enable option for selling digital products',
    'agora:settings:ads_digital:note' => '',
    'agora:settings:ads_digital:plus' => 'Sell both digital and non-digital products',
    'agora:settings:ads_digital:only' => 'Sell ONLY digital products',
    'agora:settings:digital:file_types' => 'Select allowed file type for digital products',
    'agora:settings:digital:file_types:note' => 'Set the allowed file type for digital products. Seperate each type with commas like <strong>pdf, PDF, zip, ZIP</strong> etc.',   
    'agora:settings:ads_geolocation:notenabled' => 'Experiences Geolocation is not enabled',
	'agora:settings:amap_maps_api_geocoder:notenabled' => 'Kanellga Maps Api is not enabled. Map of ads cannot be displayed', 
	'agora:settings:users_to_notify' => 'Usuarios que notificar',
    'agora:settings:users_to_notify:note' => 'Establecer una lista de usuarios que van a ser notificados por cada transaccción . Utilice nombres de usuario y separarlos con comas.',
    'agora:settings:tabs:general_options' => 'Opciones generales',
    'agora:settings:tabs:paypal_options' => 'Opciones de PayPal',
    'agora:settings:tabs:map_options' => 'Opciones de mapa',
    'agora:settings:tabs:digital_options' => 'Digital Products Options',
    'agora:settings:tabs:transactions_log' => 'Registro de Transacciones',
     'agora:settings:tabs:payulatam_options' => 'PayU Latam Options',
    'admin:settings:agora' => 'Agora, experiencias',
    'agora:settings:save:ok' => 'Configuración guardada correctamente",
    'agora:settings:transactions:none' => 'No se encontraron transacciones',
    'agora:settings:transactions:buyer' => 'Comprador',
    'agora:settings:transactions:seller' => 'Vendedor',
    'agora:settings:transactions:method' => 'Purchase method',
    'agora:settings:transactions:date' => 'Fecha transacciones PayPal',
    'agora:settings:markericon:agora_blue' => 'Blue',
    'agora:settings:markericon:ad_image' => 'Añadir imagen',
    'agora:settings:markericon' => 'Icono Marcador',
    'agora:settings:markericon:note' => 'Seleccionar el color del marcador de experiencias en el mapa',
    'agora:settings:markericon:agora_royal_blue' => 'Blue Royal',
    'agora:settings:markericon:agora_forest_green' => 'Verde',
    'agora:settings:markericon:agora_grey' => 'Grey',
    'agora:settings:markericon:agora_orange' => 'Orange',
    'agora:settings:markericon:agora_pink' => 'Pink',
    'agora:settings:markericon:agora_purple' => 'Purple',
    'agora:settings:markericon:agora_red' => 'Red',
    'agora:settings:markericon:agora_violet_red' => 'Red Violet',
    'agora:settings:markericon:agora_yellow' => 'Yellow',
    'agora:settings:paypal_enabled:note' => "Select <strong>Yes</strong> if you want to enable Paypal as payment gateway. ",
    'agora:settings:paypal_enabled' => "Enable Paypal Gateway",    
    'agora:settings:payulatam_enabled:note' => "Select <strong>Yes</strong> if you want to enable PayU Latam as payment gateway. ",
    'agora:settings:payulatam_enabled' => "Enable PayU Latam Gateway",      
    	
    // widget	
    'agora:widget' => "Experiencias",
    'agora:widget:viewall' => "Ver todas",
    'agora:widget:num_display' => "Número de mensajes a visualizar",
    'agora:widget:num_display_items' => "Número de elementos a mostrar",
    'agora:widget:description' => "",
    'agora:widget:boughtandsold' => "Experiencias compradas y vendidas",
    'agora:widget:items_bought' => "Compras recientes",
    'agora:widget:items_sold' => "Experiencias recientes",
    	
    // paypal 	
    'agora:buy' => "Comprar",
    'agora:sales' => "Ventas de este anuncio",
    'agora:transactionid' => "ID de transacción",
    'agora:messagetobuyer' => "Usted ya ha comprado esto",
    'agora:paypal:buyeremail' => "Correo electrónico del comprador",
    'agora:paypal:country' => "País",
    'agora:paypal:firstname' => "Nombre",
    'agora:paypal:lastname' => "Apellidos",
    'agora:paypal:mccurrency' => "Moneda",
    'agora:paypal:mcgross' => "Cantidad",
    'agora:paypal:paymentdate' => "Fecha de Pago",
    'agora:paypal:paymentstatus' => "Estado del pago",
    'agora:paypal:sellersubject' => "Orden verificada por",
    'agora:paypal:buyersubject' => "El éxito de la compra - %s",
    'agora:paypal:buyerbody' => "Para ver esto, por favor haga clic en el enlace de abajo",
    'agora:paypal:title' => "Experiencia",
    'agora:buyerprofil' => "Perfil comprador",
    'agora:ipn:title' => "Chequeo de fraude IPN fallido",
    'agora:ipn:error1' => "Estado de pago no completado",
    'agora:ipn:error2' => "mc_gross no coincide :",
    'agora:ipn:error3' => "mc_currency no coincide : ",
    'agora:ipn:error4' => "Este usuario ya ha comprado esta experiencia: ",
    'agora:ipn:error5' => "La compra no se ha podido guardar. Por favor, póngase en contacto con el administrador del sitio",
    'agora:ipn:error6' => "Este comprador no es usuario registrado",
    'agora:ipn:error7' => "item_number no ajustada",
    'agora:ipn:error8' => "Item is not valid agora object",
    
    // PayULatam settings
    'agora:payulatam:name' => 'Name',
    'agora:settings:payulatam_merchantId' => 'PayU Latam: Merchant ID',
    'agora:settings:payulatam_merchantId:note' => 'Enter Merchant ID PayU Latam. This account will be used to receive payments in PayU Latam.',
    'agora:settings:payulatam_accountId' => 'PayU Latam: Account ID',
    'agora:settings:payulatam_accountId:note' => 'Enter Account ID PayU Latam. This account will be used to receive payments in PayU Latam.',
    'agora:settings:payulatam_apikey' => 'PayU Latam: Api Key',
    'agora:settings:payulatam_apikey:note' => 'Enter Api Key PayU Latam. You can find your api key according instructions at <a href="http://docs.payulatam.com/manual-integracion-web-checkout/informacion-adicional/" target="_blank">http://docs.payulatam.com/manual-integracion-web-checkout/informacion-adicional/</a>.',    
    'agora:settings:payulatam_testmode' => 'Use PayU Latam test mode',
    'agora:settings:payulatam_testmode:note' => 'Select <strong>Yes</strong> ONLY for testing purpose using PayU Latam test mode. For more details read at <a href="http://docs.payulatam.com/en/web-checkout-integration/how-to-test-transactions/" target="_blank">http://docs.payulatam.com/en/web-checkout-integration/how-to-test-transactions/</a>',
    'agora:payulatam:english' => 'English', 	// en
    'agora:payulatam:spanish' => 'Spanish',		// es
    'agora:payulatam:portugues' => 'Portugues',	// pt
	'agora:settings:payulatam_lang' => 'PayU Latam: Language',
    'agora:settings:payulatam_lang:note' => 'Select default language for PayU Latam.',
    	
    // map search 	
    'agora:search' => "Buscar anuncios por ubicación",
    'agora:search:location' => "ubicación",
    'agora:search:radius' => "Radio (metros)",
    'agora:search:radius:meters' => "Radio (metros)",
    'agora:search:radius:km' => "radio (km)",
    'agora:search:radius:miles' => "radio (millas)",
    'agora:search:meters' => "metros",
    'agora:search:km' => "km",
    'agora:search:miles' => "millas",
    'agora:search:showradius' => "Mostrar área de búsqueda",
    'agora:search:submit' => "Buscar",
    'agora:searchnearby' => "Buscar anuncios cercanas",
    'agora:mylocationsis' => "Mi ubicación es: ",
    'agora:searchbyname' => "Buscar anuncios por nombre",
    'agora:search:name' => "nombre",
    'agora:search:searchname' => "Búsqueda de anuncios para %s y cercanos",
    'agora:search:usernotfound' => "Experiencias no encontradas",
    'agora:search:usersfound' => "Experiencias encontradas",
    'agora:search:around' => "Experiencias cercanas no encontradas",
  
	// user settings
	'agora:usersettings:settings' => "Classifieds Settings",
	'agora:usersettings:title' => "Personal Classifieds Settings",
	'agora:usersettings:error:user' => "Error, not such user",
	'agora:usersettings:no_settings' => "No classifieds settings available to configure",
	'agora:usersettings:paypal_settings' => "Paypal Settings",
	'agora:usersettings:paypal' => "Paypal account",
	'agora:usersettings:paypal:note' => "Specify the right Merchant ID or email address for your Paypal account. This account will be used to receive payments through Paypal gateway.",
	'agora:usersettings:payulatam_settings' => "PayU Latam Settings",
	
	'agora:usersettings:payulatam_merchantId' => 'PayU Latam: Merchant ID',
    'agora:usersettings:payulatam_merchantId:note' => 'Enter Merchant ID PayU Latam. This Merchant ID will be used to receive payments through PayU Latam gateway.',
    'agora:usersettings:payulatam_accountId' => 'PayU Latam: Account ID',
    'agora:usersettings:payulatam_accountId:note' => 'Enter Account ID PayU Latam. This Account ID will be used to receive payments in PayU Latam gateway.',
    'agora:usersettings:payulatam_apikey' => 'PayU Latam: Api Key',
    'agora:usersettings:payulatam_apikey:note' => 'Enter Api Key PayU Latam. You can find your api key according instructions at <a href="http://docs.payulatam.com/manual-integracion-web-checkout/informacion-adicional/" target="_blank">http://docs.payulatam.com/manual-integracion-web-checkout/informacion-adicional/</a>.',    
    'agora:usersettings:payulatam_lang' => 'PayU Latam: Language',
    'agora:usersettings:payulatam_lang:note' => 'Select default language for PayU Latam.', 
    'agora:usersettings:payulatam_testmode' => 'Use PayU Latam test mode',
     
	'agora:usersettings:logo' => "",
	'agora:usersettings:logo:note' => "",
	'agora:usersettings:update:success' => "Your Classifieds Settings were successfully saved",
	'agora:usersettings:update:error' => "Error on saving Classifieds Settings",
	'agora:usersettings:no_fornormaluseryet' => "No settings to configure yet",  
    
);

add_translation("es", $lang);