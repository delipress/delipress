<?php

namespace Delipress\WordPress\Services\Provider\SendinBlue;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );


class SendinBlueMeta {

    /**
     *
     * @param array|null $object
     */
    public function __construct($object = null){
        $this->object = $object;
    }

    /**
     * @return int
     */
    public function getId(){
        if(isset($this->object["name"])){
            return $this->object["name"];
        }

        return uniqid();
    }   

    /**
     * @return string
     */
    public function getTitle(){
        return $this->object["name"];
    }

    /**
     * @return string
     */
    public function getTag(){
        return $this->object["name"];
    }

    /**
     * @return string
     */
    public function getDataType(){
        if(isset($this->object["datatype"])){
            return $this->object["datatype"];
        }

        return $this->object["type"];
    }

    /**
     * @return string
     */
    public function getNamespace(){
        return "static";
    }

}
