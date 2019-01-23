<?php

namespace Delipress\WordPress\Services\Provider\SendGrid;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use Delipress\WordPress\Models\InterfaceModel\SubscriberInterface;
use Delipress\WordPress\Models\SubscriberModel;
use Delipress\WordPress\Helpers\StatusHelper;
use Delipress\WordPress\Helpers\MetaProviderHelper;
use Delipress\WordPress\Helpers\SubscriberMetaHelper;

class SendGridSubscriber extends SubscriberModel implements SubscriberInterface {
    
    /**
     * @return int
     */
    public function getId(){
        return $this->object["id"];
    }

    /**
     * @return string
     */
    public function getEmail(){
        return $this->object["email"];
    }

    /**
     * @return string
     */
    public function getName(){

        return $this->getFirstName() . " " . $this->getLastName();
    }

    /**
     *
     * @param MetaModel $meta
     * @return any
     */
    public function getMetaValue($meta){

        switch($meta->getTag()){
            case SubscriberMetaHelper::WORDPRESS_META_FIRST_NAME:
                return $this->object["first_name"];
            case SubscriberMetaHelper::WORDPRESS_META_LAST_NAME:
                return $this->object["last_name"];
        }
        
        foreach($this->object["custom_fields"] as $metadata){
            if($metadata["name"] == $meta->getTag()){
                return $metadata["value"];
            }
        }
    }

    /**
     *
     * @param boolean $str
     * @return string
     */
    public function getStatus($str = false){
        if($str){
            return StatusHelper::getLabelByKey(StatusHelper::SUBSCRIBE);
        }

        return StatusHelper::SUBSCRIBE;
    }

    /**
     * @return null|string
     */
    public function getCreatedAt(){
        return  date_i18n( get_option( 'date_format' ), strtotime( $this->object["updated_at"] ) ); 
    }

    
}
