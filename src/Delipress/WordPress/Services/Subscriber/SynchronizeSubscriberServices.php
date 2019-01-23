<?php

namespace Delipress\WordPress\Services\Subscriber;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\ServiceInterface;
use DeliSkypress\Models\MediatorServicesInterface;

use Delipress\WordPress\Helpers\ProviderHelper;

use Delipress\WordPress\Models\SubscriberModel;
use Delipress\WordPress\Models\InterfaceModel\ListInterface;
use Delipress\WordPress\Models\InterfaceModel\SubscriberInterface;


/**
 * SynchronizeSubscriberServices
 *
 * @author DeliPress
 * @version 1.0.0
 * @since 1.0.0
 */
class SynchronizeSubscriberServices implements ServiceInterface, MediatorServicesInterface {


    /**
     * @see MediatorServicesInterface
     * 
     * @param array $services
     * @return void
     */
    public function setServices($services){

        $this->optionServices              = $services["OptionServices"];
        $this->providerServices            = $services["ProviderServices"];
        $this->listSubscriberTableServices = $services["ListSubscriberTableServices"];
        $this->subscriberTableServices     = $services["SubscriberTableServices"];
        $this->metaServices                = $services["MetaServices"];

    }

    /**
     * 
     * @param ListInterface $list
     * @param string $email
     * @param array $params
     * @return void
     */
    public function subscriberSynchronizeOnList(ListInterface $list, $email, $params = array()){

        $params["safeError"] = (isset($params["safeError"])) ? $params["safeError"] : false;
        $provider            = $this->optionServices->getProvider();

        if(
            !array_key_exists("is_connect", $provider) ||
            !$provider["is_connect"]
        ){
            
            return array(
                "success" => false
            );
        }

        $response = $this->synchronizeSubscriberOnList(
            $list->getId(),
            array_merge(
                $params,
                array(
                    "email"       => $email
                )
            ),
            $params["safeError"]
        );

        return $response;
    }
    
    /**
     * 
     * @param ListInterface $list
     * @param array $contacts
     * @param bool $safeError
     * @return void
     */
    public function subscribersSynchronizeOnList(ListInterface $list, $contacts, $safeError = false){

        $provider            = $this->optionServices->getProvider();

        if(
            !array_key_exists("is_connect", $provider) ||
            !$provider["is_connect"]
        ){
            
            return array(
                "success" => false
            );
        }

        return $this->synchronizeSubscribersOnList($list->getId(), $contacts, $safeError);

    }

    /**
     * 
     * @param ListInterface $list
     * @param SubscriberInterface $subscriber
     * @param array $params
     * @return void
     */
    public function editSubscriberSynchronizeOnList(ListInterface $list, SubscriberInterface $subscriber, $params){

        $params["safeError"] = (isset($params["safeError"])) ? $params["safeError"] : false;
        $provider            = $this->optionServices->getProvider();

        if(
            !array_key_exists("is_connect", $provider) ||
            !$provider["is_connect"]
        ){
            return array(
                "success" => false
            );
            
        }
        
        $response = $this->editSynchronizeSubscriberOnList(
            $list->getId(),
            $subscriber->getId(),
            $params,
            $params["safeError"]
        );

        return $response;
    }

    /**
     *
     * @param array $metas
     * @param int $listId
     * @return void
     */
    protected function verifyMetaData($metas, $listId = null){

        foreach($metas as $key => $value){
            $this->metaServices->insertMetaToProvider($key, array(
                "list_id" => $listId
            ));
        }
    }

    /**
     * @action DELIPRESS_SLUG . "_before_synchronize_subscriber_on_list"
     * @action DELIPRESS_SLUG . "_after_synchronize_subscriber_on_list"
     * 
     * @param int $listId
     * @param array $params
     * @param bool $safeError
     * @return array
     */
    public function synchronizeSubscriberOnList($listId, $params, $safeError = false){
        
        $provider        = $this->optionServices->getProviderKey();
        
        do_action(DELIPRESS_SLUG . "_before_synchronize_subscriber_on_list", $listId, $params, $provider);

        $metas = (isset($params["metas"])) ? $params["metas"] : array();
        $this->verifyMetaData($metas, $listId);

        $response = $this->providerServices
                         ->getProviderApi($provider)
                         ->setSafeError($safeError)
                         ->createSubscriberOnList($listId, $params);

        do_action(DELIPRESS_SLUG . "_after_synchronize_subscriber_on_list", $response);

        return $response;
    }

    /**
     * @action DELIPRESS_SLUG . "_before_synchronize_subscribers_on_list"
     * @action DELIPRESS_SLUG . "_after_synchronize_subscribers_on_list"
     * 
     * @param int $listId
     * @param array $params
     * @param bool $safeError
     * @return array
     */
    public function synchronizeSubscribersOnList($listId, $params, $safeError = false, $verifyMetaData = true){

        $provider        = $this->optionServices->getProviderKey();
        
        do_action(DELIPRESS_SLUG . "_before_synchronize_subscribers_on_list", $listId, $params, $provider);

        $metas = (isset($params["metas"])) ? $params["metas"] : array();
        if($verifyMetaData){
            $this->verifyMetaData($metas, $listId);
        }
        
        $response = $this->providerServices
                         ->getProviderApi($provider)
                         ->setSafeError($safeError)
                         ->createSubscribersOnList($listId, $params);

        do_action(DELIPRESS_SLUG . "_after_synchronize_subscribers_on_list", $response);

        return $response;
    }

    /**
     * @action DELIPRESS_SLUG . "_before_edit_synchronize_subscriber_on_list"
     * @action DELIPRESS_SLUG . "_after_edit_synchronize_subscriber_on_list"
     * 
     * @param int $listId
     * @param int $subscriberId
     * @param array $params
     * @param bool $safeError
     * @return array
     */
    public function editSynchronizeSubscriberOnList($listId, $subscriberId, $params, $safeError = false){
        $provider        = $this->optionServices->getProviderKey();

        do_action(DELIPRESS_SLUG . "_before_edit_synchronize_subscriber_on_list", $listId, $subscriberId, $params, $provider);

        $response = $this->providerServices
                         ->getProviderApi($provider)
                         ->setSafeError($safeError)
                         ->editSubscriberOnList($listId, $subscriberId, $params);


        do_action(DELIPRESS_SLUG . "_after_edit_synchronize_subscriber_on_list", $response);

        return $response;
    }


}









