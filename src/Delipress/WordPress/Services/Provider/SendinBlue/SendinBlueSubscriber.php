<?php

namespace Delipress\WordPress\Services\Provider\SendinBlue;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use Delipress\WordPress\Models\InterfaceModel\SubscriberInterface;
use Delipress\WordPress\Models\SubscriberModel;
use Delipress\WordPress\Helpers\StatusHelper;
use Delipress\WordPress\Helpers\MetaProviderHelper;

class SendinBlueSubscriber extends SubscriberModel implements SubscriberInterface {
    
    /**
     * @return int
     */
    public function getId(){
        return $this->object["email"];
    }

    /**
     * @return string
     */
    public function getEmail(){
        return $this->object["email"];
    }


    public function getAttributes(){
        return $this->object["attributes"];
    }

    /**
     * @return string
     */
    public function getMetaValue($meta){

        $attributes = $this->getAttributes();
        foreach($attributes as $key => $value){
            if($key == $meta->getTag()){
                return $value;
            }
        }

        return "";
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
     *
     * @return null|string
     */
    public function getCreatedAt(){
        return  date_i18n( get_option( 'date_format' ), strtotime( $this->object["last_modified"] ) ); 
    }

    
}
