<?php

namespace Delipress\WordPress\Models\AbstractModel;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );


/**
 * AbstractModel
 *
 * @version 1.0.0
 * @since 1.0.0
 */
abstract class AbstractModel {

    /**
     *
     * @var array|stdClass
     */
    protected $object = null;

    /**
     *
     * @return int
     */
    public function getId(){
        if(!$this->object){
            return "";
        }

        if(is_array($this->object)){
            return (int) $this->object["id"];
        }
        else {
            return (int) $this->object->id;
        }

    }

    /**
     *
     * @param string $key
     * @return any
     */
    public function getValue($key){
        if(!$this->object){
            return "";
        }
        
        if(is_array($this->object)){
            if( !array_key_exists( $key, $this->object ) ){
                return null;
            }
            
            return $this->object[$key];
        }

        if( !property_exists( $this->object, $key ) ){
            return null;
        }
        
        return $this->object->{$key};

    }

    /**
     *
     * @return string
     */
    public function getCreatedAt(){
        if(!$this->object){
            return "";
        }   

        $createdAt = $this->getValue("created_at");
        if(!$createdAt){
            return null;
        }
    
        return  date_i18n( get_option( 'date_format' ), strtotime( $createdAt ) ); 
    }


}
