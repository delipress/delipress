<?php

namespace Delipress\WordPress\Helpers;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use Delipress\WordPress\Models\CampaignModel;
use Delipress\WordPress\Models\ListModel;
use Delipress\WordPress\Models\OptinModel;
use Delipress\WordPress\Models\SubscriberModel;

/**
 *
 * @author Delipress
 * @version 1.0.0
 * @since 1.0.0
 */
abstract class PrepareModelHelper{

    /**
     * @static
     *
     * @return CampaignModel
     */
    public static function getCampaignFromUrl(){
        $campaign = new CampaignModel();

        if( isset( $_GET["campaign_id"]) ){
            $id = (int) $_GET["campaign_id"];
            $campaign->setCampaignById( $id );
        }

        return $campaign;
    }

    /**
     * @static
     *
     * @return OptinModel
     */
    public static function getOptinFromUrl(){
        $optin = new OptinModel();

        if( isset( $_GET["optin_id"]) ){
            $id = (int) $_GET["optin_id"];
            $optin->setOptinById( $id );
        }

        return $optin;
    }

    /**
     * @static
     *
     * @return ListInterface
     */
    public static function getListFromUrl(){
        if( !isset( $_GET["list_id"]) ){
            return new ListModel();
        }

        $id = $_GET["list_id"];

        global $delipressPlugin;
        
        $list    = $delipressPlugin->getService("ListServices")->getList($id);

        return $list;
    }

    /**
     * @static
     *
     * @return SubscriberInterface
     */
    public static function getSubscriberFromUrl(){
        if( !isset( $_GET["subscriber_id"]) ){
            return new SubscriberModel();
        }   
        
        $listId = null;

        if( isset( $_GET["list_id"]) ){
            $listId = $_GET["list_id"];
        }

        $id = $_GET["subscriber_id"];

        global $delipressPlugin;

        if($listId){
            $subscriber    = $delipressPlugin->getService("SubscriberServices")->getSubscriberByList($listId, $id);
        }
        else{
            $subscriber    = $delipressPlugin->getService("SubscriberServices")->getSubscriber($id);
        }

        return $subscriber;
    }

}









