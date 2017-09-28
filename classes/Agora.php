<?php
/**
 * Elgg Agora Classifieds plugin
 * @package Agora
 */

class Agora extends ElggObject {
    const SUBTYPE = "agora";
    
    protected $meta_defaults = array(
        "title" 		=> NULL,
        "description" 	=> NULL,
        "price" 		=> NULL,
        "price_final"	=> NULL,
        "currency" 		=> NULL,
        "image" 		=> NULL,
        "category"   	=> NULL,
        "terms" 		=> NULL,
        "howmany" 		=> NULL,
        "location" 		=> NULL,
        "digital" 		=> NULL,
        "tax_cost" 		=> NULL,	// info about taxes, always percentage
        "shipping_cost" => NULL,	// cost of shipping if any
        "shipping_type" => NULL,	// type of shipping, amount or percentage
        "tags"          => NULL,
        "comments_on"	=> NULL,
    );    

    protected function initializeAttributes() {
        parent::initializeAttributes();

        $this->attributes["subtype"] = self::SUBTYPE;
    }
    
    // get the price of ad depending on price and taxes
    public function get_ad_price() {
		$price = $this->price;
		
		if ($this->tax_cost && is_numeric($this->tax_cost)) 
			$price = $price + $price*$this->tax_cost/100;

        return $price;
    } 
    
    // get the price of ad depending on price and taxes
    public function get_ad_tax_cost() {
		$tax_cost = 0;
		
		if ($this->tax_cost && is_numeric($this->tax_cost)) 
			$tax_cost = $this->price*$this->tax_cost/100;

        return $tax_cost;
    }     
    
    // get the shipping cost applied
    public function get_ad_shipping_cost() {
		if ($this->shipping_cost && is_numeric($this->shipping_cost)) {
			if ($this->shipping_type == AGORA_SHIPPING_TYPE_TOTAL) 
				return $this->shipping_cost;
			else if ($this->shipping_type == AGORA_SHIPPING_TYPE_PERCENTAGE) 
				return $this->get_ad_price()*$this->shipping_cost/100;
		}
        
        return 0;
    } 
    
    // get the price of ad depending on price, taxes and shipping
    public function get_ad_price_with_shipping_cost() {
		$price = $this->get_ad_price();

		if ($this->shipping_cost && is_numeric($this->shipping_cost)) {
			$price = $price + $this->get_ad_shipping_cost();
		}
		
        return $price;
    }    
}
