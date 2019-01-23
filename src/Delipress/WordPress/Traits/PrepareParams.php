<?php

namespace Delipress\WordPress\Traits;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use Delipress\WordPress\Helpers\AdminFormValues;

trait PrepareParams {   

    /**
     * 
     * @param string $type
     * @param string $key
     * @param string (POST|GET) $from
     * @return array
     */
    protected function getParams($type, $key, $from = "POST"){
        $params = array();

        if($key){
            if($from === "POST") {
                if(property_exists($this, "fieldsPosts") && array_key_exists($key, $this->fieldsPosts)){
                    $fields = $this->fieldsPosts[$key];
                }
                else{
                    array();
                }
                if(property_exists($this, "fieldsRequired") && array_key_exists($key, $this->fieldsRequired)){
                    $fieldsRequired = $this->fieldsRequired[$key];
                }
                else{
                    $fieldsRequired = array();
                }
            }
            else if( property_exists($this, "fieldsGets") && array_key_exists($key, $this->fieldsGets)){
                $fields = $this->fieldsGets[$key];
                if(property_exists($this, "fieldsGetsRequired") && array_key_exists($key, $this->fieldsGetsRequired)){
                    $fieldsRequired = $this->fieldsGetsRequired[$key];
                }

            }
            else{
                $fields         = array();
                $fieldsRequired = array();
            }
        }
        else{
            if($from === "POST") {
                if(property_exists($this, "fieldsPosts") ){
                    $fields = $this->fieldsPosts;
                }
                else{
                    array();
                }
                if(property_exists($this, "fieldsRequired") ){
                    $fieldsRequired = $this->fieldsRequired;
                }
                else{
                    $fieldsRequired = array();
                }
            }
            else if( property_exists($this, "fieldsGets") ){
                $fields = $this->fieldsGets;
                if(property_exists($this, "fieldsGetsRequired") ){
                    $fieldsRequired = $this->fieldsGetsRequired;
                }
            }
            else{
                $fields         = array();
                $fieldsRequired = array();
            }
        }

        if($type === "meta"){
            if($key){
                
                if($from === "POST") {
                    if(property_exists($this, "fieldsPostMetas") && array_key_exists($key, $this->fieldsPostMetas)){
                        $fields = $this->fieldsPostMetas[$key];
                    }
                    else{
                        array();
                    }
                    if(property_exists($this, "fieldsMetasRequired") && array_key_exists($key, $this->fieldsMetasRequired)){
                        $fieldsRequired = $this->fieldsMetasRequired[$key];
                    }
                    else{
                        $fieldsRequired = array();
                    }
                }
                else if( property_exists($this, "fieldsGetsMetas") && array_key_exists($key, $this->fieldsGetsMetas)){
                    $fields = $this->fieldsGetsMetas[$key];
                    if(property_exists($this, "fieldsGetsMetasRequired") && array_key_exists($key, $this->fieldsGetsMetasRequired)){
                        $fieldsRequired = $this->fieldsGetsMetasRequired[$key];
                    }
    
                }
                else{
                    $fields         = array();
                    $fieldsRequired = array();
                }
            }
            else{
                if($from === "POST") {
                    if(property_exists($this, "fieldsPostMetas") ){
                        $fields = $this->fieldsPostMetas;
                    }
                    else{
                        array();
                    }
                    if(property_exists($this, "fieldsMetasRequired") ){
                        $fieldsRequired = $this->fieldsMetasRequired;
                    }
                    else{
                        $fieldsRequired = array();
                    }
                }
                else if( property_exists($this, "fieldsGetsMetas") ){
                    $fields = $this->fieldsGetsMetas;
                    if(property_exists($this, "fieldsGetsMetasRequired") ){
                        $fieldsRequired = $this->fieldsGetsMetasRequired;
                    }
    
                }
                else{
                    $fields         = array();
                    $fieldsRequired = array();
                }
            }
        }
        
        foreach($fields as $key => $value){
            if(
                ( 
                    ($from === "POST" && !isset($_POST[$key]) ) ||
                    ($from === "GET" && !isset($_GET[$key]) )
                ) &&
                in_array($key, $fieldsRequired)
            ){
                $this->missingParameters[$key] = 1;
            }

            if( 
                ( 
                    ($from === "POST" && isset($_POST[$key]) ) ||
                    ($from === "GET" && isset($_GET[$key]) )
                )
            ){

                $fromValue = ($from === "POST") ? $_POST[$key]: $_GET[$key];

                AdminFormValues::register($key, $fromValue);
                
                if(function_exists($value)){
                    $params[$key] = call_user_func($value, $fromValue);
                }
                else if(method_exists($this, $value)){
                    $params[$key] = call_user_func_array(array($this, $value), array($fromValue));
                }
                else if(!empty($value) ) {
                    $this->missingParameters[$key] = 1;
                }
                else{
                    $params[$key] = $fromValue;
                }
            }
        }

        return $params;
    }
     
     /**
      * @param string $key
      * @param string $type (fields|meta)
      */
    public function getPostParams($type = "fields", $key = null){
        return $this->getParams($type, $key, "POST");
    }
    
     /**
      * @param string $key
      * @param string $type (fields|meta)
      */
    public function getGetParams($type = "fields", $key = null){
        return $this->getParams($type, $key, "GET");
    }


}