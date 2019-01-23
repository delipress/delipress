<?php

namespace Delipress\WordPress\Services;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\MediatorServicesInterface;

use Delipress\WordPress\Models\AbstractModel\AbstractOptionServices;

use Delipress\WordPress\Helpers\OptionHelper;
use Delipress\WordPress\Helpers\ActionHelper;
use Delipress\WordPress\Helpers\PageAdminHelper;
use Delipress\WordPress\Helpers\PostTypeHelper;
use Delipress\WordPress\Helpers\OptinHelper;
use Delipress\WordPress\Helpers\ProviderHelper;
use Delipress\WordPress\Helpers\ConnectorHelper;

/**
 * OptionServices
 *
 * @author Delipress
 * @version 1.0.0
 * @since 1.0.0
 */
class OptionServices extends AbstractOptionServices implements MediatorServicesInterface{

    protected $optionsDefault = array(
        "options"       => array(
            "from_to"           => "",
            "reply_to"          => "",
            "from_name"         => "",
            "license_key"       => "",
            "license_status"    => ""
        ),
        "subscribers" => array(
            "double_optin" => true,
            "text_subscription"           => "",
            "logo_subscription"           => "",
            "title_subscription"          => "",
            "button_subscription"         => "",
            "subscription_redirect"       => "",
        ),
        "others" => array(
            "show_setup" => true
        ),
        "provider"          => array(
            "client_id"          => "",
            "client_secret"      => "",
            "api_key_mailchimp"  => "",
            "api_key_sendinblue" => "",
            "api_key_sendgrid"   => "",
            "is_connect"         => false,
            "key"                => ""
        ),
        "pages" => array(
            "unsubscribe" => ""
        ),
        "connectors" => array(
            ConnectorHelper::WORDPRESS_USER => array(
                "active"  => false,
                "list_id" => 0
            ),
            ConnectorHelper::WOOCOMMERCE => array(
                "active"  => false,
                "list_id" => 0
            )
        ),
        'version' => null
    );

    protected $nameOptions = OptionHelper::OPTIONS_NAME;

    /**
     *
     * @param array $services
     * @return void
     */
    public function setServices($services){
        $this->campaignServices = $services["CampaignServices"];
    }


    /**
     * MD5 Key provider
     *
     * @return string
     */
    public function getMd5Provider(){
        $providerOption = $this->getProvider();
        switch($providerOption["key"]){
            case ProviderHelper::MAILCHIMP:
                return md5($providerOption["api_key_mailchimp"]);
            case ProviderHelper::MAILJET:
                return md5($providerOption["client_id"] . $providerOption["client_secret"]);
            case ProviderHelper::SENDGRID:
                return md5($providerOption["api_key_sendgrid"]);
            case ProviderHelper::SENDINBLUE:
                return md5($providerOption["api_key_sendinblue"]);
            default:
                return "";
        }
    }

    /**
     * @return array
     */
    public function getOptions($noCache = false){
        $settingsDefault = $this->getSettingsDefault();
        if($this->cacheOptions === null || $noCache){
            $this->cacheOptions = wp_parse_args( get_option($this->nameOptions), $settingsDefault );
            if(!isset($this->cacheOptions["options"]["from_name"]) || empty($this->cacheOptions["options"]["from_name"])){
                $this->cacheOptions["options"]["from_name"] = get_option("blogname");
            }
            if(!isset($this->cacheOptions["options"]["reply_to"])){
                $this->cacheOptions["options"]["reply_to"] = "";
            }
        }

        if(!isset($this->cacheOptions["subscribers"]["logo_subscription"]) ){
            $this->cacheOptions["subscribers"]["logo_subscription"] = $settingsDefault["subscribers"]["logo_subscription"];
        }

        if(!isset($this->cacheOptions["subscribers"]["text_subscription"]) ){
            $this->cacheOptions["subscribers"]["text_subscription"] = $settingsDefault["subscribers"]["text_subscription"];
        }

        if(!isset($this->cacheOptions["subscribers"]["title_subscription"]) ){
            $this->cacheOptions["subscribers"]["title_subscription"] = $settingsDefault["subscribers"]["title_subscription"];
        }

        if(!isset($this->cacheOptions["subscribers"]["button_subscription"]) ){
            $this->cacheOptions["subscribers"]["button_subscription"] = $settingsDefault["subscribers"]["button_subscription"];
        }

        if(!isset($this->cacheOptions["subscribers"]["subscription_redirect"]) ){
            $this->cacheOptions["subscribers"]["subscription_redirect"] = $settingsDefault["subscribers"]["subscription_redirect"];
        }

        return $this->cacheOptions;
    }

    public function getConnectors($noCache = false){
        $options = $this->getOptions($noCache);

        return $options["connectors"];
    }

