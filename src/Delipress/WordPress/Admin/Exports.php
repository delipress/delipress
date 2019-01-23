<?php

namespace Delipress\WordPress\Admin;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\HooksAdminInterface;
use DeliSkypress\Models\ContainerInterface;
use DeliSkypress\WordPress\Actions\AbstractHook;

use Delipress\WordPress\Helpers\PageAdminHelper;
use Delipress\WordPress\Helpers\ActionHelper;
use Delipress\WordPress\Strategy\ExportJsonStrategy;

/**
 * Exports
 *
 * @author Delipress
 * @version 1.0.0
 * @since 1.0.0
 */
class Exports extends AbstractHook implements HooksAdminInterface{

    /**
     *  @param ContainerInterface $containerServices
     */
    public function setContainerServices(ContainerInterface $containerServices){
        $this->exportServices    = $containerServices->getService("ExportServices");
    }

    
    /**
     * @see HooksAdminInterface
     */
    public function hooks(){

        if(current_user_can('manage_options' ) ){
            add_action( 'admin_post_' . ActionHelper::LIST_EXPORT, array($this, 'adminPostListExport') );
        }
    }

    /**
     * @action DELIPRESS_SLUG . '_before_export_' . ActionHelper::LIST_EXPORT
     * @action DELIPRESS_SLUG . '_after_export_' . ActionHelper::LIST_EXPORT
     * @return void
     */
    public function adminPostListExport(){
        if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( $_GET['_wpnonce'], ActionHelper::LIST_EXPORT ) ) {
            wp_nonce_ays( '' );
        }

        $strategy = new ExportJsonStrategy();
        
        do_action(DELIPRESS_SLUG . '_before_export_' . ActionHelper::LIST_EXPORT);

        $this->exportServices
             ->setStrategy($strategy)
             ->setTypeExport(ActionHelper::LIST_EXPORT)
             ->execute();
             
        do_action(DELIPRESS_SLUG . '_after_export_' . ActionHelper::LIST_EXPORT);
        
        exit();
    }

}









