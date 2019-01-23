<?php

namespace Delipress\WordPress\Models;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use Delipress\WordPress\Helpers\PageAdminHelper;
use Delipress\WordPress\Helpers\PostTypeHelper;
use Delipress\WordPress\Helpers\TaxonomyHelper;
use Delipress\WordPress\Helpers\OptinHelper;

use Delipress\WordPress\Models\AbstractModel\AbstractModelPostType;
use Delipress\WordPress\Models\ListModel;
use Delipress\WordPress\Services\Table\TableServices;

/**
 * OptinModel
 *
 * @version 1.0.0
 * @since 1.0.0
 */
class OptinModel extends AbstractModelPostType{

    /**
     * @var int
     */
    protected $templateId = null;
    
    /**
     *
     * @param int $id
     * @return OptinModel
     */
    public function setOptinById($id){
        $this->object = get_post($id);
        return $this;
    }


    /**
     *
     * @param WP_Post|Array $optin
     * @return OptinModel
     */
    public function setOptin($optin){
        $this->object = $optin;
        return $this;
    }

    public function getOldLists($type = "object"){
        if(!$this->object){
            return array();
        }

        if(property_exists($this, "lists") ){
            return $this->filteredLists($type);
        }

        $lists = wp_get_object_terms( array($this->getId() ), TaxonomyHelper::TAXO_LIST );

        foreach($lists as $key => $value){
            $list = new ListModel();
            $list->setList($value);
            $lists[$key] = $list;
        }
        
        $this->lists = $lists;
        
        return $this->filteredLists($type);
    }
    
    /**
     *
     * @param string $type
     * @return array
     */
    public function getLists($type = "object"){
        if(!$this->object){
            return array();
        }

        $this->getPostMeta("listsMeta", PostTypeHelper::OPTIN_TAXO_LISTS); 
        
        return $this->filteredLists($type);

    }

    /**
     *
     * @return int
     */
    public function getCountLists(){
        $lists = $this->getLists("IDS");

        if(!$lists){
            return 0;
        }

        return count($lists);
    }

    /**
     * @return array
     */
    protected function filteredLists($type){

        switch($type){
            case "object":
                if(property_exists($this, "lists")){
                    return $this->lists;
                }

                global $delipressPlugin;

                $this->lists = array();
                if(empty($this->listsMeta)){
                    return $this->lists;
                }

                foreach($this->listsMeta as $listId){
                    $result =  $delipressPlugin->getService("ListServices")
                                                     ->getList($listId, true);
                    if($result){
                        $this->lists[] = $result;
                    }

                }

                return $this->lists;

            case "IDS":
                return $this->listsMeta;

        }

    }

    /**
     * @return string
     */
    public function getIsActive(){
        return $this->getPostMeta("isActive", PostTypeHelper::META_OPTIN_IS_ACTIVE);
    }

    /**
     * @return string
     */
    public function getType(){
        return esc_html( $this->getPostMeta("type", PostTypeHelper::META_OPTIN_TYPE) ); 
    }

    /**
     * @return string
     */
    public function getTypeLabel(){
        $optin = $this->getType();

        if(!$optin){
            return "";
        }

        $optinValue = OptinHelper::getOptinByKey($optin);
        return esc_html( $optinValue["label"] );
    }

    /**
     * @return int
     */
    public function getCounterView(){
        if(!$this->object){
            return "";
        }

        $optinStatsTableServices = TableServices::getTable("OptinStatsTableServices");
        
        $nb  = $optinStatsTableServices->getCountView($this->getId());
        $key = "counterView";

        if( property_exists( $this, $key ) ){
            return $this->{$key};
        }
        
        if(!$nb){
            return 0;
        }

        $this->{$key} = $nb;

        return $this->{$key};
    }

    /**
     * @return int
     */
    public function getCounterConvert(){
        if(!$this->object){
            return "";
        }

        $optinStatsTableServices = TableServices::getTable("OptinStatsTableServices");
        
        $nb  = $optinStatsTableServices->getCountConvert($this->getId());
        $key = "counterConvert";

        if( property_exists( $this, $key ) ){
            return $this->{$key};
        }
        
        if(!$nb){
            return 0;
        }

        $this->{$key} = $nb;

        return $this->{$key};
    }

    /**
     * @return int|float
     */
    public function getRateCounterView(){
        $view    = $this->getCounterView();
        $convert = $this->getCounterConvert();

        if($view === 0){
            return 0;
        }

        $percent = ($convert * 100) / $view;
        if(is_float($percent)){
            return number_format($percent,2, ".", " ");
        }

        return $percent;
    }

    /**
     *
     * @return array
     */
    public function getBehavior(){
        return $this->getPostMeta("behavior", PostTypeHelper::META_OPTIN_BEHAVIOR);
    }


}
