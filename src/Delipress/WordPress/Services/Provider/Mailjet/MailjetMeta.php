<?php

namespace Delipress\WordPress\Services\Provider\Mailjet;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );


class MailjetMeta {

    /**
     * @return array|null
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

        return $this->object["ID"];
    }   

    /**
     * @return string
     */
    public function getTitle(){
        if(isset($this->object["name"])){
            return $this->object["name"];
        }

        return $this->object["Name"];
    }

    /**
     * @return string
     */
    public function getTag(){
        
        if(isset($this->object["name"])){
            return $this->object["name"];
        }

        return $this->object["Name"];
    }

    /**
     * @return string
     */
    public function getDataType(){
        if(isset($this->object["datatype"])){
            return $this->object["datatype"];
        }

        return $this->object["Datatype"];
    }

    /**
     * @return string
     */
    public function getNamespace(){
        if(isset($this->object["namespace"])){
            return $this->object["namespace"];
        }

        return $this->object["Namespace"];
    }

}
