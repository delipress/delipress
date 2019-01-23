<?php

namespace Delipress\WordPress\Admin\Subscriber;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\HooksInterface;
use DeliSkypress\Models\ContainerInterface;
use DeliSkypress\WordPress\Actions\AbstractHook;

use Delipress\WordPress\Helpers\ActionHelper;

/**
 * CreateSubscriber
 *
 * @author DeliPress
 * @version 1.0.0
 * @since 1.0.0
 */
class CreateSubscriber extends AbstractHook implements HooksInterface {

    /**
     *  @param ContainerInterface $containerServices
     *  @see AbstractHook
     */
    public function setContainerServices(ContainerInterface $containerServices){
        $this->createSubscriberServices = $containerServices->getService("CreateSubscriberServices");
        $this->subscriberServices       = $containerServices->getService("SubscriberServices");
        $this->listServices             = $containerServices->getService("ListServices");
    }

    /**
     * @see HooksInterface
     */
    public function hooks(){
        if(current_user_can('manage_options' ) ){
            add_action( 'admin_post_' . ActionHelper::CREATE_SUBSCRIBER, array($this, 'createSubscriber') );
        }
    }

    
    /**
     * @see admin_post_' . ActionHelper::CREATE_SUBSCRIBER
     * 
     * @return void
     */
    public function createSubscriber(){

        if ( 
            ! isset( $_GET['_wpnonce'] ) || 
            ! wp_verify_nonce( $_GET['_wpnonce'], ActionHelper::CREATE_SUBSCRIBER ) ||
            $_SERVER["REQUEST_METHOD"] !== "POST" ||
            ! isset( $_POST["list_id"] )

        ) {
            wp_nonce_ays( '' );
        }

        $subscriberId = isset($_POST["subscriber_id"]) ? $_POST["subscriber_id"] : null;
        $listId       = $_POST["list_id"];

        if(isset( $subscriberId ) ) {
            $result = $this->createSubscriberServices->editSusbcriberOnList();
        }
        else{
            $result = $this->createSubscriberServices->createSubscriberOnList();
        }
        
        
        if(!$result["success"]){
            $url = $this->subscriberServices->getCreateUrl( $listId, $subscriberId);
        }
        else{
            $url = $this->listServices->getSinglePageListUrl( $listId);
        }

        wp_redirect($url);
        exit;
    } 



}









