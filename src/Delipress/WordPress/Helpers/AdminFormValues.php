<?php

namespace Delipress\WordPress\Helpers;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

/**
 *
 * @author Delipress
 * @version 1.0.0
 * @since 1.0.0
 */
abstract class AdminFormValues{

    protected static $transient = array();

    const FORM_VALUES   = "_delipress_form_values";

    /**
     *
     * @param string $key
     * @param string $value
     */
    public static function register($key, $value){

        if ( false === ( $transient = get_transient( self::FORM_VALUES ) ) ) {
            $transient = array();
            $transient[$key] = $value;
            set_transient(self::FORM_VALUES, $transient);
        }
        else{
            if(!array_key_exists($key, $transient)){
                $transient[$key] = $value;
                set_transient(self::FORM_VALUES, $transient);
            }
        }

    }

    public static function getFormValues(){
        if(!empty(self::$transient)){
            return self::$transient;
        }


        $transient = get_transient( self::FORM_VALUES );

        if ( false ===  $transient  ){
            return array();
        }

        self::$transient = $transient;

        self::cleanFormValues();

        return self::$transient;

    }

    /**
     *
     * @param string $postKey
     * @param string $editValue
     * @return string
     */
    public static function displayOldValues($postKey, $editValue){
        $old_values = self::getFormValues();
        return self::displayOldValuesInArray($old_values, $postKey, $editValue);
    }

    /**
     *
     * @param array $old_values
     * @param string $postKey
     * @param any $editValue
     * @return string
     */
    public static function displayOldValuesInArray($old_values, $postKey, $editValue){
        return (array_key_exists($postKey, $old_values)) ? esc_attr($old_values[$postKey]) : esc_attr($editValue);
    }
    
    public static function cleanFormValues(){
        delete_transient(self::FORM_VALUES);
    }
}
