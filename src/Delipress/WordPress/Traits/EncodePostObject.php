<?php

namespace Delipress\WordPress\Traits;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

trait EncodePostObject {

    /**
     * 
     * @param objet $object
     * @return string
     */
    public function encodeData($object){
        return base64_encode(json_encode(array($object->ID, $object->post_name)));
    }

    /**
     * 
     * @param string $data
     * @return JSON
     */
    public function decodeData($data){
        return json_decode(base64_decode($data));
    }

}

