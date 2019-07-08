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
        "txn_method"       => NULL,     // sale methodbuyer_name
        "buyer_name"       => NULL,     // buyer name
        "bill_number"      => NULL,     // bill_number on this format DDMMYY0000X
        "bill_analysis"    => NULL,     // bill analysis due to tax, discount, fee etc
        "payable_amount"   => NULL,     // amount payed
        "payable_amount_f" => NULL,     // amount payed formatted
    );    

    protected function initializeAttributes() {
        parent::initializeAttributes();

        $this->attributes["subtype"] = self::SUBTYPE;
    }
    
    /**
     * Return buyer user entity
     * 
     * @return type
     */
    public function getBuyer() {
        $buyer = get_user($this->owner_guid);
        if ($buyer instanceof \ElggUser) {
            return $buyer;
        }
        
        return false;
    }    
    
    /**
     * Return a new invoice number depending on previous number of current day
     * 
     * @return string
     */
    Public Static function getNewInvoiceNumber() {
        $q = date("dmy");
        error_log($q);
        $options = [
            'type' => 'object',
            'subtype' => AgoraSale::SUBTYPE,
            'metadata_name_value_pairs' => array(
                array('name' => 'bill_number', 'value' => $q.'%', 'operand' => 'like'),
            ),
            'limit' => 1,
        ];
        $entities = elgg_get_entities($options);
        
        if ($entities) {
            $last_invoice_no = (int) substr($entities[0]->bill_number, -5);
            $last_invoice_no++;
            $new_invoice_no = $q.AgoraOptions::addLeadingZero($last_invoice_no);
        }
        else {
            $new_invoice_no = $q.AgoraOptions::addLeadingZero(1);
        }      
        
        return $new_invoice_no;
    }
    
  
}