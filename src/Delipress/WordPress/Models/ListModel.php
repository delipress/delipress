<?php

namespace Delipress\WordPress\Models;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use Delipress\WordPress\Models\InterfaceModel\ListInterface;
use Delipress\WordPress\Helpers\TaxonomyHelper;
use Delipress\WordPress\Helpers\PostTypeHelper;

use Delipress\WordPress\Services\Table\TableServices;

class ListModel implements ListInterface {

    public function __construct($object = null){
        $this->setObject($object);
    }

    public function setObject($object){
        $this->object = $object;
        return $this;
    }

    public function getId(){
        if(property_exists($this, "object") && isset($this->object->term_id)){
            return $this->object->term_id;
        }

        if(property_exists($this, "id")){
            return $this->id;
        }

        return null;
    }

    public function setId($id){
        $this->id = $id;
        return $this;
    }

    public function getName(){
        if(property_exists($this, "object") && isset($this->object->name)){
            return $this->object->name;
        }

        if(property_exists($this, "name")){
            return $this->name;
        }

        return "";
    }

    public function setName($name){
        $this->name = $name;
        return $this;
    }

    public function setList($list){
        $this->object = $list;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isDynamic(){
        return false;
    }

    /**
     *
     * @return boolean
     */
    public function synchronizeInWork(){
        $transient = get_transient(DELIPRESS_SLUG . "_sync_import_background", false);
        if(!$transient){
            $transient = get_transient(DELIPRESS_SLUG . "_sync_export_background", false);
        }
        if(!$transient){
            $transient = get_transient(DELIPRESS_SLUG . "_save_one_item_import_background_file", false);
            if($transient){
                $id = $this->getId();
                if($id === $transient["list_id"]){
                    return true;
                }
                return false;
            }
        }

    
        $transient = get_transient(DELIPRESS_SLUG . "_sync_import_background_file", false);
       
        if(!$transient){
            return false;
        }
        
        $id = $this->getId();
        if(!array_key_exists($id, $transient)){
            return false;
        }
        return ($transient[$id]) ? true : false;
    }

    /**
     * @return boolean
     */
    public function isConnector(){
        global $delipressPlugin;
        $isConnector = false;
        $connectors = $delipressPlugin->getService("OptionServices")->getConnectors();
        foreach($connectors as $key => $connector){
            if(!isset($connector["list_id"])){
                continue;
            }
            
            if($connector["list_id"] == 0){
                continue;
            }

            if($connector["list_id"] == $this->getId()){
                $isConnector = true;
                break;
            }
        }
        return $isConnector;
    }

    /**
     * @return array
     */
    public function getOldOptins(){
        return get_posts(
            array(
                'post_type' => PostTypeHelper::CPT_OPTINFORMS,
                'numberposts' => -1,
                'tax_query' => array(
                    array(
                        'taxonomy' => TaxonomyHelper::TAXO_LIST,
                        'field'    => 'id',
                        'terms'    => $this->getId(),
                    )
                )
            )
        );
    }

    /**
     * @return array
     */
    public function getOptins(){
        global $delipressPlugin;

        $optins     = $delipressPlugin->getService("OptinServices")
                                      ->getOptins(array(
                                               "posts_per_age" => -1
                                      ));
        $optinsForList = array();

        foreach($optins as $optin){
            $lists = $optin->getLists();
            if(empty($lists)){
                continue;
            }

            foreach($lists as $list){
                if($list->getId() == $this->getId()){
                    $optinsForList[] = $optin;
                }
            }
        }

        return $optinsForList;
    }


    /**
     * @return string
     */
    protected function getMetaProvider(){
        return get_term_meta(
            $this->getId(), 
            TaxonomyHelper::META_LIST_ID_PROVIDER , 
            true
        );
    }

    /**
     * @return string
     */
    public function getProvider(){
        $getMetaProvider = $this->getMetaProvider();
        if(!$getMetaProvider){
            return null;
        }
        $metaProvider = explode("_", $getMetaProvider);
        return $metaProvider[0];
    }
    /**
     * @return string
     */
    public function getProviderId(){
        $getMetaProvider = $this->getMetaProvider();
        if(!$getMetaProvider){
            return null;
        }
        $metaProvider = explode("_", $getMetaProvider);
        return $metaProvider[1];
    }

    public function countSubscribers(){
        return 0;
    }

}
