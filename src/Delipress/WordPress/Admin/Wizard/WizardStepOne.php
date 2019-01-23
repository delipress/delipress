<?php

namespace Delipress\WordPress\Admin\Wizard;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\HooksInterface;
use DeliSkypress\Models\ContainerInterface;
use DeliSkypress\WordPress\Actions\AbstractHook;

use Delipress\WordPress\Helpers\OptionHelper;
use Delipress\WordPress\Helpers\PageAdminHelper;
use Delipress\WordPress\Helpers\ProviderHelper;
use Delipress\WordPress\Services\Wizard\WizardServices;

use Delipress\WordPress\Helpers\ActionHelper;

/**
 * WizardStepOne
 *
 * @author DeliPress
 * @version 1.0.0
 * @since 1.0.0
 */
class WizardStepOne extends AbstractHook implements HooksInterface {

    /**
     *  @param ContainerInterface $containerServices
     *  @see AbstractHook
     */
    public function setContainerServices(ContainerInterface $containerServices){
        $this->wizardServices           = $containerServices->getService("WizardServices");
        $this->wizardStepOneServices    = $containerServices->getService("WizardStepOneServices");
        $this->optionServices           = $containerServices->getService("OptionServices");
    }

    /**
     * @see HooksInterface
     */
    public function hooks(){
        add_action( 'admin_post_' . ActionHelper::WIZARD_STEP_1_CONNECT, array($this, 'connectProvider') );
        add_action( 'admin_post_' . ActionHelper::WIZARD_STEP_1_SEND, array($this, 'sendProvider') );
    }

    /**
     * @see 'admin_post_' . ActionHelper::WIZARD_STEP_1_CONNECT
     *
     * @return void
     */
    public function connectProvider(){
        if ( 
            ! isset( $_GET['_wpnonce'] ) || 
            ! wp_verify_nonce( $_GET['_wpnonce'], ActionHelper::WIZARD_STEP_1_CONNECT ) ||
            $_SERVER["REQUEST_METHOD"] !== "POST" ||
            ! isset( $_POST["provider"])
        ) {
            wp_nonce_ays( '' );
        }
        
        $provider = sanitize_text_field($_POST["provider"]);

        $provider = ProviderHelper::getProviderFromPostRequest();
        
        $response = $this->wizardStepOneServices->connectProvider($provider["key"]);

        if($response["success"]){
            $url = $this->wizardServices->getPageWizard(1, array(
                    "provider" => $provider["key"],
                    "tab"      => "send"
                )
            );
        }
        else{
            $url = $this->wizardServices->getPageWizard(1, array(
                    "provider" => $provider["key"]
                )
            );
        }

        wp_redirect($url);
        exit;
       
    } 

    /**
     * @see 'admin_post_' . ActionHelper::WIZARD_STEP_1_SEND
     *
     * @return void
     */
    public function sendProvider(){

        if ( 
            ! isset( $_GET['_wpnonce'] ) || 
            ! wp_verify_nonce( $_GET['_wpnonce'], ActionHelper::WIZARD_STEP_1_SEND ) ||
            $_SERVER["REQUEST_METHOD"] !== "POST" ||
            ! isset( $_POST["provider"])
        ) {
            wp_nonce_ays( '' );
        } 
        
        $provider = ProviderHelper::getProviderFromPostRequest();

        $response = $this->wizardStepOneServices->sendProvider($provider["key"]);

        if($response["success"]){
            $url = $this->wizardServices->getPageWizard(1, array(
                    "provider" => $provider["key"],
                    "tab"      => "finish"
                )
            );
        }
        else{
            $url = $this->wizardServices->getPageWizard(1, array(
                    "provider" => $provider["key"],
                    "tab"      => "send"
                )
            );
        }

        wp_redirect($url);
        exit;
    }


}

