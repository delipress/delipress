<?php

namespace Delipress\WordPress\Admin\Optin;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\HooksInterface;
use DeliSkypress\Models\ContainerInterface;
use DeliSkypress\WordPress\Actions\AbstractHook;

use Delipress\WordPress\Helpers\PageAdminHelper;
use Delipress\WordPress\Helpers\ActionHelper;
use Delipress\WordPress\Helpers\OptinHelper;
use Delipress\WordPress\Helpers\AdminNoticesHelper;
use Delipress\WordPress\Helpers\CodeErrorHelper;

/**
 * CreateOptin
 *
 * @author DeliPress
 * @version 1.0.0
 * @since 1.0.0
 */
class CreateOptin extends AbstractHook implements HooksInterface {

    /**
     *  @param ContainerInterface $containerServices
     *  @see AbstractHook
     */
    public function setContainerServices(ContainerInterface $containerServices){
        $this->createOptinServices = $containerServices->getService("CreateOptinServices");
        $this->optinServices       = $containerServices->getService("OptinServices");
    }

    /**
     * @see HooksInterface
     */
    public function hooks(){
        if(current_user_can('manage_options' ) ){
            add_action( 'admin_post_' . ActionHelper::CREATE_OPTIN, array($this, 'createOptin') );
            add_action( 'admin_post_' . ActionHelper::CREATE_OPTIN_STEP_TWO, array($this, 'createOptinStepTwo') );
            add_action( 'admin_post_' . ActionHelper::CREATE_OPTIN_STEP_THREE, array($this, 'createOptinStepThree') );
        }
    }


    /**
     * @see admin_post_' . ActionHelper::CREATE_OPTIN
     *
     * @return void
     */
    public function createOptin(){
        if (
            ! isset( $_GET['_wpnonce'] ) ||
            ! wp_verify_nonce( $_GET['_wpnonce'], ActionHelper::CREATE_OPTIN ) ||
            $_SERVER["REQUEST_METHOD"] !== "POST" ||
            ! isset( $_POST["next_step"] )
        ) {
            wp_nonce_ays( '' );
        }

        $optinId = (isset( $_POST["optin_id"] )) ? (int) $_POST["optin_id"] : null;
        $nextStep = (int) $_POST["next_step"];

        $response = $this->createOptinServices->createOptin($optinId);

        $url = $this->optinServices->getPageUrl();
        if($nextStep != 1){
            if(!$response["success"]){
                $url = $this->optinServices->getCreateUrl(1, $optinId);
            }
            else{
                $optinHelp = OptinHelper::getOptinByKey($response["results"]["type"]);
                $nextStep = 1;
                if($optinHelp["steps"]["step2"]){
                    $nextStep = 2;
                }
                else if($optinHelp["steps"]["step3"]){
                    $nextStep = 3;
                }

                if($nextStep != 1){
                    $url       = $this->optinServices->getCreateUrl($nextStep, $response["results"]["optin_id"]);
                }
            }
        } else {
            $url = $this->optinServices->getCreateUrl(1, $optinId);
        }

        wp_redirect($url);
        exit;
    }

    /**
     * @see admin_post_' . ActionHelper::CREATE_OPTIN_STEP_TWO
     *
     * @return void
     */
    public function createOptinStepTwo(){
        if (
            ! isset( $_GET['_wpnonce'] ) ||
            ! wp_verify_nonce( $_GET['_wpnonce'], ActionHelper::CREATE_OPTIN_STEP_TWO ) ||
            $_SERVER["REQUEST_METHOD"] !== "POST" ||
            ! isset( $_POST["next_step"] )
        ) {
            wp_nonce_ays( '' );
        }

        $optinId  = (isset( $_POST["optin_id"] )) ? (int) $_POST["optin_id"] : null;
        $nextStep = (isset( $_POST["next_step"]) ) ? (int) $_POST["next_step"] : 2;

        $this->createOptinServices->createOptinStepTwo($optinId);

        $url = $this->optinServices->getPageUrl();

        if($nextStep != 2){
            $url = $this->optinServices->getCreateUrl($nextStep, $optinId);
        }

        wp_redirect($url);
        exit;
    }
    /**
     * @see admin_post_' . ActionHelper::CREATE_OPTIN_STEP_THREE
     *
     * @return void
     */
    public function createOptinStepThree(){
        if (
            ! isset( $_GET['_wpnonce'] ) ||
            ! wp_verify_nonce( $_GET['_wpnonce'], ActionHelper::CREATE_OPTIN_STEP_THREE ) ||
            $_SERVER["REQUEST_METHOD"] !== "POST" ||
            ! isset( $_POST["next_step"] )
        ) {
            wp_nonce_ays( '' );
        }

        $optinId  = (isset( $_POST["optin_id"] )) ? (int) $_POST["optin_id"] : null;
        $nextStep = (isset( $_POST["next_step"]) ) ? (int) $_POST["next_step"] : 3;

        if($nextStep > 3){
            $nextStep = 3;
        }

        $result = $this->createOptinServices->createOptinStepThree($optinId);
        $url    = $this->optinServices->getPageUrl();

        if(!$result["success"]){
            $url = $this->optinServices->getCreateUrl(3, $optinId);
        }
        else if($nextStep < 3){
            $url = $this->optinServices->getCreateUrl($nextStep, $optinId);
        }
        else{
            AdminNoticesHelper::registerSuccess(
                CodeErrorHelper::ADMIN_NOTICE,
                CodeErrorHelper::getMessage(CodeErrorHelper::OPTIN_CREATED)
            );
        }

        wp_redirect($url);
        exit;
    }



}
