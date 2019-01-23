<?php

namespace Delipress\WordPress\Models\AbstractModel;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );


/**
 * AbstractModelTerm
 *
 * @version 1.0.0
 * @since 1.0.0
 */
abstract class AbstractModelTerm {

    /**
     * @var object|null
     */
    protected $object = null;

    public function getObject(){
        return $this->object;
    }

    public function getId(){
        if(!$this->object){
            return "";
        }
        if(property_exists( $this, "term_id" )){
            return $this->term_id;
        }

        return (int) $this->object->term_id;
    }

    /**
     *
     * @return empty|string
     */
    public function getName(){

        if(!$this->object){
            return "";
        }
        
        if(property_exists( $this, "name" )){
            return esc_html($this->name);
        }
        
        return esc_html($this->object->name);
    }

    /**
     *
     * @param string $key
     * @param string $meta
     * @param boolean $single
     * @param boolean $withCache
     * @return empty|any
     */
    public function getTermMeta($key, $meta, $single = true, $withCache = true){

        if(!$this->object){
            return "";
        }

        if(property_exists( $this, $key ) && $withCache){
            return $this->{$key};
        }

        $this->{$key} = get_term_meta(
            $this->getId(), 
            $meta , 
            $single
        );
        
        return $this->{$key};
    }


}
