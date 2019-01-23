<?php

namespace Delipress\WordPress\Models\AbstractModel;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\ServiceInterface;


/**
 * AbstractOptionServices
 *
 * @author Delipress
 * @version 1.0.0
 * @since 1.0.0
 */
class AbstractOptionServices implements ServiceInterface{

    protected $optionsDefault = array();

    protected $nameOptions = null;

    protected $cacheOptions = null;

    /**
     * @param string $key
     * @return array
     */
    public function getOptionsByKey($key){

        $options = $this->getOptions();
        if(array_key_exists($key, $options)){
            return $options[$key];
        }   

        return null;
    }

    /**
     * @param array $options
     */
    public function setOptions($options){    
        $newOptions = wp_parse_args( $options, $this->getOptions() );
        update_option($this->nameOptions , $newOptions);
    }

    /**
     *
     * @param array $options
     * @param string $key
     * @return void
     */
    public function setOptionsByKey($options, $key){
        $newOptions = $this->getOptions();

        if(!isset($newOptions[$key])){
            return null;
        }
        $newOptions[$key] = wp_parse_args( $options, $newOptions[$key] );

        update_option($this->nameOptions , $newOptions);
    }

    /**
     * @return array $optionsDefault
     */
    public function getSettingsDefault(){
        return $this->optionsDefault;
    }

    /**
     * @return array
     */ 
    public function getOptions($noCache = false){
        if($this->cacheOptions === null || $noCache){
            $this->cacheOptions = wp_parse_args( get_option($this->nameOptions), $this->getSettingsDefault() );
        }

        return $this->cacheOptions;
    }

}









