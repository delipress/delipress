<?php

namespace Delipress\WordPress\Helpers;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

/**
 *
 * @author Delipress
 * @version 1.0.0
 * @since 1.0.0
 */
abstract class ActionHelper{

    // ALL
    const REACT_AJAX                   = "react_ajax";
    const VIEW_CAMPAIGN_ONLINE         = "delipress_view_campaign_online";
    const REQUEST_UNSUBSCRIBE          = "delipress_request_unsubscribe";
    const UNSUBSCRIBE                  = "delipress_unsubscribe";
    const CONFIRM_SUBSCRIBE            = "delipress_confirm_subscribe";

    const SETTINGS_EXPORT             = "settings_export";
    const LIST_EXPORT                 = "list_export";
    const DP_REGISTER_NEWSLETTER      = "dp_register_newsletter";
    

    // CAMPAIGN
    const CREATE_CAMPAIGN             = "create_campaign";
    const CREATE_CAMPAIGN_STEP_ONE    = "create_campaign_step_one";
    const CREATE_CAMPAIGN_STEP_TWO    = "create_campaign_step_two";
    const CREATE_CAMPAIGN_STEP_THREE  = "create_campaign_step_three";
    const CREATE_CAMPAIGN_STEP_FOUR   = "create_campaign_step_four";
    const CREATE_CAMPAIGN_SEND_TEST   = "create_campaign_send_test";
    const PREVIEW_CAMPAIGN            = "preview_campaign";
    const SEND_CAMPAIGN               = "send_campaign";
    const DELETE_CAMPAIGN             = "delete_campaign";
    const DELETE_CAMPAIGNS            = "delete_campaigns";

    // LIST
    const CREATE_LIST                 = "create_list";
    const DELETE_LIST                 = "delete_list";
    const DELETE_LISTS                = "delete_lists";
    const SYNCHRONIZE_LIST_SUBSCRIBER_IMPORT            = "synchronize_list_subscriber_import";
    const SYNCHRONIZE_LIST_SUBSCRIBER_EXPORT            = "synchronize_list_subscriber_export";
    const CREATE_DYNAMIC_LIST         = "create_dynamic_list";
    
    // SUBSCRIBER
    const CREATE_SUBSCRIBER           = "create_subscriber";
    const DELETE_SUBSCRIBER_LIST      = "delete_subscriber_list";
    const DELETE_SUBSCRIBERS_LIST     = "delete_subscribers_list";
    const IMPORT_SUBSCRIBER_STEP_ONE  = "import_subscriber_step_one";
    const IMPORT_SUBSCRIBER_STEP_TWO  = "import_subscriber_step_two";
    

    // OPTIONS
    const SETTINGS_PROVIDER           = "providers";
    const SETTINGS                    = "options";

    const IMPORT_LISTS_PROVIDER       = "import_lists_provider";
    const EXPORT_LISTS_PROVIDER       = "export_lists_provider";
    const DISCONNECT_PROVIDER         = "disconnect_provider";
    
    // WIZARD
    const REMOVE_SETUP_WIZARD_PAGE    = "remove_setup_wizard_page";
    const WIZARD_STEP_1_CONNECT       = "wizard_step_1_connect";
    const WIZARD_STEP_1_SEND          = "wizard_step_1_send";
    const WIZARD_STEP_2_SYNCHRONIZE   = "wizard_step_2_synchronize";
    const WIZARD_STEP_2_CREATE_LIST   = "wizard_step_2_create_list";


    // OPTINS
    const CREATE_OPTIN                = "create_optin";
    const CREATE_OPTIN_STEP_TWO       = "create_optin_step_two";
    const CREATE_OPTIN_STEP_THREE     = "create_optin_step_three";
    const DELETE_OPTIN                = "delete_optin";
    const DELETE_OPTINS               = "delete_optins";
    
    const DELETE_TEMPLATE_FROM_CAMPAIGN               = "delete_template_from_campaign";

}









