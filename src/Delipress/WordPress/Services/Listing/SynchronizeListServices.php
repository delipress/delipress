<?php

namespace Delipress\WordPress\Services\Listing;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\ServiceInterface;
use DeliSkypress\Models\MediatorServicesInterface;

use Delipress\WordPress\Helpers\ProviderHelper;
use Delipress\WordPress\Helpers\TaxonomyHelper;

use Delipress\WordPress\Models\InterfaceModel\ListInterface;


/**
 * SynchronizeListServices
 *
 * @author DeliPress
 */
class SynchronizeListServices implements ServiceInterface, MediatorServicesInterface {


    /**
     * @see MediatorServicesInterface
     *
     * @param array $services
     * @return void
     */
    public function setServices($services){
        $this->optionServices   = $services["OptionServices"];
        $this->providerServices = $services["ProviderServices"];

    }

    /**
     *
     * @action DELIPRESS_SLUG . "_before_create_list_synchronize_" . $provider["key"]
     * @action DELIPRESS_SLUG . "_after_create_list_synchronize_" . $provider["key"]
     *
     * @param ListInterface $list
     * @return array
     */
    public function createListSynchronize(ListInterface $list, $params = array()){
        $provider = $this->optionServices->getProvider();

        if(
            !array_key_exists("is_connect", $provider) ||
            !$provider["is_connect"]
        ){

            return array(
                "success" => false
            );

        }

        do_action(DELIPRESS_SLUG . "_before_create_list_synchronize", $list);

        $providerApi = $this->providerServices
                         ->getProviderApi($provider["key"]);

        if(isset($params["safeError"])){
            $providerApi->setSafeError($params["safeError"]);
        }

        $response = $providerApi->createList($list);
        
        $idListProvider = "";

        if($response["success"]){
            switch($provider["key"]){
                case ProviderHelper::MAILJET:
                    $idListProvider = $response["results"][0]["ID"];
                    break;
                case ProviderHelper::MAILCHIMP:
                case ProviderHelper::SENDGRID:
                case ProviderHelper::SENDINBLUE:
                    $idListProvider = $response["results"]["id"];
                    break;
                default:
                    $idListProvider = apply_filters(DELIPRESS_SLUG . "_create_list_synchronize_" . $provider["key"], $response);
                    break;
            }
        }

        do_action(DELIPRESS_SLUG . "_before_create_list_synchronize", $response, $list);

        return array(
            "success" => $response["success"],
            "results" => array(
                "response" => $response["results"],
                "list_id"  => $idListProvider
            )
        );
    }

    /**
     *
     * @param ListInterface $list
     * @param array $params
     * @return array
     */
    public function editListSynchronize(ListInterface $list, $params){

        $provider = $this->optionServices->getProvider();

        if(
            !array_key_exists("is_connect", $provider) ||
            !$provider["is_connect"]
        ){

            return array(
                "success" => false
            );

        }


        $providerApi = $this->providerServices
                         ->getProviderApi($provider["key"]);

        if(isset($params["safeError"])){
            $providerApi->setSafeError($params["safeError"]);
        }

        $response = $providerApi->editList($list, $params);
        
        $idListProvider = "";
        
        if($response["success"]){
            switch($provider["key"]){
                case ProviderHelper::MAILJET:
                    $idListProvider = $response["results"][0]["ID"];
                    break;
                case ProviderHelper::MAILCHIMP:
                case ProviderHelper::SENDGRID:
                case ProviderHelper::SENDINBLUE:
                    $idListProvider = $response["results"]["id"];
                    break;
                default:
                    $idListProvider = apply_filters(DELIPRESS_SLUG . "_cedit_list_synchronize_" . $provider["key"], $response);
                    break;
            }
        }


        return array(
            "success" => $response["success"],
            "results" => array(
                "response" => $response["results"],
                "list_id"  => $idListProvider
            )
        );
    }


}
