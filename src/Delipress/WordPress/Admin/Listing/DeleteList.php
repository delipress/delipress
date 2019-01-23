<?php

namespace Delipress\WordPress\Admin\Listing;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\HooksInterface;
use DeliSkypress\Models\ContainerInterface;
use DeliSkypress\WordPress\Actions\AbstractHook;

use Delipress\WordPress\Helpers\ActionHelper;

/**
 * DeleteList
 *
 * @author DeliPress
 * @version 1.0.0
 * @since 1.0.0
 */
class DeleteList extends AbstractHook implements HooksInterface {

    /**
     *  @param ContainerInterface $containerServices
     *  @see AbstractHook
     */
    public function setContainerServices(ContainerInterface $containerServices){
        $this->deleteListServices   = $containerServices->getService("DeleteListServices");
        $this->listServices         = $containerServices->getService("ListServices");
    }

    /**
     * @see HooksInterface
     */
    public function hooks(){
        if(current_user_can('manage_options' ) ){
            add_action( 'admin_post_' . ActionHelper::DELETE_LIST, array($this, 'deleteList') );
            add_action( 'admin_post_' . ActionHelper::DELETE_LISTS, array($this, 'deleteLists') );
        }
    }

    /**
     * @see admin_post_ ActionHelper::DELETE_LIST
     * 
     * @return void
     */
    public function deleteList(){
        if ( 
            ! isset( $_GET['_wpnonce'] ) || 
            ! wp_verify_nonce( $_GET['_wpnonce'], ActionHelper::DELETE_LIST ) ||
            $_SERVER["REQUEST_METHOD"] !== "GET"

        ) {
            wp_nonce_ays( '' );
        }
        
        $response = $this->deleteListServices->deleteList();

        $url      = $this->listServices->getPageListUrl();

        wp_redirect($url);
        exit;
    } 

    /**
     * @see admin_post_ ActionHelper::DELETE_LISTS
     * 
     * @return void
     */
    public function deleteLists(){
        if ( 
            ! isset( $_GET['_wpnonce'] ) || 
            ! wp_verify_nonce( $_GET['_wpnonce'], ActionHelper::DELETE_LISTS ) ||
            $_SERVER["REQUEST_METHOD"] !== "POST"

        ) {
            wp_nonce_ays( '' );
        }

        $response = $this->deleteListServices->deleteLists();
        
        $url      = $this->listServices->getPageListUrl();

        wp_redirect($url);
        exit;
    }



}