    public function getConnectorByKey($key){
        $connectors = $this->getConnectors();

        if(!isset($connectors[$key])){
            return null;
        }

        return $connectors[$key];
    }

    /**
     * @return array
     */
    public function getProvider($noCache = false){
        $options = $this->getOptions($noCache);

        return $options["provider"];
    }

    /**
     * @return array
     */
    public function getProviderKey($noCache = false){
        $options = $this->getOptions($noCache);

        return $options["provider"]["key"];
    }

    /**
     * @return string
     */
    public function getVersion(){
        $options = $this->getOptions();

        return $options["version"];
    }

    /**
     * @return OptionServices
     */
    public function updateVersion(){
        $this->setOptions(
            array(
                "version" => DELIPRESS_VERSION
            )
        );

        return $this;
    }

    /**
     * @return boolean
     */
    public function isValidLicense(){
        $options = $this->getOptions();
        return (isset($options["options"]["license_status"]) && isset($options["options"]["license_status"]["status"]) && $options["options"]["license_status"]["status"] === "valid");
    }

    /**
     *
     * @return boolean
     */
    public function isFullLicense(){
       return true;
    }

    /**
     * @return boolean
     */
    public function canSendCampaignWithLicense(){
        $licenseStatus = $this->isValidLicense();
        if($licenseStatus){
            return true;
        }

        global $wpdb;

        $dateSend = PostTypeHelper::META_CAMPAIGN_DATE_SEND;
        $isSend   = PostTypeHelper::META_CAMPAIGN_IS_SEND;
        $typeSend = PostTypeHelper::META_CAMPAIGN_SEND;

        $beginDayMonth = new \DateTime("first day of this month");
        $beginDayMonth->setTime(0,0,0);
        $beginDayMonth = $beginDayMonth->format("Y-m-d H:i:s");
        $lastDayMonth = new \DateTime("last day of this month");
        $lastDayMonth->setTime(23,59,59);
        $lastDayMonth = $lastDayMonth->format("Y-m-d H:i:s");

        $sql = "SELECT {$wpdb->prefix}posts.ID
                FROM {$wpdb->prefix}posts
                INNER JOIN {$wpdb->prefix}postmeta ON ( {$wpdb->prefix}posts.ID = {$wpdb->prefix}postmeta.post_id )
                INNER JOIN {$wpdb->prefix}postmeta AS mt1 ON ( {$wpdb->prefix}posts.ID = mt1.post_id )
                INNER JOIN {$wpdb->prefix}postmeta AS mt2 ON ( {$wpdb->prefix}posts.ID = mt2.post_id )
                WHERE 1=1
                AND (
                    ( {$wpdb->prefix}postmeta.meta_key = '{$isSend}' AND {$wpdb->prefix}postmeta.meta_value = '1' )
                    AND ( mt1.meta_key = '{$dateSend}' )
                    AND ( mt2.meta_key = '{$typeSend}' )
                    AND (
                        CASE WHEN mt2.meta_value = 'now'
                            THEN  {$wpdb->prefix}posts.post_date >= '{$beginDayMonth}' AND {$wpdb->prefix}posts.post_date <= '{$lastDayMonth}'
                            ELSE mt1.meta_value >= '{$beginDayMonth}' AND mt1.meta_value <= '{$lastDayMonth}'
                        END
                    )
                )
                AND {$wpdb->prefix}posts.post_type = 'delipress-campaign'
                AND {$wpdb->prefix}posts.post_status = 'publish'
                LIMIT 0,1";

        $result    = $wpdb->get_var(
            $sql
        );

        if($result === null){
            return true;
        }

        return false;



    }

    /**
     *
     * @return string
     */
    public function getStatusMessageLicense(){
        $options = $this->getOptions();
        return (isset($options["options"]["license_status"]) && isset($options["options"]["license_status"]["message"])) ? $options["options"]["license_status"]["message"] : "";
    }

    /**
     *
     * @return string
     */
    public function getLicenseKey(){
        $options = $this->getOptions();
        return (isset($options["options"]["license_key"]) ) ? $options["options"]["license_key"] : "";
    }

    /**
     * @filter DELIPRESS_SLUG . "_option_default"
     * @return array $optionsDefault
     */
    public function getSettingsDefault(){
        $this->optionsDefault["options"]["from_to"]   = get_option("admin_email");
        $this->optionsDefault["options"]["from_name"] = get_option("blogname");

        $this->optionsDefault["subscribers"]["text_subscription"] = __("You're just one click away from subscribing to our newsletter. Just click on the button below to confirm your subscription. Welcome among us!", "delipress");
        $this->optionsDefault["subscribers"]["logo_subscription"] = DELIPRESS_PATH_PUBLIC_IMG . "/logo.svg";
        $this->optionsDefault["subscribers"]["title_subscription"] = __("Confirm your subscription", "delipress");
        $this->optionsDefault["subscribers"]["button_subscription"] = __("Confirm my subscription", "delipress");

        return apply_filters(DELIPRESS_SLUG . "_options_default", $this->optionsDefault);
    }

