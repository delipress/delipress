<?php

namespace Delipress\WordPress\Admin\Template;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\HooksInterface;
use DeliSkypress\Models\ContainerInterface;
use DeliSkypress\WordPress\Actions\AbstractHook;

use Delipress\WordPress\Helpers\ActionHelper;

/**
 * DeleteTemplate
 */
class DeleteTemplate extends AbstractHook implements HooksInterface {

    /**
     *  @param ContainerInterface $containerServices
     *  @see AbstractHook
     */
    public function setContainerServices(ContainerInterface $containerServices){
        $this->deleteTemplateServices = $containerServices->getService("DeleteTemplateServices");
        $this->campaignServices       = $containerServices->getService("CampaignServices");
    }

    /**
     * @see HooksInterface
     */
    public function hooks(){
        if(current_user_can('manage_options' ) ){
            add_action( 'admin_post_' . ActionHelper::DELETE_TEMPLATE_FROM_CAMPAIGN, array($this, 'deleteTemplateFromCampaign') );
        }
    }

    /**
     * @return void
     */
    public function deleteTemplateFromCampaign(){

        if ( 
            ! isset( $_GET['_wpnonce'] ) || 
            ! wp_verify_nonce( $_GET['_wpnonce'], ActionHelper::DELETE_TEMPLATE_FROM_CAMPAIGN ) ||
            ! isset( $_GET["template_id"]) ||
            ! isset( $_GET["campaign_id"]) ||
            $_SERVER["REQUEST_METHOD"] !== "GET"

        ) {
            wp_nonce_ays( '' );
        }

        $response = $this->deleteTemplateServices->deleteTemplate((int) $_GET["template_id"]);

        $url = $this->campaignServices->getCreateUrlByNextStep(2, (int)  $_GET["campaign_id"]);

        wp_redirect($url);
        exit;
    }
    


}









