<?php

namespace Delipress\WordPress\Services;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\WordPress\Services\AbstractTable;
use DeliSkypress\Models\ServiceInterface;
use DeliSkypress\Models\MediatorServicesInterface;

use Delipress\WordPress\Helpers\TableHelper;
use Delipress\WordPress\Helpers\ProviderHelper;
use Delipress\WordPress\Helpers\SubscriberMetaHelper;

/**
 * MetaServices
 *
 * @author Delipress
 * @version 1.0.0
 * @since 1.0.0
 */
class MetaServices implements ServiceInterface, MediatorServicesInterface {

    

    public function setServices($services){
        $this->optionServices   = $services["OptionServices"];
        $this->providerServices = $services["ProviderServices"];
    }

    /**
     *
     * @param string $id
     * @return array
     */
    public function getMetaDataById($id){
        $provider         = $this->optionServices->getProvider();
        
        if(!$provider["is_connect"]){
            return array(
                "success" => false
            );
        }
           
        $providerApi = $this->providerServices
                            ->getProviderApi($provider["key"])
                            ->setSafeError(true);

                            
        return $providerApi->getMetaData($id);
    }

    /**
     *
     * @param string $name
     * @param array $args
     * @return array
     */
    public function getMetaDataByName($name, $args = array()){
        $provider         = $this->optionServices->getProvider();
        
        if(!$provider["is_connect"]){
            return array(
                "success" => false
            );
        }
           
        $providerApi = $this->providerServices
                            ->getProviderApi($provider["key"])
                            ->setSafeError(true);

                            
        return $providerApi->getMetaDataByName($name, $args);
    }


    /**
     *
     * @param string $name
     * @param array $args
     * @return array
     */
    public function insertMetaToProvider($name, $args = array()){
        $provider         = $this->optionServices->getProvider();
        $name             = remove_accents(str_replace(" ", "_", trim($name) ) );
        if(!$provider["is_connect"]){
            return array(
                "success" => false
            );
        }
           
        $providerApi = $this->providerServices
                            ->getProviderApi($provider["key"])
                            ->setSafeError(true);

                            
        $response = $providerApi->getMetaDataByName($name, $args);

        if($response["success"]){
            return $response;
        }
     
        $params = array(
            "datatype" => (isset($args["datatype"])) ? $args["datatype"] : "str",
            "name"     => $name,
            "type"     => (isset($args["type"])) ? $args["type"] : "static",
        );

        $response = $providerApi->createMetaData($params, $args);

        if(!$response["success"]){
            return array(
                "success" => false
            );
        }
                
        return $response;
    }

    /**
     * @return array
     */
    public function getMetasUsers(){
        return array(
            "success" => true,
            "results" => SubscriberMetaHelper::getSubscriberMetas()
        );
    }



    /** 
     * @param int $listId | null
     * @return array
     */
    public function getMetas($listId = null, $args = array()){
         $provider         = $this->optionServices->getProvider();
        
        if(!$provider["is_connect"]){
            return array(
                "success" => false,
                "results" => array()
            );
        }

        $userMetas = array();
        $prepareUserMetas = null;
        if(isset($args["with_meta_wordpress"]) && $args["with_meta_wordpress"]){
            $prepareUserMetas = SubscriberMetaHelper::getSubscriberMetas();
        }

        switch($provider["key"]){
            case ProviderHelper::SENDGRID:
                $prepareUserMetas = SubscriberMetaHelper::getWordPressUserMeta();
                break;
        }

        if($prepareUserMetas){
            foreach($prepareUserMetas as $prepareUserMeta){
                $userMetas[] =  $this->providerServices->getMetaModel($prepareUserMeta);
            }
        }
        
        $providerApi = $this->providerServices
                        ->getProviderApi($provider["key"])
                        ->setSafeError(true);

        $metas = $providerApi->getMetaDatas(
            array(
                "list_id" => $listId
            )
        );



        if(!$metas["success"]){
            return array(
                "success" => false,
                "results" => $userMetas
            );
        }

        $response = array(
            "success" => true,
            "results" => array()
        );

        switch($provider["key"]){
            case ProviderHelper::MAILJET:
                foreach($metas["results"] as $key => $result){
                    $response["results"][$key] = $this->providerServices->getMetaModel($result);
                }
                break;
            case ProviderHelper::MAILCHIMP:
                foreach($metas["results"]["merge_fields"] as $key => $result){
                    $response["results"][$key] = $this->providerServices->getMetaModel($result);
                }
                break;
            case ProviderHelper::SENDGRID:
                foreach($metas["results"]["custom_fields"] as $key => $result){
                    $response["results"][$key] = $this->providerServices->getMetaModel($result);
                }
                break;
            case ProviderHelper::SENDINBLUE:
                foreach($metas["results"]["normal_attributes"] as $key => $result){
                    $response["results"][$key] = $this->providerServices->getMetaModel($result);
                }
                break;
            default:
                $response["results"] = apply_filters(DELIPRESS_SLUG . "_get_meta_datas", $metas);
                break;
        }

        $response["results"] = array_merge(
            $userMetas,
            $response["results"]
        );

        return $response;
    }

}









