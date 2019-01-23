<?php

namespace Delipress\WordPress\Admin\Wizard;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\HooksInterface;
use DeliSkypress\Models\ContainerInterface;
use DeliSkypress\WordPress\Actions\AbstractHook;

use Delipress\WordPress\Helpers\ActionHelper;
use Delipress\WordPress\Helpers\AdminNoticesHelper;
use Delipress\WordPress\Helpers\CodeErrorHelper;

/**
 * WizardRemovePage
 *
 * @author DeliPress
 * @version 1.0.0
 * @since 1.0.0
 */
class WizardRemovePage extends AbstractHook implements HooksInterface {

    /**
     *  @param ContainerInterface $containerServices
     *  @see AbstractHook
     */
    public function setContainerServices(ContainerInterface $containerServices){
        $this->optionServices           = $containerServices->getService("OptionServices");
    }

    /**
     * @see HooksInterface
     */
    public function hooks(){
        if(current_user_can('manage_options' ) ){
            add_action( 'admin_post_' . ActionHelper::REMOVE_SETUP_WIZARD_PAGE, array($this, 'removePageWizard') );
        }
    }

    /**
     * Remove wizard with an action
     *
     * @return void
     */
    public function removePageWizard(){

        if ( 
            ! isset( $_GET['_wpnonce'] ) || 
            ! wp_verify_nonce( $_GET['_wpnonce'], ActionHelper::REMOVE_SETUP_WIZARD_PAGE )
        ) {
            wp_nonce_ays( '' );
        }

        $args = array(
            "others" => array(
                "show_setup" => false
            )
        );

        $this->optionServices->setOptions($args);
        
        AdminNoticesHelper::registerSuccess(
            CodeErrorHelper::ADMIN_NOTICE,
            __("Wizard successfully removed", "delipress")
        );

        wp_redirect($this->optionServices->getPageUrl("others"));
        exit;
       
    } 


}

