<?php

namespace Delipress\WordPress\Helpers;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

abstract class CodeErrorHelper
{

    public static $messages = null;

    const TRY_CHEAT         = "try_cheat";
    const UNKNOW_ERROR      = "unknow_error";
    const SUCCESS           = "success";
    const ERROR             = "error";
    const NOT_EMPTY         = "not_empty";
    const ADMIN_NOTICE      = "admin_notice";

    const ADMIN_NOTICE_ERROR_DEFAULT = "admin_notice_error";


    //////////////////////////////// CODE CAMPAIGN
    const MISSING_CAMPAIGN_NAME                 = "missing_campaign_name";
    const MISSING_CAMPAIGN_TAXO_LISTS           = "missing_campaign_taxo_lists";
    const NOT_EMPTY_CAMPAIGN_NAME               = "not_empty_campaign_name";
    const NOT_EMPTY_META_CAMPAIGN_SUBJECT       = "not_empty_meta_campaign_subject";
    const NOT_EMPTY_META_CAMPAIGN_PROVIDER      = "not_empty_meta_campaign_provider";
    const NOT_EMPTY_META_CAMPAIGN_SEND          = "not_empty_meta_campaign_send";
    const NOT_EMPTY_META_CAMPAIGN_DATE_SEND     = "not_empty_meta_campaign_date_send";
    const SEND_CAMPAIGN_SUCCESS                 = "send_campaign_success";
    const SEND_LATER_CAMPAIGN_SUCCESS           = "send_later_campaign_success";
    const SEND_CAMPAIGN_ERROR                   = "send_campaign_error";

    //////////////////////////////// CODE SUBSCRIBER
    const CREATE_SUBSCRIBER_ON_LIST_SUCCESS                 = "create_subscriber_on_list_success";
    const DELETE_SYNCHRONIZE_SUBSCRIBER_ON_LIST_ERROR       = "delete_synchronize_subscriber_on_list_error";
    const DELETE_SYNCHRONIZE_SUBSCRIBER_ON_LIST_SUCCESS     = "delete_synchronize_subscriber_on_list_success";
    const META_SUBSCRIBER_EMAIL                             = "meta_subscriber_email";
    const META_SUBSCRIBER_CONFIRM                           = "meta_subscriber_confirm";

    //////////////////////////////// CODE OPTIONS
    const OPTIONS_SUCCESS                                 = "[options]";
    const OPTIONS_ERROR                                   = "[options_errors]";
    const OPTIONS_FROM_TO                                 = "[options][from_to]";
    const OPTIONS_REPLY_TO                                = "[options][reply_to]";
    const OPTIONS_FROM_NAME                               = "[options][from_name]";
    const OPTIONS_LICENSE_KEY                             = "[options][license_key]";
    const SYNCHRONIZE_SUCCESS                             = "synchronize_success";
    const PROVIDER_CONNEXION_ERROR                        = "provider_connexion_error";
    const TRANSIT_KEY_PROVIDER                            = "transit_key_provider";

    //////////////////////////////// CODE PROVIDER 
    const MAILJET_CLIENT_ID                     = "[options][providers][mailjet][client_id]";
    const MAILJET_CLIENT_SECRET                 = "[options][providers][mailjet][client_secret]";
    const MAILCHIMP_API_KEY                     = "[options][providers][mailchimp][api_key_mailchimp]";
    const SENDINBLUE_API_KEY                    = "[options][providers][sendinblue][api_key_sendinblue]";
    const SENDGRID_API_KEY                      = "[options][providers][sendgrid][api_key_sendgrid]";

    //////////////////////////////// CODE OPTIN
    const MISSING_OPTIN_NAME        =  "missing_optin_name";
    const MISSING_OPTIN_TAXO_LISTS  =  "missing_optin_taxo_lists";
    const MISSING_OPTIN_IS_ACTIVE   =  "missing_optin_is_active" ;
    const OPTIN_CREATED             =  "optin_created" ;


    /**
     * @param string $code  const CodeErrorHelper
     */
    public static function getMessage($code){
        if(self::$messages == null){
            self::$messages = array(
                self::ADMIN_NOTICE_ERROR_DEFAULT       => __("Something went wrong. Look below for errors.","delipress"),
                self::TRY_CHEAT                         => __("Cheatin&#8217; uh?"),
                self::NOT_EMPTY                         => __("This field can't be empty", "delipress"),
                self::SEND_CAMPAIGN_SUCCESS             => __("Campaign sent with success", "delipress"),
                self::SEND_LATER_CAMPAIGN_SUCCESS       => __("Campaign has been successsfully scheduled", "delipress"),
                self::MISSING_CAMPAIGN_NAME             => __("Missing campaign name parameter", "delipress"),
                self::MISSING_CAMPAIGN_TAXO_LISTS       => __("Recipient list must be provided", "delipress"),
                self::NOT_EMPTY_CAMPAIGN_NAME           => __("Campaign name cannot be empty", "delipress"),
                self::NOT_EMPTY_META_CAMPAIGN_SUBJECT   => __("You need a subject for your campaign", "delipress"),
                self::NOT_EMPTY_META_CAMPAIGN_DATE_SEND => __("You need a date for your campaign", "delipress"),

                self::NOT_EMPTY_META_CAMPAIGN_PROVIDER => __("You need a provider for your campaign", "delipress"),
                self::NOT_EMPTY_META_CAMPAIGN_SEND     => __("When do you want to send this campaign?", "delipress"),
                self::SEND_CAMPAIGN_ERROR              => __("An error occured while sending this campaign", "delipress"),
                self::CREATE_SUBSCRIBER_ON_LIST_SUCCESS => __("Subscriber has successfully been added to the list", "delipress"),

                self::OPTIONS_SUCCESS                   => __("Settings saved", "delipress"),
                self::SYNCHRONIZE_SUCCESS               => __("Synchronization successful", "delipress"),
                self::PROVIDER_CONNEXION_ERROR          => __("The credentials provided are incorrect", "delipress"),
                self::MISSING_OPTIN_NAME                => __("Missing parameters", "delipress"),
                self::MISSING_OPTIN_TAXO_LISTS          => __("Missing parameters", "delipress"),
                self::MISSING_OPTIN_IS_ACTIVE           => __("Missing parameters", "delipress"),
                self::DELETE_SYNCHRONIZE_SUBSCRIBER_ON_LIST_SUCCESS           => __("Subscriber successfully deleted", "delipress"),
                self::OPTIN_CREATED                     => esc_html__("Opt-in form successfully created", "delipress")
            );
        }

        if(array_key_exists($code, self::$messages)){
            return self::$messages[$code];
        }

        return false;
    }


}
