<?php

namespace Delipress\WordPress\Services\Provider\SendGrid;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );


class SendGridMeta {

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
        if(isset($this->object["id"])){
            return $this->object["id"];
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
