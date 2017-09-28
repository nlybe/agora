<?php
/**
 * Elgg Agora Classifieds plugin
 * @package Agora
 */

class Sales extends ElggObject {
    const SUBTYPE = "agorasales";
    
    protected $meta_defaults = array(
        "txn_vguid" 		=> NULL,
        "txn_buyer_guid"   	=> NULL,
        "txn_date" 		=> NULL,    //date of transaction
        "txn_id" 		=> NULL,    //id of transaction
        "txn_method" 		=> NULL,    // method of purchase such as paypal, offline etc
    );    

    protected function initializeAttributes() {
        parent::initializeAttributes();

        $this->attributes["subtype"] = self::SUBTYPE;
    }
}