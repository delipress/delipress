<?php

namespace Delipress\WordPress\Services\Subscriber;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\ServiceInterface;
use DeliSkypress\Models\MediatorServicesInterface;

use Delipress\WordPress\Helpers\PostTypeHelper;
use Delipress\WordPress\Helpers\PageAdminHelper;
use Delipress\WordPress\Helpers\ActionHelper;
use Delipress\WordPress\Helpers\ProviderHelper;

use Delipress\WordPress\Models\SubscriberModel;

use Delipress\WordPress\Services\Table\TableServices;

/**
 * SubscriberServices
 *
 * @author Delipress
 * @version 1.0.0
 * @since 1.0.0
 */
class SubscriberServices implements ServiceInterface, MediatorServicesInterface{

    /**
     * @see MediatorServicesInterface
     * 
     * @param array $services
     * @return void
     */
    public function setServices($services){

        $this->optionServices       = $services["OptionServices"];
        $this->providerServices     = $services["ProviderServices"];
        $this->emailHtmlServices    = $services["EmailHtmlServices"];

    }

    /** 
     * @param int $listId
     * @param int $idSubscriber
     */
    public function getSubscriberByList($listId, $idSubscriber, $args = array()){
        $provider    = $this->optionServices->getProvider();

        $response     = $this->providerServices
                                ->getProviderApi($provider["key"])
                                ->getSubscriber($listId, $idSubscriber);

        if(!$response["success"]){
            return null;
        }

        $contactMetaData = array();
        
        switch($provider["key"]){
            case ProviderHelper::MAILJET:
                $subscriberObj = $response["results"][0];
                $result = $this->getSubscriberMetaData($idSubscriber);

                if($result["success"]){
                    $contactMetaData = $result["results"][0]["Data"];
                }
                break;
            default:
                $subscriberObj = $response["results"];
                break;
        }

        $subscriber = $this->providerServices->getSubscriberModel($provider["key"], $subscriberObj);
        if(method_exists($subscriber, "setMetaData")){
            $subscriber->setMetaData($contactMetaData);
        }
        
        return $subscriber;
    }

    /**
     *
     * @param int|string $listId
     * @param string $email
     * @param array $args
     * @return array | null
     */
    public function searchSubscriber($listId, $email, $args = array()){
        $provider    = $this->optionServices->getProvider();

        $response    = $this->providerServices
                                ->getProviderApi($provider["key"])
                                ->searchSubscribers(
                                    array_merge(
                                        array(
                                            "list_id" => $listId,
                                            "email"   => $email
                                        ),
                                        $args
                                    )
                                );

        if(!$response["success"]){
            return null;
        }

        $result = array(
            "success" => true
        );

        if(empty($response["results"]) ){
            $result["success"] = false;
        }

        switch($provider["key"]){
            case ProviderHelper::MAILJET:
            
                $response = current($response["results"]);
                if(empty($response["Contact"]["Email"]) || !is_array($response["Contact"]["Email"])){
                    $result["success"] = false;
                }
                
                if($result["success"]){
                    $key = array_search($email, $response["Contact"]["Email"]);
                    if($key === false){
                        $result["success"] = false;
                    }
                }

                $result["results"] = $this->providerServices->getSubscriberModel(ProviderHelper::MAILJET, $response);

                break;
            case ProviderHelper::MAILCHIMP:
                $response  = $response["results"];
                $newResult = array();
                if(!empty($response["exact_matches"]["members"])){
                    foreach($response["exact_matches"]["members"] as $member){
                        $newResult[] = $this->providerServices->getSubscriberModel(ProviderHelper::MAILCHIMP, $member);
                    }
                }
                
                $result["results"] = current($newResult);
                break;
            case ProviderHelper::SENDGRID:
            case ProviderHelper::SENDINBLUE:
                $result["results"] = $this->providerServices->getSubscriberModel($provider["key"], $response["results"]);
                break;
        }        

        return $result;
    }

    /** 
     * @param int $idSubscriber
     */
    public function getSubscriber($idSubscriber, $args = array()){
        return $this->getSubscriberByList(null, $idSubscriber, $args);
    }

    /** 
     * @param int $listId
     * @param array $params
     */
    public function getSubscribersByList($listId, $params){

        $provider    = $this->optionServices->getProvider();

        $response     = $this->providerServices
                                ->getProviderApi($provider["key"])
                                ->getListContacts($listId, $params["offset"], $params["limit"]);

        $subscribers = array();
        if(empty($response["results"]) || !$response["success"]){
            return $subscribers;
        }
        
        foreach($response["results"] as $subscriber){
            $subscribers[] = $this->providerServices->getSubscriberModel($provider["key"], $subscriber);
        }

        return $subscribers;
    }

    /** 
     * @param int $subscriberId,
     * @param array $params
     * @return array
     */
    public function getSubscriberMetaData($subscriberId, $params = array()){
        $provider    = $this->optionServices->getProvider();

        $response     = $this->providerServices
                                ->getProviderApi($provider["key"])
                                ->getContactMetaData($subscriberId, $params);

        return $response;

    }


    /**
     * 
     * @param int $listId
     * @return string
     */
    public function getPageSubscribersUrl($listId){

        return add_query_arg(
            array(
                "page"    => PageAdminHelper::getPageNameByConst(PageAdminHelper::PAGE_LISTS),
                "action"  => "subscribers",
                "list_id" => $listId
            ),
            admin_url("admin.php")
        );

    }

    /**
     * 
     * @param int $listId
     * @return string
     */
    public function getCreateUrl($listId, $subscriberId = null){

        return add_query_arg(
            array(
                "page"          => PageAdminHelper::getPageNameByConst(PageAdminHelper::PAGE_LISTS),
                "action"        => "subscribers-create",
                "list_id"       => $listId,
                "subscriber_id" => $subscriberId
            ),
            admin_url("admin.php")
        );

    }

    /**
     * 
     * @param int $listId
     * @return string
     */
    public function getPageSubscribersImport($listId = null, $step = 1){

        return add_query_arg(
            array(
                "page"          => PageAdminHelper::getPageNameByConst(PageAdminHelper::PAGE_LISTS),
                "action"        => "subscribers-import",
                "list_id"       => $listId,
                "step"          => $step
            ),
            admin_url("admin.php")
        );

    }


    /**
     * 
     * @param int $subscriberId
     * @param int $listId
     * @return string
     */
    public function getDeleteSubscriberFromList($subscriberId, $listId){
        return wp_nonce_url(
            admin_url( 
                sprintf(
                    'admin-post.php?action=%s&subscriber_id=%s&list_id=%s',
                    ActionHelper::DELETE_SUBSCRIBER_LIST,
                    $subscriberId, 
                    $listId
                )
            ),
            ActionHelper::DELETE_SUBSCRIBER_LIST
        );
    }
        

    /**
     * 
     * @param int $listId
     * @return string
     */
    public function getDeleteSubscribersFromList($listId){
        return wp_nonce_url( 
            add_query_arg(
                array(
                    "action"  => ActionHelper::DELETE_SUBSCRIBERS_LIST,
                    "list_id" => $listId
                ),
                admin_url("admin-post.php")
            ),
            ActionHelper::DELETE_SUBSCRIBERS_LIST
        );
    }
        



}









