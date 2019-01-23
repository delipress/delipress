<?php

namespace Delipress\WordPress\Admin;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\HooksInterface;
use DeliSkypress\Models\ContainerInterface;
use DeliSkypress\WordPress\Actions\AbstractHook;

use Delipress\WordPress\Helpers\ActionHelper;
use Delipress\WordPress\Helpers\ProviderHelper;
use Delipress\WordPress\Helpers\CodeErrorHelper;
use Delipress\WordPress\Helpers\AdminNoticesHelper;


/**
 * ImportProvider
 *
 * @author DeliPress
 * @version 1.0.0
 * @since 1.0.0
 */
class ImportProvider extends AbstractHook implements HooksInterface {

    /**
     *  @param ContainerInterface $containerServices
     *  @see AbstractHook
     */
    public function setContainerServices(ContainerInterface $containerServices){
        $this->providerServices = $containerServices->getService("ProviderServices");
        $this->optionServices   = $containerServices->getService("OptionServices");
        $this->listServices     = $containerServices->getService("ListServices");
    
    }

    /**
     * @see HooksInterface
     */
    public function hooks(){
        if(current_user_can('manage_options' ) ){
            add_action( 'admin_post_' . ActionHelper::IMPORT_LISTS_PROVIDER, array($this, 'importData') );

        }
    }

    /**
     * @return void
     */
    public function importData(){
        
        if ( 
            ! isset( $_GET['_wpnonce'] ) || 
            ! wp_verify_nonce( $_GET['_wpnonce'], ActionHelper::IMPORT_LISTS_PROVIDER ) ||
            $_SERVER["REQUEST_METHOD"] !== "POST" 
        ) {
            wp_nonce_ays( '' );
        }

        $provider = ProviderHelper::getProviderFromUrl();


        $response = $this->providerServices
                         ->getProviderImport($provider["key"])
                         ->importLists();

        if($response["success"]){
            AdminNoticesHelper::registerSuccess(
                CodeErrorHelper::ADMIN_NOTICE,
                __("List import in progress", "delipress") 
            );
        }

        $url = $this->listServices->getPageListUrl();       
        wp_redirect($url);
        exit;
    } 



}









