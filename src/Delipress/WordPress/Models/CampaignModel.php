<?php

namespace Delipress\WordPress\Models;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use Delipress\WordPress\Helpers\PageAdminHelper;
use Delipress\WordPress\Helpers\PostTypeHelper;
use Delipress\WordPress\Helpers\TaxonomyHelper;

use Delipress\WordPress\Models\AbstractModel\AbstractModelPostType;
use Delipress\WordPress\Models\ListModel;

/**
 * Campaign model
 *
 * @version 1.0.0
 * @since 1.0.0
 */
class CampaignModel extends AbstractModelPostType{

    protected $templateId = null;

    /**
     *
     * @param int $id
     * @return CampaignModel
     */
    public function setCampaignById($id){
        $this->object = get_post($id);
        return $this;
    }

    /**
     *
     * @param std $campaign
     * @return CampaignModel
     */
    public function setCampaign($campaign){
        $this->object = $campaign;
        return $this;
    }

    /**
     * 
     * @return string
     */
    public function getSubject(){
        return esc_html( $this->getPostMeta("subject", PostTypeHelper::META_CAMPAIGN_SUBJECT) );
    }


    /**
     * @return boolean
     */
    public function hasValidStepOne(){
        $subject  = $this->getSubject();
        $title    = $this->getTitle();
        $list     = $this->getListSend();
        $dateSend = $this->getSend();

        if(
            !empty($subject) &&
            !empty($title) &&
            !empty($dateSend) &&
            !is_null($list)
        ){
            return true;
        }

        return false;
    }


    /**
     * 
     * @return string
     */
    public function getSend(){
        return $this->getPostMeta("send", PostTypeHelper::META_CAMPAIGN_SEND);
    }

    /**
     * 
     * @return boolean
     */
    public function getIsSend(){
        if(!$this->object){
            return false;
        }
        
        if($this->object->post_status == "draft"){
            return false;
        }

        $send = $this->getSend();
        if($send === "now"){
            return $this->getPostMeta("isSend", PostTypeHelper::META_CAMPAIGN_IS_SEND);
        }
        else{
            $dateSend = $this->getDateSend();
            $timezoneWP = get_option('timezone_string');
            if(empty($timezoneWP)){
                $timezoneWP = new \DateTimeZone("UTC");
            }
            else{
                $timezoneWP = new \DateTimeZone($timezoneWP);
            }
            $now = new \DateTime("now", $timezoneWP);
            if(strtotime($dateSend) < strtotime($now->format("Y-m-d H:i:s"))){
                return true;
            }
            else{
                return false;
            }
        }
    }

    /**
     * 
     * @param string $format
     * @return string
     */
    public function getDateSend($format = false){
        $typeSend = $this->getSend();

        if($typeSend === "later"){
            $dateSend = $this->getPostMeta("dateSend", PostTypeHelper::META_CAMPAIGN_DATE_SEND);
        }
        else{
            if(!$this->object){
                return "";
            }
            
            if(is_object($this->object)){
                $dateSend = $this->object->post_modified;
            }
            else{
                $dateSend = $this->object["post_modified"];
            }   
        }
        
        if(!$format) {
            return $dateSend;
        }

        return date_i18n( get_option( 'date_format' ) . " " . get_option( 'time_format' ), strtotime( $dateSend ) );
    }

    /**
     * 
     * @return string
     */
    public function getDateSendFormValue(){
        $typeSend = $this->getSend();
        if($typeSend === "now"){
            return "";
        }

        $locale = get_locale();
        $value  = $this->getDateSend();
        $date   = \DateTime::createFromFormat("Y-m-d H:i:s", $value);
        if(!$date){
            return "";
        }
        switch($locale){
            case "fr_FR":
                return $date->format("d-m-Y H:i");
                break;
            default:
                return $date->format("m-d-Y H:i");
                break;
        }
    }

    /**
     * @return string
     */
    public function getHtml(){
        return $this->getPostMeta("html", PostTypeHelper::META_CAMPAIGN_TEMPLATE_HTML);
    }

    /**
     * 
     * @param string $type
     * @return ListModel
     */
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
     * @return ListModel
     */
    public function getLists(){
        if(!$this->object){
            return new ListModel();
        }
        
        if(property_exists($this, "list") ){
            return $this->list;
        }

        global $delipressPlugin;
        $listId = $this->getPostMeta("list", PostTypeHelper::CAMPAIGN_TAXO_LISTS);

        $this->list     = $delipressPlugin->getService("ListServices")
                                           ->getList($listId, true);

        if(!$this->list){
            return new ListModel();
        }

        return $this->list;
    }

    /**
     * @return ListInterface
     */
    public function getListSend(){
        return $this->getLists();
    }

    /**
     * 
     * @param string $type
     * @return array
     */
    protected function filteredLists($type){
        switch($type){
            case "object":
                return $this->lists;
                break;
            case "IDS":
                $filtered = array();
                foreach($this->lists as $key => $list){
                    $filtered[] = $list->getId();
                }
                return $filtered;
                break;

        }

    }

    /**
     * 
     * @return string
     */
    public function getConfig(){
        return $this->getPostMeta("config", PostTypeHelper::META_CAMPAIGN_TEMPLATE_CONFIG);
    }

    /**
     * 
     * @return string
     */
    public function getMetaCampaignProvider(){
        return $this->getPostMeta("metaProvider", PostTypeHelper::META_CAMPAIGN_CAMPAIGN_PROVIDER_ID);
    }

    public function setMetaCampaignProvider($provider){
        $this->metaProvider = $provider;
        return $this;
    }

    /**
     * 
     * @return string
     */
    public function getCampaignProvider(){
        if(property_exists($this, "campaignProvider")){
            return $this->campaignProvider;
        }

        $this->campaignProvider = $this->getPostMeta("metaProvider", PostTypeHelper::META_CAMPAIGN_CAMPAIGN_PROVIDER);

        if(!$this->campaignProvider){
            $metaProvider = explode("_", $this->getMetaCampaignProvider());
            return $metaProvider[0];
        }

        return $this->campaignProvider;


    }

    /**
     * 
     * @return string
     */
    public function getCampaignProviderId(){
        $metaProvider = explode("_", $this->getMetaCampaignProvider());
        return (isset($metaProvider[1])) ? $metaProvider[1] : null;
    }

    /**
     * @return string
     */
    public function getTokenOnline(){
        return esc_html( $this->getPostMeta("tokenOnline", PostTypeHelper::META_CAMPAIGN_TOKEN_ONLINE) );
    }

}
