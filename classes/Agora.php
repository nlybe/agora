<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

class Agora extends ElggObject {
    const SUBTYPE = "agora";
    
    protected $meta_defaults = [
        "title" 	=> NULL,
        "description" 	=> NULL,
        "price" 	=> NULL,
        "price_final"	=> NULL,        // required for searches
        "currency" 	=> NULL,
        "image" 	=> NULL,
        "category"   	=> NULL,
        "terms" 	=> NULL,
        "howmany" 	=> NULL,
        "location" 	=> NULL,
        "digital" 	=> NULL,
        "tax_cost" 	=> NULL,	// info about taxes, always percentage
        "shipping_cost" => NULL,	// cost of shipping if any
        "shipping_type" => NULL,	// type of shipping, amount or percentage
        "tags"          => NULL,
        "comments_on"	=> NULL,
    ];

    protected function initializeAttributes() {
        parent::initializeAttributes();

        $this->attributes["subtype"] = self::SUBTYPE;
    }
    
    /**
     * Get the price of ad depending on price and taxes
     * 
     * @return type
     */
    public function getPrice() {
        $price = $this->price;

        if ($this->tax_cost && is_numeric($this->tax_cost)) {
            $price = $price + $price*$this->tax_cost/100;
        }

        return $price;
    } 
    
    /**
     * Get the price of ad depending on price and taxes
     * 
     * @return type
     */
    public function getTaxCost() {
        $tax_cost = 0;

        if ($this->tax_cost && is_numeric($this->tax_cost)) {
            $tax_cost = $this->price*$this->tax_cost/100;
        }

        return $tax_cost;
    }     
    
    /**
     * Get the shipping cost applied
     * 
     * @return type
     */
    public function getShippingCost() {
        if ($this->shipping_cost && is_numeric($this->shipping_cost)) {
            if ($this->shipping_type == AGORA_SHIPPING_TYPE_TOTAL) {
                return $this->shipping_cost;
            }
            else if ($this->shipping_type == AGORA_SHIPPING_TYPE_PERCENTAGE) {
                return $this->getPrice()*$this->shipping_cost/100;
            }
        }
        
        return 0;
    } 
    
    /**
     * Get the price of ad depending on price, taxes and shipping
     * 
     * @return type
     */
    public function getFinalPrice() {
        $price = $this->getPrice();

        if ($this->shipping_cost && is_numeric($this->shipping_cost)) {
            $price = $price + $this->getShippingCost();
        }
		
        return $price;
    } 
    
    /**
     * Check if this ad is soldout
     * 
     * @return boolean: true is soldout, else false
     */
    public function isSoldOut() {
        if (is_numeric($this->howmany) && $this->howmany == 0) {
            return true;
        }
		
        return false;
    }     
    
    /**
     * Check if given user has purchased this ad
     * 
     * @param type $guid
     * @param type $user_guid
     * @return mixed. If count, true/false. If not count, array.
     */
    public function userPurchasedAd($user_guid, $count = false) {
        $options = [
            'type' => 'object',
            'subtype' => AgoraSale::SUBTYPE,
            'container_guid' => $this->getGUID(),
            'owner_guid' => $user_guid,
            'count' => $count,
        ];
        $entities = elgg_get_entities($options);
        
        if ($count && $entities > 0) {
            return true;
        }
        else if (!$count) {
            return $entities;
        }
        
        return false;
    }
    
    /**
     * Get list of sales
     * 
     * @param type $list
     * @return type
     */
    public function getSales($list = false) {
        if ($this->canEdit()) {
            $options = [
                'type' => 'object',
                'subtype' => AgoraSale::SUBTYPE,
                'limit' => 0,
            ];
        
            return elgg_call(ELGG_IGNORE_ACCESS, function() use ($options, $list) {                
                if ($list) {
                    $options['no_results'] =  elgg_echo('agora:sales:none');
                    return elgg_list_entities($options);
                }
                else {
                    return elgg_get_entities($options);
                }
            });
        }
        
        return false;
    }
    
    /**
     * Get list of requests
     * 
     * @param type $list
     * @return type
     */
    public function getRequests($list = false) {
        if ($this->canEdit()) { 
            $options = [
                'type' => 'object',
                'subtype' => AgoraInterest::SUBTYPE,
                'limit' => 0,
                'metadata_name_value_pairs' => [
                    ['name' => 'int_ad_guid', 'value' =>  $this->getGUID(), 'operand' => '='],
                ],
            ];

            return elgg_call(ELGG_IGNORE_ACCESS, function() use ($options, $list) {
                if ($list) {
                    $options['no_results'] =  elgg_echo('agora:requests:none');
                    $options['full_view'] =  true;
                    return elgg_list_entities($options);
                }        
                return elgg_get_entities($options);
            });
        }

        return false;
    }
    
    /**
     * Get URL of ad images
     * 
     * @param type $size
     * @return boolean
     */
    public function getImageUrl($size = 'medium') {
        // Get the size
        $size = elgg_strtolower($size);
        if (!in_array($size, ['master', 'large', 'medium', 'small', 'tiny'])) {
            $size = 'medium';
        }
        $image_url = "agora/image/$this->guid/$size/" . time() . ".jpg";

        return elgg_normalize_url($image_url);
    }
    
    /**
     * Get entity images
     * 
     * @param int $max_images
     * @return array
     */
    public function getMoreImages($max_images = 0) {
        return elgg_get_entities([
            'type' => 'object',
            'subtype' => AgoraImage::SUBTYPE,
            'container_guid' => $this->guid,
            'limit' => $max_images,
            $options['order_by_metadata'] = [
                'name' => 'time_created',
                'direction' => 'ASC',
            ]
        ]);
    }   
    
    /**
     * Reduce available items, if is set
     * 
     * @param type $size
     * @return boolean
     */
    public function reduceItems() {
        if (is_numeric($this->howmany) && $this->howmany>0) {
            $this->howmany--;
            $this->save();
            
            return true;
        }
        
        return false;
    }     

    /**
     * Can a user comment on this company post?
     *
     * @see ElggObject::canComment()
     *
     * @param int  $user_guid User guid (default is logged in user)
     * @param bool $default   Default permission
     *
     * @return bool
     */
    public function canComment($user_guid = 0, $default = null):bool {        
        if ($this->comments_on === 'Off') {
            return false;
        }

        return true;
    }
    
    /**
     * Get entity digital files
     * 
     * @return array
     */
    public function getDigitalFiles() {
        return elgg_get_entities([
            'type' => 'object',
            'subtype' => AgoraFile::SUBTYPE,
            'container_guid' => $this->guid,
            'limit' => 0,
        ]);
    } 
    
    /**
     * Get entity digital files
     * 
     * @return array
     */
    public function deleteDigitalFiles() {
        if ($this->canEdit()) { 
            $files = $this->getDigitalFiles();

            if ($files) {
                foreach ($files as $f) {
                    $f->delete();
                }
            }
        }
    }  
}
