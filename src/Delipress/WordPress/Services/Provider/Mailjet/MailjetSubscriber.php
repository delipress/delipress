<?php

namespace Delipress\WordPress\Services\Provider\Mailjet;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use Delipress\WordPress\Models\InterfaceModel\SubscriberInterface;
use Delipress\WordPress\Models\SubscriberModel;
use Delipress\WordPress\Helpers\StatusHelper;
use Delipress\WordPress\Helpers\MetaProviderHelper;

class MailjetSubscriber extends SubscriberModel implements SubscriberInterface {
    
    /**
     * @return int
     */
    public function getId(){
        if(isset($this->object["Contact"])){
            return $this->object["Contact"]["ID"];
        }

        return $this->object["ID"];
    }

    /**
     * @return string
     */
    public function getEmail(){
        if(isset($this->object["Contact"])){
            if(is_array($this->object["Contact"]["Email"])){
                return current($this->object["Contact"]["Email"]);
            }
        }
        
        return $this->object["Email"];
    }

    /**
     * @return string
     */
    public function getName(){
        if(isset($this->object["Contact"])){
            return $this->object["Contact"]["Name"];
        }

        return $this->object["Name"];
    }

    /**
     *
     * @param MetaModel $meta
     * @return any
     */
    public function getMetaValue($meta){
        foreach($this->metadata as $metadata){
            if($metadata["Name"] === $meta->getTag()){
                return $metadata["Value"];
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
            if(!$this->object["IsUnsubscribed"]){
                return StatusHelper::getLabelByKey(StatusHelper::SUBSCRIBE);
            }
            else{
                return StatusHelper::getLabelByKey(StatusHelper::UNSUBSCRIBE);
            }
        }

        return ($this->object["IsUnsubscribed"]) ? StatusHelper::UNSUBSCRIBE : StatusHelper::SUBSCRIBE;
    }

    /**
     *
     * @return null|string
     */
    public function getCreatedAt(){
        
        if(isset($this->object["Contact"])){
            if(isset($this->object["Contact"]["LastUpdateAt"])){
                return  date_i18n( get_option( 'date_format' ), strtotime( $this->object["Contact"]["LastUpdateAt"] ) ); 
            }
        }

        if(!isset($this->object["CreatedAt"])){
            return null;
        }

        return  date_i18n( get_option( 'date_format' ), strtotime( $this->object["CreatedAt"] ) ); 
    }

    
}
