<?php

namespace Delipress\WordPress\Admin\Subscriber;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\HooksInterface;
use DeliSkypress\Models\ContainerInterface;
use DeliSkypress\WordPress\Actions\AbstractHook;

use Delipress\WordPress\Helpers\ActionHelper;

/**
 * DeleteSubscriber
 *
 * @author DeliPress
 * @version 1.0.0
 * @since 1.0.0
 */
class DeleteSubscriber extends AbstractHook implements HooksInterface {

    /**
     *  @param ContainerInterface $containerServices
     *  @see AbstractHook
     */
    public function setContainerServices(ContainerInterface $containerServices){
        $this->deleteSubscriberServices = $containerServices->getService("DeleteSubscriberServices");
        $this->listServices             = $containerServices->getService("ListServices");
    }

    /**
     * @see HooksInterface
     */
    public function hooks(){
        if(current_user_can('manage_options' ) ){
            add_action( 'admin_post_' . ActionHelper::DELETE_SUBSCRIBER_LIST, array($this, 'deleteSubscriberList') );
            add_action( 'admin_post_' . ActionHelper::DELETE_SUBSCRIBERS_LIST, array($this, 'deleteSubscribersList') );
        }
    }

    /**
     * @return void
     */
    public function deleteSubscriberList(){

        if ( 
            ! isset( $_GET['_wpnonce'] ) || 
            ! wp_verify_nonce( $_GET['_wpnonce'], ActionHelper::DELETE_SUBSCRIBER_LIST ) ||
            $_SERVER["REQUEST_METHOD"] !== "GET"

        ) {
            wp_nonce_ays( '' );
        }

        $response = $this->deleteSubscriberServices->deleteSubscriberFromList();

        $url      = $this->listServices->getSinglePageListUrl($response["results"]["list"]->getId());

        wp_redirect($url);
        exit;
    }
    
    /**
     * @return void
     */
    public function deleteSubscribersList(){

        if ( 
            ! isset( $_GET['_wpnonce'] ) || 
            ! wp_verify_nonce( $_GET['_wpnonce'], ActionHelper::DELETE_SUBSCRIBERS_LIST ) ||
            $_SERVER["REQUEST_METHOD"] !== "POST"

        ) {
            wp_nonce_ays( '' );
        }

        $response = $this->deleteSubscriberServices->deleteSubscribersFromList();

        $url      = $this->listServices->getSinglePageListUrl($response["results"]["list"]->getId());

        wp_redirect($url);
        exit;
    }



}









