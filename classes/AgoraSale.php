<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

class AgoraSale extends ElggObject {
    const SUBTYPE = "agora_sale";
    
    protected $meta_defaults = array(
        "title"            => NULL,
        "description"      => NULL,
        "container_guid"   => NULL,     // agora guid
        "owner_guid"       => NULL,     // buyer guid
        "transaction_id"   => NULL,     // transaction_id from paypal
        "txn_method"       => NULL,     // sale method
    );    

    protected function initializeAttributes() {
        parent::initializeAttributes();

        $this->attributes["subtype"] = self::SUBTYPE;
    }
    
}