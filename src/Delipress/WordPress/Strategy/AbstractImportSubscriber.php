<?php

namespace Delipress\WordPress\Strategy;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use Delipress\WordPress\Services\Table\TableServices;

use Delipress\WordPress\Helpers\ProviderHelper;
use Delipress\WordPress\Helpers\SubscriberMetaHelper;

/**
 * AbstractImportSubscriber
 *
 * @author Delipress
 * @version 1.0.0
 * @since 1.0.0
 */
class AbstractImportSubscriber  {

    protected $range = 100;
    
    public function setServices($services){
        $this->metaServices   = $services["MetaServices"];
        $this->optionServices = $services["OptionServices"];
        return $this;
    }

    /**
     * 
     * @param array $file
     * @return AbstractImportSubscriber
     */
    public function setFile($file){
        $this->file = $file;
        return $this;
    }

    /**
     * 
     * @param string $provider
     * @return AbstractImportSubscriber
     */
    public function setProvider($provider){
        $this->provider = $provider;
        return $this;
    }

    /**
     * 
     * @param array $data
     * @return AbstractImportSubscriber
     */
    public function setData($data){
        $this->data = $data;
        return $this;
    }

    /**
     * @return array
     */
    public function getData(){
        return $this->data;
    }
    
    /** 
     * @return int
     */
    public function getRange(){
        return apply_filters(DELIPRESS_SLUG  . "_abstract_import_subscriber_range", $this->range);
    }

    /**
     *
     * @param Object $backgroundProcess
     * @return AbstractImportSubscriber
     */
    public function setBackgroundProcess($backgroundProcess){
        $this->backgroundProcess = $backgroundProcess;
        return $this;
    }

    /**
     * @return Object
     */
    public function getBackgroundProcess(){
        return $this->backgroundProcess;
    }

    /**
     * @return void
     */
    public function execute(){

        $data = $this->getData();

        if(empty($data) || !isset($data["create_import"]) ){
            return;
        }
        
        foreach($data["create_import"] as $key => $value){
            if(empty($value)){
                continue;
                
            }
            $name = remove_accents(str_replace(" ", "_", trim($value) ) );
            $this->metaServices->insertMetaToProvider($name, array(
                "list_id" => $data["list_id"]
            ));

            $data["create_import"][$key] = $name;
        }

        $provider            = $this->optionServices->getProviderKey();
        $keysMetasSubscriber = SubscriberMetaHelper::getKeysWordPressUser();

        foreach($data["meta_import"] as $key => $value){
            if(empty($value)){
                continue;
            }

            if($value && in_array($value, $keysMetasSubscriber)){
                continue;
            }
            
            switch($provider){
                case ProviderHelper::MAILJET:
                    $result = $this->metaServices->getMetaDataById($value);

                    if($result["success"]){
                        $meta = current($result["results"]);
                        $data["meta_import"][$key] = $meta["Name"];
                    }
                    break;
                case ProviderHelper::SENDGRID:
                    $result = $this->metaServices->getMetaDataById($value);
                    if($result["success"]){
                        $data["meta_import"][$key] = $result["results"]["name"];
                    }
                    break;
                case providerHelper::SENDINBLUE:
                    break;
            }
            
        }

        $this->setData($data);

    }

    /**
     * @param array $params
     * 
     * @return void
     */
    public function pushBackgroundProcess($params){
        $range                   = $this->getRange();
        $countCall               = ceil($params["total_subscribers"] /  $range);

        for ($i=1; $i <= $countCall; $i++) {
            $arr = array(
                "page"              => $i,
                "provider"          => $this->provider,
                "limit"             => $range,
                "total_call"        => $countCall,
                "file"              => $this->file,
                "total_subscribers" => $params["total_subscribers"],
            );

            $arr = array_merge($arr, $params, $this->getData());

            $this->getBackgroundProcess()->push_to_queue($arr);
        }
        
        $this->getBackgroundProcess()->save()->dispatch();

    }

}

