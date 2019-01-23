<?php

namespace Delipress\WordPress\Services\Listing;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\ServiceInterface;
use DeliSkypress\Models\MediatorServicesInterface;

use Delipress\WordPress\Models\OptinModel;
use Delipress\WordPress\Helpers\PostTypeHelper;


/**
 * ListSubscriberServices
 *
 * @author DeliPress
 * @version 1.0.0
 * @since 1.0.0
 */
class ListSubscriberServices implements ServiceInterface, MediatorServicesInterface {

    /**
     * @see MediatorServicesInterface
     * 
     * @param array $services
     * @return void
     */
    public function setServices($services){
        $this->createSubscriberServices          = $services["CreateSubscriberServices"];

    }

    /**
     * @action DELIPRESS_SLUG . "_before_add_subscriber_from_optin"
     * @action DELIPRESS_SLUG . "_after_add_subscriber_from_optin"
     * 
     * @param int $idOptin
     * @param array $params
     * 
     * @return array
     */
    public function addSubscriberFromOptin($idOptin, $params){

        $optin = get_post($idOptin);
        if($optin->post_type !== PostTypeHelper::CPT_OPTINFORMS){
            return array(
                "success" => false
            );
        }

        $optinModel = new OptinModel();
        $optinModel->setOptin($optin);

        
        do_action(DELIPRESS_SLUG . "_before_add_subscriber_from_optin", $optin, $params);

        $this->createSubscriberServices->createSubscriberOnListsFromOptin($optinModel, $params);

        do_action(DELIPRESS_SLUG . "_after_add_subscriber_from_optin", $optin, $params);
    
        return array(
            "success" => true 
        );
    }

}









