<?php

namespace Delipress\WordPress\Helpers;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

/**
 *
 * @author Delipress
 * @version 1.0.0
 * @since 1.0.0
 */
abstract class CampaignMetaHelper{

    const VIEW_CAMPAIGN_ONLINE      = "[delipress_view_campaign_online]";
    const SITE_URL                  = "[delipress_site_url]";
    const LINK_UNSUBSCRIBE          = "[delipress_link_unsubscribe]";

    public static function getMetas(){
        
        return apply_filters(DELIPRESS_SLUG . "_campaign_meta_helper", 
            array(
                self::VIEW_CAMPAIGN_ONLINE       => "viewCampaignOnline",
                self::LINK_UNSUBSCRIBE           => "linkUnsubscribe",
                self::SITE_URL                   => "siteUrl"
            )
        );

    }
}
