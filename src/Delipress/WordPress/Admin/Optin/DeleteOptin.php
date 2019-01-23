<?php

namespace Delipress\WordPress\Admin\Optin;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\HooksInterface;
use DeliSkypress\Models\ContainerInterface;
use DeliSkypress\WordPress\Actions\AbstractHook;

use Delipress\WordPress\Helpers\ActionHelper;

/**
 * DeleteOptin
 *
 * @author DeliPress
 * @version 1.0.0
 * @since 1.0.0
 */
class DeleteOptin extends AbstractHook implements HooksInterface {

    /**
     *  @param ContainerInterface $containerServices
     *  @see AbstractHook
     */
    public function setContainerServices(ContainerInterface $containerServices){
        $this->deleteOptinServices   = $containerServices->getService("DeleteOptinServices");
        $this->optinServices         = $containerServices->getService("OptinServices");
    }

    /**
     * @see HooksInterface
     */
    public function hooks(){
        if(current_user_can('manage_options' ) ){
            add_action( 'admin_post_' . ActionHelper::DELETE_OPTIN, array($this, 'deleteOptin') );
            add_action( 'admin_post_' . ActionHelper::DELETE_OPTINS, array($this, 'deleteOptins') );
        }
    }

   /**
    * @see 'admin_post_' . ActionHelper::DELETE_OPTIN
    *
    * @return void
    */
    public function deleteOptin(){

        if ( 
            ! isset( $_GET['_wpnonce'] ) || 
            ! wp_verify_nonce( $_GET['_wpnonce'], ActionHelper::DELETE_OPTIN ) ||
            $_SERVER["REQUEST_METHOD"] !== "GET" ||
            !isset( $_GET["optin_id"] )

        ) {
            wp_nonce_ays( '' );
        }

        $response = $this->deleteOptinServices->deleteOptin();

        $url      = $this->optinServices->getPageUrl();

        wp_redirect($url);
        exit;
    }

   /**
    * @see 'admin_post_' . ActionHelper::DELETE_OPTINS
    *
    * @return void
    */
    public function deleteOptins(){

        if ( 
            ! isset( $_GET['_wpnonce'] ) || 
            ! wp_verify_nonce( $_GET['_wpnonce'], ActionHelper::DELETE_OPTINS ) ||
            $_SERVER["REQUEST_METHOD"] !== "POST"

        ) {
            wp_nonce_ays( '' );
        }

        $type = "inactive";
        if(isset($_GET["type"]) && $_GET["type"] === "active"){
            $type = "active";
        }

        $response = $this->deleteOptinServices->deleteOptins($type);

        $url      = $this->optinServices->getPageUrl();

        wp_redirect($url);
        exit;
    }


}









