<?php

namespace Delipress\WordPress\Admin\Listing;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\HooksInterface;
use DeliSkypress\Models\ContainerInterface;
use DeliSkypress\WordPress\Actions\AbstractHook;

use Delipress\WordPress\Helpers\ActionHelper;


class CreateDynamicList extends AbstractHook implements HooksInterface {

    /**
     *  @param ContainerInterface $containerServices
     *  @see AbstractHook
     */
    public function setContainerServices(ContainerInterface $containerServices){
        $this->listServices                = $containerServices->getService("ListServices");
        $this->createDynamicListServices   = $containerServices->getService("CreateDynamicListServices");
    }

    /**
     * @see HooksInterface
     */
    public function hooks(){
        add_action( 'admin_post_' . ActionHelper::CREATE_DYNAMIC_LIST, array($this, 'createDynamic') );
    }

   
    public function createDynamic(){
        if ( 
            ! isset( $_GET['_wpnonce'] ) || 
            ! wp_verify_nonce( $_GET['_wpnonce'], ActionHelper::CREATE_DYNAMIC_LIST ) ||
            $_SERVER["REQUEST_METHOD"] !== "POST" 
        ) {
            wp_nonce_ays( '' );
        }
        

        $result = $this->createDynamicListServices
                       ->createDynamicList($_POST["specification"]);


        $url = $this->listServices->getPageListUrl();
        wp_redirect($url);
        exit;
    } 



}









