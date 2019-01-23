<?php

namespace Delipress\WordPress\Services\Provider\SendinBlue;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use Delipress\WordPress\Models\InterfaceModel\SubscriberInterface;
use Delipress\WordPress\Models\SubscriberModel;
use Delipress\WordPress\Helpers\StatusHelper;
use Delipress\WordPress\Helpers\MetaProviderHelper;

class SendinBlueSubscriberV3 extends SubscriberModel implements SubscriberInterface {
    
    /**
     * @return int
     */
    public function getId(){
        // var_dump($this->object); die;
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

    public function getAttributes(){
        return $this->object->getAttributes();
    }

    /**
     * @return string
     */
    public function getFirstName(){
        $attributes = $this->getAttributes();
        if(isset($attributes["PRENOM"])){
            return $attributes["PRENOM"];
        }

        if($attributes["first_name"]){
            return $attributes["first_name"];
        }

        return "";
    }

    /**
     * @return string
     */
    public function getLastName(){
        $attributes = $this->getAttributes();
        if(isset($attributes["NOM"])){
            return $attributes["NOM"];
        }

        if($attributes["last_name"]){
            return $attributes["last_name"];
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
        return  date_i18n( get_option( 'date_format' ), strtotime( $this->object["modifiedAt"] ) ); 
    }

    
}
