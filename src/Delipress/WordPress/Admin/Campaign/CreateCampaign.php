<?php

namespace Delipress\WordPress\Admin\Campaign;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\HooksInterface;
use DeliSkypress\Models\ContainerInterface;
use DeliSkypress\WordPress\Actions\AbstractHook;

use Delipress\WordPress\Helpers\OptionHelper;
use Delipress\WordPress\Helpers\PageAdminHelper;
use Delipress\WordPress\Helpers\CodeErrorHelper;
use Delipress\WordPress\Helpers\AdminNoticesHelper;
use Delipress\WordPress\Helpers\ActionHelper;

/**
 * CreateCampaign
 *
 * @author DeliPress
 * @version 1.0.0
 * @since 1.0.0
 */
class CreateCampaign extends AbstractHook implements HooksInterface {

    /**
     *  @param ContainerInterface $containerServices
     *  @see AbstractHook
     */
    public function setContainerServices(ContainerInterface $containerServices){
        $this->createCampaignServices    = $containerServices->getService("CreateCampaignServices");
        $this->campaignServices          = $containerServices->getService("CampaignServices");
    }

    /**
     * @see HooksInterface
     */
    public function hooks(){
        if(current_user_can('manage_options' ) ){
            add_action( 'admin_post_' . ActionHelper::CREATE_CAMPAIGN_STEP_ONE, array($this, 'createCampaignStepOne') );
            add_action( 'admin_post_' . ActionHelper::CREATE_CAMPAIGN_STEP_TWO, array($this, 'createCampaignStepTwo') );
            add_action( 'admin_post_' . ActionHelper::CREATE_CAMPAIGN_STEP_THREE, array($this, 'createCampaignStepThree') );
            add_action( 'admin_post_' . ActionHelper::CREATE_CAMPAIGN_STEP_FOUR, array($this, 'createCampaignStepFour') );
        }
    }

    /**
     * @see admin_post_' . ActionHelper::CREATE_CAMPAIGN_STEP_ONE
     * @return void
     */
    public function createCampaignStepOne(){
        if(
            $_SERVER["REQUEST_METHOD"] !== "POST" ||
            !isset($_GET["_wpnonce"]) ||
            !isset($_POST["next_step"]) ||
            ! wp_verify_nonce( $_GET['_wpnonce'], ActionHelper::CREATE_CAMPAIGN_STEP_ONE )
        ){
            wp_nonce_ays( '' );
        }

        $campaignId = ( isset($_POST["campaign_id"] ) ) ? (int) $_POST["campaign_id"] : null;
        $nextStep   = (isset($_POST["next_step"]) ) ? (int) $_POST["next_step"] : 2;
        $response   = $this->createCampaignServices->createCampaignStepOne($campaignId);

        if($response["success"]){
            $url = $this->campaignServices->getCreateUrlByNextStep($nextStep, $response["results"]["campaign_id"]);
        }
        else{
            $url = $this->campaignServices->getCreateUrlByNextStep(1, $response["results"]["campaign_id"]);
        }

        wp_redirect($url);
        exit;

    }

    /**
     * @see admin_post_' . ActionHelper::CREATE_CAMPAIGN_STEP_TWO
     * @return void
     */
    public function createCampaignStepTwo(){
        if(
            $_SERVER["REQUEST_METHOD"] !== "POST" ||
            !isset($_GET["_wpnonce"]) ||
            !isset($_POST["next_step"]) ||
            !isset($_POST["campaign_id"]) ||
            ! wp_verify_nonce( $_GET['_wpnonce'], ActionHelper::CREATE_CAMPAIGN_STEP_TWO )
        ){
            wp_nonce_ays( '' );
        }

        $campaignId = ( isset($_POST["campaign_id"] ) ) ? (int) $_POST["campaign_id"] : null;
        $nextStep   = (isset($_POST["next_step"]) ) ? (int) $_POST["next_step"] : 3;
        $from       = (isset($_GET["from"]) ) ? $_GET["from"] : "";

        $response   = $this->createCampaignServices->createCampaignStepTwo($campaignId, $from);

        $url = $this->campaignServices->getCreateUrlByNextStep($nextStep, $campaignId);
        if(!$response["success"]){
            $url = $this->campaignServices->getCreateUrlByNextStep(2, $campaignId);
        }


        wp_redirect($url);
        exit;

    }

    /**
     * Create campaign step two
     * @see admin_post_' . ActionHelper::CREATE_CAMPAIGN_STEP_THREE
     * @return void
     */
    public function createCampaignStepThree(){
        if(
            $_SERVER["REQUEST_METHOD"] !== "POST" ||
            !isset($_GET["_wpnonce"]) ||
            ! wp_verify_nonce( $_GET['_wpnonce'], ActionHelper::CREATE_CAMPAIGN_STEP_THREE ) ||
            !isset($_POST["next_step"]) ||
            !isset($_POST["campaign_id"])
        ){
            wp_nonce_ays( '' );
        }

        $nextStep   = (int) $_POST["next_step"];
        $campaignId = (int) $_POST["campaign_id"];

        $url = $this->campaignServices->getCreateUrlByNextStep($nextStep, $campaignId);
        wp_redirect($url);
        exit;
    }



    /**
     * @return void
     */
    public function createCampaignStepFour(){
        if(
            $_SERVER["REQUEST_METHOD"] !== "POST" ||
            !isset($_GET["_wpnonce"]) ||
            ! wp_verify_nonce( $_GET['_wpnonce'], ActionHelper::CREATE_CAMPAIGN_STEP_FOUR ) ||
            !isset($_POST["next_step"]) ||
            !isset($_POST["campaign_id"])
        ){
            wp_nonce_ays( '' );
        }

        $campaignId = (int) $_POST["campaign_id"];
        $nextStep   = (isset($_POST["next_step"]) ) ? (int) $_POST["next_step"] : 4;

        $url        = $this->campaignServices->getCreateUrlByNextStep($nextStep, $campaignId);

        if($nextStep == 4){
            $url = $this->campaignServices->getPageUrl();
            AdminNoticesHelper::registerSuccess(
                CodeErrorHelper::ADMIN_NOTICE,
                __("Campaign saved", "delipress")
            );
        }

        wp_redirect($url);
        exit;
    }



}
