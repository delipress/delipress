<?php

namespace Delipress\WordPress\Services\Subscriber;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\ServiceInterface;
use DeliSkypress\Models\MediatorServicesInterface;

use Delipress\WordPress\Helpers\TaxonomyHelper;
use Delipress\WordPress\Helpers\PostTypeHelper;
use Delipress\WordPress\Helpers\AdminNoticesHelper;
use Delipress\WordPress\Helpers\CodeErrorHelper;
use Delipress\WordPress\Helpers\ProviderHelper;

use Delipress\WordPress\Models\InterfaceModel\SubscriberInterface;
use Delipress\WordPress\Models\InterfaceModel\ListInterface;
use Delipress\WordPress\Models\SubscriberModel;

use Delipress\WordPress\Traits\PrepareParams;
use Delipress\WordPress\Traits\Listing\ListTrait;
use Delipress\WordPress\Traits\Subscriber\SubscriberTrait;

use Delipress\WordPress\Services\Table\TableServices;


/**
 * DeleteSubscriberServices
 *
 * @author DeliPress
 * @version 1.0.0
 * @since 1.0.0
 */
class DeleteSubscriberServices implements ServiceInterface, MediatorServicesInterface{

    use PrepareParams;

    protected $missingParameters = array();

    /**
     * @see MediatorServicesInterface
     * 
     * @param array $services
     * @return void
     */
    public function setServices($services){

        $this->optionServices   = $services["OptionServices"];
        $this->providerServices = $services["ProviderServices"];
        $this->listServices       = $services["ListServices"];
        $this->subscriberServices = $services["SubscriberServices"];

    }


    /**
     * 
     * @param ListInterface $list
     * @param SubscriberInterface $subscriber
     * @return array
     */
    protected function deleteOneSubscriberOnList(ListInterface $list, SubscriberInterface $subscriber, $safeError = false){

        
        $provider = $this->optionServices->getProvider();
        $response = array(
            "success" => true
        );

        if(
            !array_key_exists("is_connect", $provider) ||
            !$provider["is_connect"]
        ){

            return array(
                "success" => false
            );
            
        }

        $listConnectId = $list->getId();
        
        $response = $this->providerServices
                            ->getProviderApi($provider["key"])
                            ->setSafeError($safeError)
                            ->deleteSubscriberOnList(
                                $listConnectId,
                                array(
                                    "email"  => $subscriber->getEmail(),
                                    "id"     => $subscriber->getId()
                                )    
                            );

        if(!$response["success"]){
            $response = array(
                "success" => false
            );
        }
        
        
        return $response;
    }


    /**
     * @action DELIPRESS_SLUG . "_before_delete_subscriber_from_list"
     * @action DELIPRESS_SLUG . "_after_delete_subscriber_from_list"
     * 
     * @return void
     */
    public function deleteSubscriberFromList(){
        $this->fieldsGets = array(
            "list_id"       => "",
            "subscriber_id" => ""
        );

        $this->fieldsRequired = array(
            "list_id",
            "subscriber_id"
        );

        $params = $this->getGetParams("fields");

        do_action(DELIPRESS_SLUG . "_before_delete_subscriber_from_list", $params);

        if(!empty($this->missingParameters)){
            AdminNoticesHelper::registerError(
                CodeErrorHelper::ADMIN_NOTICE,
                CodeErrorHelper::getMessage(CodeErrorHelper::TRY_CHEAT)
            );
        }

        $list       = $this->listServices->getList($params["list_id"]);
        $subscriber = $this->subscriberServices->getSubscriberByList($params["list_id"], $params["subscriber_id"]);

        $response = $this->deleteOneSubscriberOnList($list, $subscriber);

        if($response["success"] ) {
            AdminNoticesHelper::registerSuccess(
                CodeErrorHelper::ADMIN_NOTICE,
                CodeErrorHelper::getMessage(CodeErrorHelper::DELETE_SYNCHRONIZE_SUBSCRIBER_ON_LIST_SUCCESS)
            );
        }
        else{
            AdminNoticesHelper::registerError(
                CodeErrorHelper::ADMIN_NOTICE,
                CodeErrorHelper::getMessage(CodeErrorHelper::ADMIN_NOTICE_ERROR_DEFAULT)
            );
        }

        $result = array(
            "success" => true,
            "results" => array(
                "list"        => $list,
                "subscriber"  => $subscriber,
                "synchronize" => $response
                
            )   
        );

        do_action(DELIPRESS_SLUG . "_after_delete_subscriber_from_list", $result);

        return $result;

    }

    /**
     * 
     * @param ListInterface $list
     * @param SubscriberInterface $subscriber
     * @return array
     */
    public function deleteSubscriberOnList(ListInterface $list, SubscriberInterface $subscriber){

        return $this->deleteOneSubscriberOnList($list, $subscriber);
    }

    /**
     * 
     * @return void
     */
    public function deleteSubscribersFromList(){
        $this->fieldsPosts = array(
            "list_id"       => "",
            "subscribers"   => ""
        );

        $this->fieldsRequired = array(
            "list_id",
            "subscribers"
        );

        $params = $this->getPostParams("fields");

        do_action(DELIPRESS_SLUG . "_before_delete_subscribers_from_list", $params);

        if(!empty($this->missingParameters)){
            AdminNoticesHelper::registerError(
                CodeErrorHelper::ADMIN_NOTICE,
                CodeErrorHelper::getMessage(CodeErrorHelper::TRY_CHEAT)
            );
        }

        $list       = $this->listServices->getList($params["list_id"]);
        if(!$list){
            return array(
                "success" => false
            );
        }

        $provider = $this->optionServices->getProvider();
        if(empty($params["subscribers"])){
            return array(
                "success" => true,
                "results" => array(
                    "list" => $list
                )
            );
        }
        
        foreach($params["subscribers"] as $key => $subscriber){
            $subscriberModel = new SubscriberModel();
            switch($provider["key"]){
                case ProviderHelper::MAILJET:
                    $subscriberModel->setEmail($subscriber);
                    break;
                default:
                    $subscriberModel->setId($subscriber);
                    break;

            }
            
            $response = $this->deleteOneSubscriberOnList($list, $subscriberModel);
        }
        
        if($response["success"] ) {
            AdminNoticesHelper::registerSuccess(
                CodeErrorHelper::ADMIN_NOTICE,
                CodeErrorHelper::getMessage(CodeErrorHelper::DELETE_SYNCHRONIZE_SUBSCRIBER_ON_LIST_SUCCESS)
            );
        }
        else{
            AdminNoticesHelper::registerError(
                CodeErrorHelper::ADMIN_NOTICE,
                CodeErrorHelper::getMessage(CodeErrorHelper::ADMIN_NOTICE_ERROR_DEFAULT)
            );
        }

        $result = array(
            "success" => true,
            "results" => array(
                "list"        => $list,
                "subscribers" => $params["subscribers"],
                "synchronize" => $response
                
            )   
        );

        do_action(DELIPRESS_SLUG . "_after_delete_subscribers_from_list", $result);

        return $result;
    }
}









