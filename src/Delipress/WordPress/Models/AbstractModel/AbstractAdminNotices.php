<?php

namespace Delipress\WordPress\Models\AbstractModel;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

/**
 *
 * @author Delipress
 * @version 1.0.0
 * @since 1.0.0
 */
abstract class AbstractAdminNotices{



    protected static $transient = array();

    /**
     * 
     * @param string $type
     * @param string $key
     * @param string $message
     * @param array $params
     */
    protected static function register($type, $key, $message, $params){

        if ( false === ( $transient = get_transient( $type ) ) ) {
            $transient = array();
            $transient[$key]["message"] = $message;
            foreach($params as $keyParam => $param){
                $transient[$key][$keyParam] = $param;
            }
            set_transient($type, $transient);
        }
        else{
            if(!array_key_exists($key, $transient)){
                $transient[$key]["message"] = $message;
                foreach($params as $keyParam => $param){
                    $transient[$key][$keyParam] = $param;
                }
                set_transient($type, $transient);
            }
        }

    }

    /**
     * 
     * @param string $key
     * @param string $message
     * @param array $params
     * @return void
     */
    public static function registerError($key, $message, $params = array()){
        self::register(static::ERROR_NOTICES, $key, $message, $params);      
    }

    /**
     * 
     * @param string $key
     * @param string $message
     * @param array $params
     * @return void
     */
    public static function registerSuccess($key, $message, $params = array()){
        self::register(static::SUCCESS_NOTICES, $key, $message, $params);
    }

    /**
     * 
     * @param string $key
     * @param string $message
     * @param array $params
     * @return void
     */
    public static function registerInfo($key, $message, $params = array()){
        self::register(static::INFO_NOTICE, $key, $message, $params);
    }

    protected static function getNotices($type){
        if(array_key_exists($type, self::$transient)){
            return self::$transient[$type];
        }

        $transient = get_transient( $type );
        if ( false ===  $transient  ){
            return array();
        }

        self::$transient[$type] = $transient;
        
        return self::$transient[$type];

    }

    /**
     * @return array
     */
    public static function getErrorNotices(){
        return self::getNotices(static::ERROR_NOTICES);
    }

    /**
     * @return array
     */
    public static function getSuccessNotices(){
        return self::getNotices(static::SUCCESS_NOTICES );
    }

    /**
     * @return array
     */
    public static function getInfoNotices(){
        return self::getNotices(static::INFO_NOTICES );
    }

    /**
     * @return array
     */
    public static function deleteErrorNotices(){
        return delete_transient(static::ERROR_NOTICES);
    }

    /**
     * @return array
     */
    public static function deleteSuccessNotices(){
        return delete_transient(static::SUCCESS_NOTICES);
    }

    /**
     * @return array
     */
    public static function deleteInfoNotices(){
        return delete_transient(static::INFO_NOTICES);
    }

    /**
     * 
     * @param string $const
     * @return void
     */
    public static function displayError($const){}

    public static function hasError($const){}

}









