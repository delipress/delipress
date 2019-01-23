<?php

namespace Delipress\WordPress\Admin\Campaign;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\HooksInterface;
use DeliSkypress\Models\ContainerInterface;
use DeliSkypress\WordPress\Actions\AbstractHook;

use Delipress\WordPress\Helpers\ActionHelper;

/**
 * DeleteCampaign
 *
 * @author DeliPress
 * @version 1.0.0
 * @since 1.0.0
 */
class DeleteCampaign extends AbstractHook implements HooksInterface {

    /**
     *  @param ContainerInterface $containerServices
     *  @see AbstractHook
     */
    public function setContainerServices(ContainerInterface $containerServices){
        $this->deleteCampaignServices = $containerServices->getService("DeleteCampaignServices");
        $this->campaignServices       = $containerServices->getService("CampaignServices");
    }

    /**
     * @see HooksInterface
     */
    public function hooks(){
        if(current_user_can('manage_options' ) ){
            add_action( 'admin_post_' . ActionHelper::DELETE_CAMPAIGN, array($this, 'deleteCampaign') );
            add_action( 'admin_post_' . ActionHelper::DELETE_CAMPAIGNS, array($this, 'deleteCampaigns') );
        }
    }

   
    public function deleteCampaign(){

        if ( 
            ! isset( $_GET['_wpnonce'] ) || 
            ! wp_verify_nonce( $_GET['_wpnonce'], ActionHelper::DELETE_CAMPAIGN ) ||
            $_SERVER["REQUEST_METHOD"] !== "GET" ||
            !isset( $_GET["campaign_id"] )

        ) {
            wp_nonce_ays( '' );
        }

        $response = $this->deleteCampaignServices->deleteCampaign();

        $url      = $this->campaignServices->getPageUrl();

        wp_redirect($url);
        exit;
    } 

    public function deleteCampaigns(){
        if ( 
            ! isset( $_GET['_wpnonce'] ) || 
            ! wp_verify_nonce( $_GET['_wpnonce'], ActionHelper::DELETE_CAMPAIGNS ) ||
            $_SERVER["REQUEST_METHOD"] !== "POST"

        ) {
            wp_nonce_ays( '' );
        }

        $type = "send";
        if(isset($_GET["type"]) && $_GET["type"] !== "send"){
            $type = "draft";
        }
        $response = $this->deleteCampaignServices->deleteCampaigns($type);
        
        $url      = $this->campaignServices->getPageUrl();

        wp_redirect($url);
        exit;
    }



}









