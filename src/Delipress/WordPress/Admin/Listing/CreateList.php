<?php

namespace Delipress\WordPress\Admin\Listing;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\HooksInterface;
use DeliSkypress\Models\ContainerInterface;
use DeliSkypress\WordPress\Actions\AbstractHook;

use Delipress\WordPress\Helpers\ActionHelper;

/**
 * CreateList
 *
 * @author DeliPress
 * @version 1.0.0
 * @since 1.0.0
 */
class CreateList extends AbstractHook implements HooksInterface {

    /**
     *  @param ContainerInterface $containerServices
     *  @see AbstractHook
     */
    public function setContainerServices(ContainerInterface $containerServices){
        $this->createListServices = $containerServices->getService("CreateListServices");
        $this->listServices       = $containerServices->getService("ListServices");
    }

    /**
     * @see HooksInterface
     */
    public function hooks(){
        if(current_user_can('manage_options' ) ){
            add_action( 'admin_post_' . ActionHelper::CREATE_LIST, array($this, 'createList') );
        }
    }

   
    public function createList(){
        if ( 
            ! isset( $_GET['_wpnonce'] ) || 
            ! wp_verify_nonce( $_GET['_wpnonce'], ActionHelper::CREATE_LIST ) ||
            $_SERVER["REQUEST_METHOD"] !== "POST" 
        ) {
            wp_nonce_ays( '' );
        }

        if(isset( $_POST["list_id"] ) ) {
            $response = $this->createListServices->editList();
        }
        else{
            $response = $this->createListServices->createList();
        }
        
        if(!$response["success"]){
            if(isset( $_POST["list_id"] ) ) {
                $url = $this->listServices->getEditUrl( $_POST["list_id"]);
            }
            else{
                $url = $this->listServices->getCreateUrl();
            }
        }
        else{
            $url = $this->listServices->getPageListUrl();
        }

        wp_redirect($url);
        exit;
    } 



}









