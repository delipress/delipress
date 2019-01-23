<?php

namespace Delipress\WordPress\Services\Template;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\MediatorServicesInterface;
use DeliSkypress\Models\ServiceInterface;

use Delipress\WordPress\Models\CampaignModel;
use Delipress\WordPress\Helpers\ActionHelper;


/**
 * TemplateUrlServices
 *
 * @author Delipress
 * @version 1.0.0
 * @since 1.0.0
 */
class TemplateUrlServices implements ServiceInterface, MediatorServicesInterface {

    /**
     *
     * @param array $services
     * @return void
     */
    public function setServices($services){

        $this->campaignServices = $services["CampaignServices"];

    }

    /**
     *
     * @param CampaignModel $campaign
     * @return string
     */
    public function getUrlPreviewTemplateFromCampaign(CampaignModel $campaign){

        $urlCampaignOnline = $this->campaignServices->getUrlViewCampaignOnline($campaign);

        $params = apply_filters(DELIPRESS_SLUG . "_template_params_preview_campaign", array(
            'width'  => '250',
            'height' => '250'
        ));

        $src = 'http://s.wordpress.com/mshots/v1/' . $urlCampaignOnline . '?' . http_build_query( $params, null, '&' );
        $cache_key = 'snapshot_' . md5( $src );
        $data_uri = get_transient( $cache_key );

        if ( ! $data_uri ) {
            $response = wp_remote_get( $src );
            if ( 200 === wp_remote_retrieve_response_code( $response ) ) {
                $image_data = wp_remote_retrieve_body( $response );
                if ( $image_data && is_string( $image_data ) ) {
                    $src = $data_uri = 'data:image/jpeg;base64,' . base64_encode( $image_data );
                    set_transient( $cache_key, $data_uri, DAY_IN_SECONDS );
                }
            }
        }

        return array(
            "src"   => esc_attr($src),
            "width" => $params["width"]
        );
    }

    /**
     *
     * @param int $campaignId
     * @param int $templateId
     * @return string
     */
    public function getUrlDeleteFromCampaign($campaignId, $templateId){
         return wp_nonce_url(
            admin_url( 
                sprintf(
                    'admin-post.php?action=%s&campaign_id=%s&template_id=%s',
                    ActionHelper::DELETE_TEMPLATE_FROM_CAMPAIGN,
                    $campaignId, 
                    $templateId
                )
            ),
            ActionHelper::DELETE_TEMPLATE_FROM_CAMPAIGN
        );
    }

}