    /**
     *
     * @return string
     */
    public function getPageUrl($tab = null){
        return add_query_arg(
            array(
                "page"   => PageAdminHelper::getPageNameByConst(PageAdminHelper::PAGE_OPTIONS),
                "tab"    => $tab
            ),
            admin_url( 'admin.php' )
        );
    }


    /**
     *
     * @param string $currentTab
     * @return string
     */
    public function getUrlFormAdminPost($currentTab){
        switch($currentTab){
            case "providers":
                $action = ActionHelper::SETTINGS_PROVIDER;
                break;
            case "options":
            default:
                $action = ActionHelper::SETTINGS;
                break;
            case "export":
                $action = ActionHelper::LIST_IMPORT;
                break;
        }

        return add_query_arg(
            array(
                'tab'    => $currentTab,
                'action' => $action
            ),
            admin_url( 'options.php' )
        );
    }

    /**
     *
     * @param string $license
     * @return array
     */
    public function activateLicenseKey($license){
        $api_params = array(
			'edd_action' => 'activate_license',
			'license'    => $license,
			'item_name'  => urlencode( DELIPRESS_ITEM_NAME ),
			'url'        => home_url()
		);
        $status  = "valid";
        $message = __("License is valid", "delipress");
        $success = true;
        $priceId = null;

		$response = wp_remote_post( DELIPRESS_STORE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );
		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
            if ( is_wp_error( $response ) ) {
                $message = $response->get_error_message();
			} else {
                $message = __( 'An error occurred, please try again.', 'delipress' );
			}
            $success = false;
		} else {
            $license_data = json_decode( wp_remote_retrieve_body( $response ) );

			if ( false === $license_data->success ) {
                $success = false;
                $status  = $license_data->error;
                $message = $this->getMessageForLicenseError($license_data);
            }
            else{
                $priceId = $license_data->price_id;
            }
		}
        return array(
            "success" => $success,
            "results" => array(
                "status"   => $status,
                "message"  => $message,
                "price_id" => $priceId
            )
        );
    }
    /**
     *
     * @param string $license
     * @return array
     */
    public function checkLicense($license){
        $api_params = array(
			'edd_action' => 'check_license',
			'license'    => $license,
			'item_name'  => urlencode( DELIPRESS_ITEM_NAME ),
			'url'        => home_url()
		);
        $response = wp_remote_post( DELIPRESS_STORE_URL, array( 'body' => $api_params, 'timeout' => 15, 'sslverify' => false ) );
        if ( is_wp_error( $response ) ) {
            return false;
        }
        $priceId = null;
        $license_data = json_decode( wp_remote_retrieve_body( $response ) );
        if( $license_data->license == 'valid' ) {
            $priceId = $license_data->price_id;
            return array(
                "success" => true,
                "results" => array(
                    "status" => "valid",
                    "message" => __("License is valid", "delipress"),
                    "price_id" => $priceId
                )
            );
        } else {
            return array(
                "success" => false,
                "results" => array(
                    "status"  => $license_data->license,
                    "message" => $this->getMessageForLicenseError($license_data, "check"),
                    "price_id" => $priceId
                )
            );
        }
    }

    /**
     *
     * @param stdObject $license_data
     * @return string
     */
    public function getMessageForLicenseError($license_data, $from = "activate"){

        if($from === "activate"){
            $key = $license_data->error;
        }
        else{
            $key = $license_data->license;
        }

        switch( $key ) {
            case 'expired' :

                $message = sprintf(
                    __( 'Your license key expired on %s.' ),
                    date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) )
                );
                break;

            case 'revoked' :

                $message = __( 'Your license key has been disabled.', 'delipress' );
                break;

            case 'missing' :

                $message = __( 'Invalid license.', 'delipress' );
                break;

            case 'invalid' :
            case 'site_inactive' :

                $message = __( 'Your license is not active for this URL.', 'delipress' );
                break;

            case 'item_name_mismatch' :

                $message = sprintf( __( 'This appears to be an invalid license key for %s.' ), DELIPRESS_ITEM_NAME );
                break;

            case 'no_activations_left':

                $message = __( 'Your license key has reached its activation limit.', 'delipress' );
                break;
            default :
                $message = __( 'An error occurred, please try again.', 'delipress' );
                break;
        }

        return $message;
    }



}
