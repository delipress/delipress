<?php

namespace Delipress\WordPress\Models\AbstractModel;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );


/**
 * AbstractModelPostType
 *
 * @version 1.0.0
 * @since 1.0.0
 */
abstract class AbstractModelPostType {

    /**
     * @var object
     */
    protected $object = null;

    /**
     * @return int|string
     */
    public function getId(){
        if(!$this->object){
            return "";
        }

        return (int) $this->object->ID;
    }
    
    /**
     * @return string
     */
    public function getTitle(){
        if(!$this->object){
            return "";
        }
        
        return $this->object->post_title;
    }

    /**
     * @return string
     */
    public function getPostMeta($key, $meta, $single = true){
        if(!$this->object){
            return "";
        }
        
        if( property_exists( $this, $key ) ){
            return $this->{$key};
        }

        $this->{$key} = get_post_meta(
            $this->getId(), 
            $meta, 
            $single
        );

        return $this->{$key};
    }

    /**
     * 
     * @param string $key
     * @param string $meta
     * @param boolean $single
     * @return string|array
     */
    public function getPostMetaJsonDecode($key, $meta, $single = true){
        if(!$this->object){
            return "";
        }

        if( property_exists( $this, $key ) ){
            return $this->{$key};
        }

        $this->{$key} = json_decode($this->getPostMeta($key, $meta, $single), true);

        return $this->{$key};
    }

    /**
     * 
     * @return string
     */
    public function getAuthor(){
        if(!$this->object){
            return "";
        }

        return get_the_author_meta("display_name", $this->object->post_author);
    }

    /**
     * @return string
     */
    public function getCreatedAt(){
        if(!$this->object){
            return "";
        }

        return  date_i18n( get_option( 'date_format' ), strtotime( $this->object->post_date ) ); 
    }


}
