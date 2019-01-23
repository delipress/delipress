<?php

namespace Delipress\WordPress\Helpers;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

/**
 *
 * @author Delipress
 * @version 1.0.0
 * @since 1.0.0
 */
abstract class DataEncoder{

    /**
     * 
     * @param array $data
     * @return string
     */
    public static function encodeData($data){
        return rtrim(
            base64_encode(
                json_encode(
                    $data
                )
            )
        );

    }

    /**
     * 
     * @param string $data
     * @return array
     */
    public static function decodeData($data){
        $data = json_decode(
            base64_decode($data), true
        );
        
        if(!is_array($data)) {
            return array();
        }

        return $data;
    }
}









