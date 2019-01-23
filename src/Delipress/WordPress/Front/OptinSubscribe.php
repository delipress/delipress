<?php

namespace Delipress\WordPress\Front;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\HooksInterface;
use DeliSkypress\Models\ContainerInterface;
use DeliSkypress\WordPress\Actions\AbstractHook;
/**
 * OptinSubscribe
 *
 * @author DeliPress
 * @version 1.0.0
 * @since 1.0.0
 */
class OptinSubscribe extends AbstractHook implements HooksInterface {

    /**
     *  @param ContainerInterface $containerServices
     *  @see AbstractHook
     */
    public function setContainerServices(ContainerInterface $containerServices){
        $this->listSubscriberServices    = $containerServices->getService("ListSubscriberServices");
    }

    /**
     * @see HooksFrontInterface
     */
    public function hooks(){
        add_action( 'wp_ajax_nopriv_delipress_subscriber_on_list', array($this, 'subscribeOnList') );
        add_action( 'wp_ajax_delipress_subscriber_on_list', array($this, 'subscribeOnList') );
    }

    public function subscribeOnList(){
         if(
            $_SERVER["REQUEST_METHOD"] !== "POST" ||
            !isset($_POST["email"]) ||
            !isset($_POST["id"])
        ){
             wp_send_json_error(
                 array(
                    "code" => "not_allowed"
                ) 
            );
        }

        $idOptin = (int) $_POST["id"];
        $params = array(
            "email" => sanitize_email( $_POST["email"] ),
            "metas" => array(
                "first_name" => (isset($_POST["first_name"])) ? sanitize_text_field($_POST["first_name"]) : "",
                "last_name"  => (isset($_POST["last_name"])) ? sanitize_text_field($_POST["last_name"]) : ""
            )
        );
        $response = $this->listSubscriberServices->addSubscriberFromOptin($idOptin, $params);

        wp_send_json($response);
    }

}