<?php

namespace Delipress\WordPress\Admin\Wizard;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\HooksInterface;
use DeliSkypress\Models\ContainerInterface;
use DeliSkypress\WordPress\Actions\AbstractHook;

use Delipress\WordPress\Helpers\AdminNoticesHelper;
use Delipress\WordPress\Helpers\CodeErrorHelper;

use Delipress\WordPress\Services\Wizard\WizardServices;

use Delipress\WordPress\Helpers\ActionHelper;

/**
 * WizardStepTwo
 *
 * @author DeliPress
 * @version 1.0.0
 * @since 1.0.0
 */
class WizardStepTwo extends AbstractHook implements HooksInterface {

    /**
     *  @param ContainerInterface $containerServices
     *  @see AbstractHook
     */
    public function setContainerServices(ContainerInterface $containerServices){
        $this->wizardServices           = $containerServices->getService("WizardServices");
        $this->wizardStepTwoServices    = $containerServices->getService("WizardStepTwoServices");
    }

    /**
     * @see HooksInterface
     */
    public function hooks(){
        add_action( 'admin_post_' . ActionHelper::DP_REGISTER_NEWSLETTER, array($this, 'registerNewsletterDelipress') );
    }

    /**
     * @see 'admin_post_' . ActionHelper::DP_REGISTER_NEWSLETTER
     *
     * @return void
     */
    public function registerNewsletterDelipress(){
        if ( 
            ! isset( $_GET['_wpnonce'] ) || 
            ! wp_verify_nonce( $_GET['_wpnonce'], ActionHelper::DP_REGISTER_NEWSLETTER ) ||
            ! isset($_POST["email"])
        ) {
            wp_nonce_ays( '' );
        }


        $email = sanitize_email($_POST["email"]);

        $this->wizardStepTwoServices->registerNewsletterDelipress($email);
        
         AdminNoticesHelper::registerSuccess(
                CodeErrorHelper::ADMIN_NOTICE,
                __("Your subscription to our list has been confirmed", "delipress")
            );

        $url = $this->wizardServices->getPageSetupUrl();
        wp_redirect($url);
        exit;

    }

}
