<?php

namespace Delipress\WordPress\Models;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use Delipress\WordPress\Models\InterfaceModel\SubscriberInterface;
use Delipress\WordPress\Models\OptinModel;
use Delipress\WordPress\Services\Table\TableServices;


class SubscriberModel implements SubscriberInterface  {

    public function __construct($object = null){
        $this->setObject($object);
    }

    public function setObject($object){
        $this->object = $object;
        return $this;
    }

    public function setMetaData($metadata){
        $this->metadata = $metadata;
        return $this;
    }


    public function getId(){
        if(property_exists($this, "id")){
            return $this->id;
        }

        return null;
    }

    public function setId($id){
        $this->id = $id;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getEmail(){
        if(property_exists($this, "email")){
            return $this->email;
        }

        return "";
    }

    public function setEmail($email){
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName(){
        return "";
    }

    /**
     * @return string
     */
    public function getLastName(){
        return "";
    }

    /**
     * @return bool
     */
    public function getIsSubscribe(){
        return false;
    }

    /**
     *
     * @param MetaModel $meta
     * @return string
     */
    public function getMetaValue($meta){
        return "";
    }

    /**
     *
     * @param boolean $str
     * @return null
     */
    public function getStatus($str = false){
        return null;
    }

}
