<?php

namespace Delipress\WordPress\Admin\Campaign;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\HooksInterface;
use DeliSkypress\Models\ContainerInterface;
use DeliSkypress\WordPress\Actions\AbstractHook;

use Delipress\WordPress\Helpers\ActionHelper;
use Delipress\WordPress\Models\CampaignModel;

/**
 * SendCampaign
 *
 * @author DeliPress
 * @version 1.0.0
 * @since 1.0.0
 */
class SendCampaign extends AbstractHook implements HooksInterface {

    /**
     *  @param ContainerInterface $containerServices
     *  @see AbstractHook
     */
    public function setContainerServices(ContainerInterface $containerServices){
        $this->campaignServices        = $containerServices->getService("CampaignServices");
        $this->sendCampaignServices    = $containerServices->getService("SendCampaignServices");
    }

    /**
     * @see HooksInterface
     */
    public function hooks(){
        add_action( 'admin_post_' . ActionHelper::SEND_CAMPAIGN, array($this, 'adminPostSendCampaign') );
        add_action( 'wp_ajax_' . ActionHelper::CREATE_CAMPAIGN_SEND_TEST, array($this, 'ajaxSendTestCampaign') );
    }

    /**
     * @see wp_ajax_' . ActionHelper::CREATE_CAMPAIGN_SEND_TEST
     * 
     * @return JSON
     */
    public function ajaxSendTestCampaign(){
        if(
            $_SERVER["REQUEST_METHOD"] !== "POST" ||
            !isset($_POST["campaign_id"]) ||
            !isset($_POST["send_to"])
        ){
             wp_send_json_error(
                 array(
                    "code" => "not_allowed"
                ) 
            );
        }

        $response = $this->sendCampaignServices->sendTestCampaign();

        wp_send_json($response);
    }

    /**
     * @return void
     */
    public function adminPostSendCampaign(){

         if (   ! isset( $_GET['_wpnonce'] ) || 
                ! wp_verify_nonce( $_GET['_wpnonce'], ActionHelper::SEND_CAMPAIGN )  ||
                ! isset( $_GET["campaign_id"] )
        ) {
            wp_nonce_ays( '' );
        }

        $campaign = new CampaignModel();
        $campaign->setCampaignById((int) $_GET["campaign_id"]);


        $response = $this->sendCampaignServices->sendCampaign($campaign);
          
        if($response["success"]){
            $url = $this->campaignServices->getPageUrl();
        }
        else{ 
            $url = $this->campaignServices->getCreateUrlByNextStep(4, $campaign->getId());
        }

        wp_redirect($url);
        exit;
        
    }

 
}









