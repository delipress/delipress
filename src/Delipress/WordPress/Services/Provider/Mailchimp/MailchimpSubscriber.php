<?php

namespace Delipress\WordPress\Services\Provider\Mailchimp;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use Delipress\WordPress\Models\InterfaceModel\SubscriberInterface;
use Delipress\WordPress\Models\SubscriberModel;

use Delipress\WordPress\Helpers\StatusHelper;
use Delipress\WordPress\Helpers\MetaProviderHelper;

class MailchimpSubscriber extends SubscriberModel implements SubscriberInterface {
    
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
        return $this->object["email_address"];
    }

    /**
     *
     * @param MetaModel $meta
     * @return any
     */
    public function getMetaValue($meta){
        foreach($this->object["merge_fields"] as $key => $metadata){
            if($key === $meta->getTag()){
                return $metadata;
            }
        }
    }

    /**
     *
     * @param boolean $str
     * @return string
     */
    public function getStatus($str = false){
        switch($this->object["status"]){
            case "subscribed":
            default:
                $key = StatusHelper::SUBSCRIBE;
                break;
            case "unsubscribed":
                $key = StatusHelper::UNSUBSCRIBE;
                break;
            case "pending":
                $key = StatusHelper::PENDING;
                break;
        }

        if(!$str){
            return $key;
        }

        return StatusHelper::getLabelByKey($key);
    }

    /**
     * @return string
     */
    public function getCreatedAt(){
        if(empty($this->object["timestamp_signup"])){
            return date_i18n( get_option( 'date_format' ), strtotime( $this->object["last_changed"] ) ); 
        }

        return date_i18n( get_option( 'date_format' ), strtotime( $this->object["timestamp_signup"] ) ); 
    }

    
}
