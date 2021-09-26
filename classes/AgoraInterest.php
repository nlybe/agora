<?php
/**
 * Elgg Agora Classifieds plugin
 * @package Agora
 */

class AgoraInterest extends ElggObject {
    const SUBTYPE = "agora_interest";
    
    protected $meta_defaults = [
        "int_ad_guid" 		=> NULL,
        "int_buyer_guid"   	=> NULL,
        "int_status"            => NULL,    // interest status (interested, accepted, rejected)
        "int_message_guid" 	=> NULL,    // message guid
    ];

    protected function initializeAttributes() {
        parent::initializeAttributes();

        $this->attributes["subtype"] = self::SUBTYPE;
    }
    
    /**
     * Get the ad entity
     * 
     * @return type
     */
    public function getAd() {
        $ad = get_entity($this->int_ad_guid);

        if ($ad instanceof Agora) { 
            return $ad;
        }

        return false;
    }    
}
