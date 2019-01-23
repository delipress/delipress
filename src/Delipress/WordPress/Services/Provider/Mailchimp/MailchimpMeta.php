<?php

namespace Delipress\WordPress\Services\Provider\Mailchimp;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );


class MailchimpMeta {

    /**
     * @param array|null $object
     */
    public function __construct($object = null){
        $this->object = $object;
    }

    /**
     * @return string
     */
    public function getId(){
        return $this->object["tag"];
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
        return $this->object["tag"];
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
