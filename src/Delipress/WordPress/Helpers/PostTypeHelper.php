<?php

namespace Delipress\WordPress\Helpers;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

/**
 *
 * @author Delipress
 * @version 1.0.0
 * @since 1.0.0
 */
abstract class PostTypeHelper{
    
    // Custom Post Type
    const CPT_CAMPAIGN                        = "delipress-campaign";
    const CPT_OPTINFORMS                      = "delipress-optinform";
    const CPT_DYNAMIC_LIST                    = "delipress-dynamic-list";
    const CPT_TEMPLATE                        = "delipress-template";

    // TEMPLATE 
    const META_TEMPLATE_CONFIG                = "_delipress_template_config";

    // CAMPAIGN
    const CAMPAIGN_NAME                       = "_delipress_campaign_name";
    const CAMPAIGN_TAXO_LISTS                 = "_delipress_campaign_lists";
    const META_CAMPAIGN_SUBJECT               = "_delipress_campaign_subject";
    const META_CAMPAIGN_SEND                  = "_delipress_campaign_send";
    const META_CAMPAIGN_DATE_SEND             = "_delipress_campaign_date_send";
    const META_CAMPAIGN_IS_SEND               = "_delipress_campaign_is_send";
    const META_CAMPAIGN_CAMPAIGN_PROVIDER     = "_delipress_campaign_campaign_provider";
    const META_CAMPAIGN_CAMPAIGN_PROVIDER_ID  = "_delipress_campaign_campaign_provider_id";
    const META_CAMPAIGN_TEMPLATE_CONFIG       = "_delipress_campaign_template_config";
    const META_CAMPAIGN_TEMPLATE_HTML         = "_delipress_campaign_template_html";
    const META_CAMPAIGN_TOKEN_ONLINE          = "_delipress_campaign_token_online";
    
    // OPTIN
    const OPTIN_NAME                          = "_delipress_optin_name";
    const OPTIN_TAXO_LISTS                    = "_delipress_optin_lists";
    const META_OPTIN_IS_ACTIVE                = "_delipress_optin_is_active";
    const META_OPTIN_CONFIG                   = "_delipress_optin_config";
    const META_OPTIN_TYPE                     = "_delipress_optin_type";
    const META_OPTIN_BEHAVIOR                 = "_delipress_optin_behavior";
    const META_OPTIN_COUNTER_VIEW             = "_delipress_optin_counter_view";
    const META_OPTIN_COUNTER_CONVERT          = "_delipress_optin_counter_convert";
    const META_OPTIN_TIMESERIES               = "_delipress_optin_timeseries";

}
